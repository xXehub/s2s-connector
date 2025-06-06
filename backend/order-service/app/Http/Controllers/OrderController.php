<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => OrderResource::collection(Order::all())
        ]);
    }

    public function show($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new OrderResource($order)
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'product_id' => 'required|integer',
            'quantity' => 'required|integer'
        ]);

        try {
            // Pakai nama container,
            $userResponse = Http::timeout(5)->get("http://user-service:9000/api/users/{$validated['user_id']}");
            if ($userResponse->status() !== 200) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found in user-service'
                ], 404);
            }
            $user = $userResponse->json()['data'];

            $productResponse = Http::timeout(5)->get("http://product-service:9000/api/products/{$validated['product_id']}");
            if ($productResponse->status() !== 200) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found in product-service'
                ], 404);
            }
            $product = $productResponse->json()['data'];

            if ($validated['quantity'] > $product['stock']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not enough stock for this product'
                ], 400);
            }

            $order = Order::create([
                'user_id'       => $validated['user_id'],
                'product_id'    => $validated['product_id'],
                'product_name'  => $product['name'],
                'product_price' => $product['price'],
                'quantity'      => $validated['quantity'],
                'total_price'   => $product['price'] * $validated['quantity']
            ]);

            // Endpoint reduce stok harus PATCH
            Http::timeout(5)->patch("http://product-service:9000/api/products/{$validated['product_id']}/reduce-stock", [
                'quantity' => $validated['quantity']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => [
                    'user_id' => $order->user_id,
                    'user_name' => $user['name'],
                    'product_id' => $order->product_id,
                    'product_name' => $order->product_name,
                    'price' => $order->product_price,
                    'quantity' => $order->quantity,
                    'total_price' => $order->total_price
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        $validated = $request->validate([
            'product_name' => 'sometimes|required|string',
            'product_price' => 'sometimes|required|numeric',
            'quantity' => 'sometimes|required|integer'
        ]);

        $order->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully',
            'data' => new OrderResource($order)
        ]);
    }

    public function destroy($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        $order->delete();

        return response()->json([
            'success' => true,
            'message' => 'Order deleted successfully'
        ]);
    }

    public function getOrdersByUser($user_id)
    {
        $orders = Order::where('user_id', $user_id)->get();

        if ($orders->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No orders found for this user'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Order history fetched successfully',
            'data' => OrderResource::collection($orders)
        ]);
    }

    public function getOrdersByProduct($product_id)
    {
        $orders = Order::where('product_id', $product_id)->get();

        if ($orders->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No orders found for this product'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Orders for this product fetched successfully',
            'data' => OrderResource::collection($orders)
        ]);
    }
}
