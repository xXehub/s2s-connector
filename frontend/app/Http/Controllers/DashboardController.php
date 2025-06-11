<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        try {
            // Get statistics from all services
            $usersResponse = $this->userService->getAllUsers();
            $productsResponse = $this->productService->getAllProducts();
            $ordersResponse = $this->orderService->getAllOrders();

            $stats = [
                'total_users' => $usersResponse['success'] ? count($usersResponse['data']) : 0,
                'total_products' => $productsResponse['success'] ? count($productsResponse['data']) : 0,
                'total_orders' => $ordersResponse['success'] ? count($ordersResponse['data']) : 0,
                'total_revenue' => 0
            ];

            // Calculate total revenue
            if ($ordersResponse['success']) {
                foreach ($ordersResponse['data'] as $order) {
                    $stats['total_revenue'] += ($order['product_price'] ?? 0) * ($order['quantity'] ?? 0);
                }
            }

            // Get recent data
            $recentUsers = $usersResponse['success'] ? array_slice($usersResponse['data'], -5) : [];
            $recentProducts = $productsResponse['success'] ? array_slice($productsResponse['data'], -5) : [];
            $recentOrders = $ordersResponse['success'] ? array_slice($ordersResponse['data'], -5) : [];

            return view('dashboard.index', compact('stats', 'recentUsers', 'recentProducts', 'recentOrders'));
        } catch (\Exception $e) {
            $stats = [
                'total_users' => 0,
                'total_products' => 0,
                'total_orders' => 0,
                'total_revenue' => 0
            ];
            
            $recentUsers = [];
            $recentProducts = [];
            $recentOrders = [];
            
            return view('dashboard.index', compact('stats', 'recentUsers', 'recentProducts', 'recentOrders'))
                ->with('error', 'Unable to connect to services: ' . $e->getMessage());
        }
    }
}
