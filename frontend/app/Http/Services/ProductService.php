<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;

class ProductService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('PRODUCT_SERVICE_URL');
    }

    public function getAllProducts()
    {
        $response = Http::get("{$this->baseUrl}/products");
        return $response->json();
    }

    public function getProductById($id)
    {
        $response = Http::get("{$this->baseUrl}/products/{$id}");
        return $response->json();
    }

    public function createProduct($data)
    {
        $response = Http::post("{$this->baseUrl}/products", $data);
        return $response->json();
    }

    public function updateProduct($id, $data)
    {
        $response = Http::put("{$this->baseUrl}/products/{$id}", $data);
        return $response->json();
    }

    public function deleteProduct($id)
    {
        $response = Http::delete("{$this->baseUrl}/products/{$id}");
        return $response->json();
    }
}