<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProductService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('PRODUCT_SERVICE_URL', 'http://product-web') . '/api';
    }

    public function getAllProducts()
    {
        try {
            $response = Http::timeout(30)->get("{$this->baseUrl}/products");
            
            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json('data') ?? $response->json()
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Failed to fetch products: ' . $response->status()
            ];
        } catch (\Exception $e) {
            Log::error('ProductService getAllProducts error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage()
            ];
        }
    }

    public function getProductById($id)
    {
        try {
            $response = Http::timeout(30)->get("{$this->baseUrl}/products/{$id}");
            
            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json('data') ?? $response->json()
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Product not found'
            ];
        } catch (\Exception $e) {
            Log::error('ProductService getProductById error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage()
            ];
        }
    }

    public function createProduct($data)
    {
        try {
            $response = Http::timeout(30)->post("{$this->baseUrl}/products", $data);
            
            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json('data') ?? $response->json()
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Failed to create product: ' . $response->body()
            ];
        } catch (\Exception $e) {
            Log::error('ProductService createProduct error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage()
            ];
        }
    }

    public function updateProduct($id, $data)
    {
        try {
            $response = Http::timeout(30)->put("{$this->baseUrl}/products/{$id}", $data);
            
            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json('data') ?? $response->json()
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Failed to update product: ' . $response->body()
            ];
        } catch (\Exception $e) {
            Log::error('ProductService updateProduct error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage()
            ];
        }
    }

    public function deleteProduct($id)
    {
        try {
            $response = Http::timeout(30)->delete("{$this->baseUrl}/products/{$id}");
            
            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Product deleted successfully'
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Failed to delete product: ' . $response->body()
            ];
        } catch (\Exception $e) {
            Log::error('ProductService deleteProduct error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage()
            ];
        }
    }
}
