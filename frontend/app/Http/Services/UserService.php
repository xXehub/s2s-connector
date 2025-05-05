<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;

class UserService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('USER_SERVICE_URL');
    }

    public function getAllUsers()
    {
        $response = Http::get("{$this->baseUrl}/users");
        return $response->json();
    }

    public function getUserById($id)
    {
        $response = Http::get("{$this->baseUrl}/users/{$id}");
        return $response->json();
    }

    public function createUser($data)
    {
        $response = Http::post("{$this->baseUrl}/users", $data);
        return $response->json();
    }

    public function updateUser($id, $data)
    {
        $response = Http::put("{$this->baseUrl}/users/{$id}", $data);
        return $response->json();
    }

    public function deleteUser($id)
    {
        $response = Http::delete("{$this->baseUrl}/users/{$id}");
        return $response->json();
    }
}