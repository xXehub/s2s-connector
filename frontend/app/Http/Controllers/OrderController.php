<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\OrderService;
use App\Http\Services\UserService;
use App\Http\Services\ProductService;

class OrderController extends Controller
{
    protected $orderService;
    protected $userService;
    protected $productService;

    public function __construct(
        OrderService $orderService,
        UserService $userService,
        ProductService $productService
    ) {
        $this->orderService = $orderService;
        $this->userService = $userService;
        $this->productService = $productService;
    }

    public function index()
    {
        $response = $this->orderService->getAllOrders();
        $orders = $response['success'] ? $response['data'] : [];
        
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $usersResponse = $this->userService->getAllUsers();
        $productsResponse = $this->productService->getAllProducts();
        
        $users = $usersResponse['success'] ? $usersResponse['data'] : [];
        $products = $productsResponse['success'] ? $productsResponse['data'] : [];
        
        return view('orders.create', compact('users', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        $response = $this->orderService->createOrder($validated);

        if ($response['success'] ?? false) {
            return redirect()->route('orders.index')->with('success', 'Order created successfully');
        }

        return back()->withInput()->with('error', $response['message'] ?? 'Failed to create order');
    }

    public function show($id)
    {
        $response = $this->orderService->getOrderById($id);
        
        if (!($response['success'] ?? false)) {
            return redirect()->route('orders.index')->with('error', 'Order not found');
        }

        $order = $response['data'];
        return view('orders.show', compact('order'));
    }

    public function userOrders($userId)
    {
        $userResponse = $this->userService->getUserById($userId);
        $ordersResponse = $this->orderService->getOrdersByUser($userId);
        
        if (!($userResponse['success'] ?? false)) {
            return redirect()->route('users.index')->with('error', 'User not found');
        }
        
        $user = $userResponse['data'];
        $orders = $ordersResponse['success'] ? $ordersResponse['data'] : [];
        
        return view('orders.user-orders', compact('user', 'orders'));
    }

    public function productOrders($productId)
    {
        $productResponse = $this->productService->getProductById($productId);
        $ordersResponse = $this->orderService->getOrdersByProduct($productId);
        
        if (!($productResponse['success'] ?? false)) {
            return redirect()->route('products.index')->with('error', 'Product not found');
        }
        
        $product = $productResponse['data'];
        $orders = $ordersResponse['success'] ? $ordersResponse['data'] : [];
        
        return view('orders.product-orders', compact('product', 'orders'));
    }

    public function destroy($id)
    {
        $response = $this->orderService->deleteOrder($id);

        if ($response['success'] ?? false) {
            return redirect()->route('orders.index')->with('success', 'Order deleted successfully');
        }

        return redirect()->route('orders.index')->with('error', $response['message'] ?? 'Failed to delete order');
    }
}