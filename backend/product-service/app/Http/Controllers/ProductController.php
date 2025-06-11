<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return response()->json([
            'success' => true,
            'data' => ProductResource::collection($products)
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $product = Product::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'data' => new ProductResource($product)
        ], 201);
    }

    public function show(Product $product)
    {
        return response()->json([
            'success' => true,
            'data' => new ProductResource($product)
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
        ]);

        $product->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'data' => new ProductResource($product)
        ]);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully'
        ]);
    }

    public function reduceStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'order_id' => 'required|integer'
        ]);

        Log::info('Stock reduction request received', [
            'product_id' => $product->id,
            'current_stock' => $product->stock,
            'requested_quantity' => $validated['quantity'],
            'order_id' => $validated['order_id']
        ]);

        if ($product->stock < $validated['quantity']) {
            Log::warning('Insufficient stock for reduction', [
                'product_id' => $product->id,
                'available_stock' => $product->stock,
                'requested_quantity' => $validated['quantity'],
                'order_id' => $validated['order_id']
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock available',
                'data' => [
                    'available_stock' => $product->stock,
                    'requested_quantity' => $validated['quantity']
                ]
            ], 400);
        }

        $oldStock = $product->stock;
        $product->decrement('stock', $validated['quantity']);
        $product->refresh();

        Log::info('Stock reduced successfully', [
            'product_id' => $product->id,
            'old_stock' => $oldStock,
            'new_stock' => $product->stock,
            'reduced_quantity' => $validated['quantity'],
            'order_id' => $validated['order_id']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Stock reduced successfully',
            'data' => [
                'product_id' => $product->id,
                'old_stock' => $oldStock,
                'new_stock' => $product->stock,
                'reduced_quantity' => $validated['quantity']
            ]
        ]);
    }
}
