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
        return response()->json([
            'success' => true,
            'data' => ProductResource::collection(Product::all())
        ]);
    }

    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }

        return response()->json(['success' => true, 'data' => new ProductResource($product)]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer'
        ]);

        $product = Product::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'data' => new ProductResource($product)
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) return response()->json(['success' => false, 'message' => 'Product not found'], 404);

        $validated = $request->validate([
            'name' => 'sometimes|string',
            'price' => 'sometimes|numeric',
            'stock' => 'sometimes|integer'
        ]);

        $product->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'data' => new ProductResource($product)
        ]);
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) return response()->json(['success' => false, 'message' => 'Product not found'], 404);

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully'
        ]);
    }

    public function reduceStock(Request $request, $id)
    {
        Log::info("Received reduce stock request for product {$id}", [
            'request_data' => $request->all(),
            'headers' => $request->headers->all()
        ]);

        $product = Product::find($id);
        if (!$product) {
            Log::error("Product not found: {$id}");
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        Log::info("Current product stock: {$product->stock}, requested reduction: {$validated['quantity']}");

        if ($product->stock < $validated['quantity']) {
            Log::error("Insufficient stock for product {$id}. Available: {$product->stock}, Requested: {$validated['quantity']}");
            return response()->json([
                'success' => false, 
                'message' => 'Stock insufficient',
                'current_stock' => $product->stock,
                'requested' => $validated['quantity']
            ], 400);
        }

        $oldStock = $product->stock;
        $product->stock -= $validated['quantity'];
        $product->save();

        Log::info("Stock reduced successfully for product {$id}. Old stock: {$oldStock}, New stock: {$product->stock}");

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
