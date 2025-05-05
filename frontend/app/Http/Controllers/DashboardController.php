<?php

namespace App\Http\Controllers;

use App\Http\Services\UserService;
use App\Http\Services\ProductService;
use App\Http\Services\OrderService;

class DashboardController extends Controller
{
    protected $userService;
    protected $productService;
    protected $orderService;

    public function __construct(
        UserService $userService,
        ProductService $productService,
        OrderService $orderService
    ) {
        $this->userService = $userService;
        $this->productService = $productService;
        $this->orderService = $orderService;
    }

    public function index()
    {
        $usersResponse = $this->userService->getAllUsers();
        $productsResponse = $this->productService->getAllProducts();
        $ordersResponse = $this->orderService->getAllOrders();

        $userCount = 0;
        $productCount = 0;
        $orderCount = 0;
        $recentOrders = [];

        if ($usersResponse['success'] ?? false) {
            $userCount = count($usersResponse['data']);
        }

        if ($productsResponse['success'] ?? false) {
            $productCount = count($productsResponse['data']);
        }

        if ($ordersResponse['success'] ?? false) {
            $orderCount = count($ordersResponse['data']);
            $recentOrders = array_slice($ordersResponse['data'], 0, 5);
        }

        return view('dashboard.index', compact(
            'userCount',
            'productCount',
            'orderCount',
            'recentOrders'
        ));
    }
}