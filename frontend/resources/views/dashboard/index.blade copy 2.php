<x-app>
    <div class="page-wrapper">
        <div class="container-xl">
            <!-- Page title -->
            <div class="page-header d-print-none">
                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="page-title">
                            Dashboard
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-body">
            <div class="container-xl">
                <div class="row row-deck row-cards">
                    <div class="col-sm-6 col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="subheader">Total Users</div>
                                </div>
                                <div class="h1 mb-3">{{ $userCount ?? 0 }}</div>
                                <div class="d-flex mb-2">
                                    <div>
                                        <a href="{{ route('users.index') }}" class="btn btn-primary btn-sm">
                                            View all users
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="subheader">Total Products</div>
                                </div>
                                <div class="h1 mb-3">{{ $productCount ?? 0 }}</div>
                                <div class="d-flex mb-2">
                                    <div>
                                        <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm">
                                            View all products
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="subheader">Total Orders</div>
                                </div>
                                <div class="h1 mb-3">{{ $orderCount ?? 0 }}</div>
                                <div class="d-flex mb-2">
                                    <div>
                                        <a href="{{ route('orders.index') }}" class="btn btn-primary btn-sm">
                                            View all orders
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Recent Orders</h3>
                            </div>
                            <div class="card-body border-bottom py-3">
                                <div class="table-responsive">
                                    <table class="table table-vcenter card-table data-table">
                                        <thead>
                                            <tr>
                                                <th>Order ID</th>
                                                <th>Product</th>
                                                <th>Price</th>
                                                <th>Quantity</th>
                                                <th>Total</th>
                                                <th>Date</th>
                                                <th class="w-1"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recentOrders ?? [] as $order)
                                                <tr>
                                                    <td>{{ $order['id'] }}</td>
                                                    <td>{{ $order['product_name'] ?? 'N/A' }}</td>
                                                    <td>${{ number_format($order['product_price'] ?? 0, 2) }}</td>
                                                    <td>{{ $order['quantity'] ?? 0 }}</td>
                                                    <td>${{ number_format(($order['product_price'] ?? 0) * ($order['quantity'] ?? 0), 2) }}</td>
                                                    <td>{{ $order['created_at'] ?? 'N/A' }}</td>
                                                    <td>
                                                        <a href="{{ route('orders.show', $order['id']) }}" class="btn btn-sm btn-primary">
                                                            View
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center">No recent orders found</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center">
                                <a href="{{ route('orders.index') }}" class="btn btn-link">View all orders</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-footer />
    </div>
</x-app>