<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\ProductService;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        $response = $this->productService->getAllProducts();
        $products = $response['success'] ? $response['data'] : [];
        
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $response = $this->productService->createProduct($validated);

        if ($response['success'] ?? false) {
            return redirect()->route('products.index')->with('success', 'Product created successfully');
        }

        return back()->withInput()->with('error', $response['message'] ?? 'Failed to create product');
    }

    public function edit($id)
    {
        $response = $this->productService->getProductById($id);
        
        if (!($response['success'] ?? false)) {
            return redirect()->route('products.index')->with('error', 'Product not found');
        }

        $product = $response['data'];
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $response = $this->productService->updateProduct($id, $validated);

        if ($response['success'] ?? false) {
            return redirect()->route('products.index')->with('success', 'Product updated successfully');
        }

        return back()->withInput()->with('error', $response['message'] ?? 'Failed to update product');
    }

    public function destroy($id)
    {
        $response = $this->productService->deleteProduct($id);

        if ($response['success'] ?? false) {
            return redirect()->route('products.index')->with('success', 'Product deleted successfully');
        }

        return redirect()->route('products.index')->with('error', $response['message'] ?? 'Failed to delete product');
    }
}