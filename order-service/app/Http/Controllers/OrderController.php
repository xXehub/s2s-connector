<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    // GET /api/orders
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => OrderResource::collection(Order::all())
        ]);
    }

    // GET /api/orders/{id}
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

    // POST /api/orders
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'product_name' => 'required|string',
            'quantity' => 'required|integer'
        ]);

        $userServiceUrl = "http://localhost:8001/api/users/{$validated['user_id']}";
        $response = Http::get($userServiceUrl);

        if ($response->status() !== 200) {
            return response()->json([
                'success' => false,
                'message' => 'User not found in user-service'
            ], 404);
        }

        $order = Order::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Order created successfully',
            'data' => new OrderResource($order)
        ], 201);
    }

    // PUT/PATCH /api/orders/{id}
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
            'quantity' => 'sometimes|required|integer'
        ]);

        $order->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully',
            'data' => new OrderResource($order)
        ]);
    }

    // DELETE /api/orders/{id}
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
}
