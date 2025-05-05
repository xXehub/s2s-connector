<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;

class OrderService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('ORDER_SERVICE_URL');
    }

    public function getAllOrders()
    {
        $response = Http::get("{$this->baseUrl}/orders");
        return $response->json();
    }

    public function getOrderById($id)
    {
        $response = Http::get("{$this->baseUrl}/orders/{$id}");
        return $response->json();
    }

    public function createOrder($data)
    {
        $response = Http::post("{$this->baseUrl}/orders", $data);
        return $response->json();
    }

    public function updateOrder($id, $data)
    {
        $response = Http::put("{$this->baseUrl}/orders/{$id}", $data);
        return $response->json();
    }

    public function deleteOrder($id)
    {
        $response = Http::delete("{$this->baseUrl}/orders/{$id}");
        return $response->json();
    }

    public function getOrdersByUser($userId)
    {
        $response = Http::get("{$this->baseUrl}/orders/user/{$userId}");
        return $response->json();
    }

    public function getOrdersByProduct($productId)
    {
        $response = Http::get("{$this->baseUrl}/orders/product/{$productId}");
        return $response->json();
    }
}