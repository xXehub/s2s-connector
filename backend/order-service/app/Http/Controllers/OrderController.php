<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        try {
            $orders = Order::all();
            return response()->json([
                'status' => 'success',
                'message' => 'List of orders retrieved successfully',
                'data' => $orders
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get orders: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve orders',
                'data' => null
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'product_id' => 'required|integer',
            'product_quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
                'data' => null
            ], 422);
        }

        try {
            Log::info("🛒 [ORDER-SERVICE] Creating new order", $request->all());

            // Get product info
            $productServiceUrl = env('PRODUCT_SERVICE_URL', 'http://product-web') . "/api/products/{$request->product_id}";
            $productResponse = Http::timeout(5)->get($productServiceUrl);
            
            if ($productResponse->failed()) {
                Log::error("Product not found", ['product_id' => $request->product_id]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product not found',
                    'data' => null
                ], 404);
            }

            $product = $productResponse->json('data');

            // Check stock
            if ($product['stock'] < $request->product_quantity) {
                Log::warning("Insufficient stock", [
                    'available' => $product['stock'],
                    'requested' => $request->product_quantity
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => "Insufficient stock. Available: {$product['stock']}, Requested: {$request->product_quantity}",
                    'data' => null
                ], 400);
            }

            // Create order with ALL required fields
            $order = Order::create([
                'user_id' => $request->user_id,
                'product_id' => $request->product_id,
                'product_name' => $product['name'],
                'product_price' => $product['price'],
                'quantity' => $request->product_quantity,
                'total_price' => $product['price'] * $request->product_quantity,
                'status' => 'pending'
            ]);

            Log::info("✅ [ORDER-SERVICE] Order created successfully", ['order_id' => $order->id]);

            // DISPATCH SIMPLE JOB TO RABBITMQ
            \App\Jobs\ReduceProductStock::dispatch(
                $request->product_id,
                $request->product_quantity
            )->onQueue('product-stock-update');

            Log::info("🐰 [ORDER-SERVICE] ReduceProductStock dispatched to RabbitMQ", [
                'product_id' => $request->product_id,
                'quantity' => $request->product_quantity
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Order created successfully!.',
                'data' => $order
            ], 201);

        } catch (\Exception $e) {
            Log::error("Order creation failed", ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Order creation failed: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $order = Order::find($id);
            
            if (!$order) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Order not found',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Order found',
                'data' => $order
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to get order: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve order',
                'data' => null
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $order = Order::find($id);
            
            if (!$order) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Order not found',
                    'data' => null
                ], 404);
            }

            $order->update($request->all());
            
            return response()->json([
                'status' => 'success',
                'message' => 'Order updated successfully',
                'data' => $order
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to update order: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update order',
                'data' => null
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $order = Order::find($id);
            
            if (!$order) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Order not found',
                    'data' => null
                ], 404);
            }

            $order->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Order deleted successfully',
                'data' => null
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to delete order: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete order',
                'data' => null
            ], 500);
        }
    }

    // NEW METHOD: Get orders by user ID
    public function getOrdersByUser($userId)
    {
        try {
            Log::info("📋 [ORDER-SERVICE] Getting orders for user", ['user_id' => $userId]);

            $orders = Order::where('user_id', $userId)
                          ->orderBy('created_at', 'desc')
                          ->get();

            if ($orders->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'No orders found for this user',
                    'data' => []
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => "Found {$orders->count()} orders for user {$userId}",
                'data' => $orders
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to get orders by user: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve orders for user',
                'data' => null
            ], 500);
        }
    }

    // NEW METHOD: Get orders by product ID
    public function getOrdersByProduct($productId)
    {
        try {
            Log::info("📋 [ORDER-SERVICE] Getting orders for product", ['product_id' => $productId]);

            $orders = Order::where('product_id', $productId)
                          ->orderBy('created_at', 'desc')
                          ->get();

            if ($orders->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'No orders found for this product',
                    'data' => []
                ]);
            }

            // Calculate total quantity sold
            $totalQuantity = $orders->sum('quantity');
            $totalRevenue = $orders->sum('total_price');

            return response()->json([
                'status' => 'success',
                'message' => "Found {$orders->count()} orders for product {$productId}",
                'data' => $orders,
                'summary' => [
                    'total_orders' => $orders->count(),
                    'total_quantity_sold' => $totalQuantity,
                    'total_revenue' => $totalRevenue
                ]
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to get orders by product: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve orders for product',
                'data' => null
            ], 500);
        }
    }

    // ALIAS METHOD: For backward compatibility
    public function getByUser($userId)
    {
        return $this->getOrdersByUser($userId);
    }
}
