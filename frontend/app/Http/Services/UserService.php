<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UserService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('USER_SERVICE_URL', 'http://user-web') . '/api';
    }

    public function getAllUsers()
    {
        try {
            $response = Http::timeout(30)->get("{$this->baseUrl}/users");
            
            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json('data') ?? $response->json()
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Failed to fetch users: ' . $response->status()
            ];
        } catch (\Exception $e) {
            Log::error('UserService getAllUsers error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage()
            ];
        }
    }

    public function getUserById($id)
    {
        try {
            $response = Http::timeout(30)->get("{$this->baseUrl}/users/{$id}");
            
            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json('data') ?? $response->json()
                ];
            }
            
            return [
                'success' => false,
                'message' => 'User not found'
            ];
        } catch (\Exception $e) {
            Log::error('UserService getUserById error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage()
            ];
        }
    }

    public function createUser($data)
    {
        try {
            $response = Http::timeout(30)->post("{$this->baseUrl}/users", $data);
            
            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json('data') ?? $response->json()
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Failed to create user: ' . $response->body()
            ];
        } catch (\Exception $e) {
            Log::error('UserService createUser error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage()
            ];
        }
    }

    public function updateUser($id, $data)
    {
        try {
            $response = Http::timeout(30)->put("{$this->baseUrl}/users/{$id}", $data);
            
            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json('data') ?? $response->json()
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Failed to update user: ' . $response->body()
            ];
        } catch (\Exception $e) {
            Log::error('UserService updateUser error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage()
            ];
        }
    }

    public function deleteUser($id)
    {
        try {
            $response = Http::timeout(30)->delete("{$this->baseUrl}/users/{$id}");
            
            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'User deleted successfully'
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Failed to delete user: ' . $response->body()
            ];
        } catch (\Exception $e) {
            Log::error('UserService deleteUser error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage()
            ];
        }
    }
}
