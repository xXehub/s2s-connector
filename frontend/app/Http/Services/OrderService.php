<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('ORDER_SERVICE_URL', 'http://order-web') . '/api';
    }

    public function getAllOrders()
    {
        try {
            $response = Http::timeout(30)->get("{$this->baseUrl}/orders");
            
            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json('data') ?? $response->json()
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Failed to fetch orders: ' . $response->status()
            ];
        } catch (\Exception $e) {
            Log::error('OrderService getAllOrders error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage()
            ];
        }
    }

    public function getOrderById($id)
    {
        try {
            $response = Http::timeout(30)->get("{$this->baseUrl}/orders/{$id}");
            
            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json('data') ?? $response->json()
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Order not found'
            ];
        } catch (\Exception $e) {
            Log::error('OrderService getOrderById error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage()
            ];
        }
    }

    public function createOrder($data)
    {
        try {
            $response = Http::timeout(30)->post("{$this->baseUrl}/orders", $data);
            
            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json('data') ?? $response->json()
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Failed to create order: ' . ($response->json('message') ?? $response->body())
            ];
        } catch (\Exception $e) {
            Log::error('OrderService createOrder error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage()
            ];
        }
    }

    public function deleteOrder($id)
    {
        try {
            $response = Http::timeout(30)->delete("{$this->baseUrl}/orders/{$id}");
            
            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Order deleted successfully'
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Failed to delete order: ' . $response->body()
            ];
        } catch (\Exception $e) {
            Log::error('OrderService deleteOrder error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage()
            ];
        }
    }

    public function getOrdersByUser($userId)
    {
        try {
            $response = Http::timeout(30)->get("{$this->baseUrl}/orders/user/{$userId}");
            
            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json('data') ?? $response->json()
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Failed to fetch user orders: ' . $response->status()
            ];
        } catch (\Exception $e) {
            Log::error('OrderService getOrdersByUser error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage()
            ];
        }
    }

    public function getOrdersByProduct($productId)
    {
        try {
            $response = Http::timeout(30)->get("{$this->baseUrl}/orders/product/{$productId}");
            
            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json('data') ?? $response->json()
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Failed to fetch product orders: ' . $response->status()
            ];
        } catch (\Exception $e) {
            Log::error('OrderService getOrdersByProduct error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage()
            ];
        }
    }
}
