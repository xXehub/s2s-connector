<x-app>
    <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">
                            Dashboard
                        </h2>
                        <div class="text-muted mt-1">Microservices Management System</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page body -->
        <div class="page-body">
            <div class="container-xl">
                @if(session('error'))
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-title">Service Connection Error</h4>
                        <div class="text-muted">{{ session('error') }}</div>
                    </div>
                @endif

                <!-- Statistics Cards -->
                <div class="row row-deck row-cards">
                    <div class="col-sm-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="subheader">Total Users</div>
                                </div>
                                <div class="h1 mb-3">{{ number_format($stats['total_users']) }}</div>
                                <div class="d-flex mb-2">
                                    <div class="flex-fill">
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-primary" style="width: {{ min(100, $stats['total_users'] * 2) }}%" role="progressbar"></div>
                                        </div>
                                    </div>
                                    <div class="ms-3">
                                        <span class="text-green d-inline-flex align-items-center lh-1">
                                            {{ $stats['total_users'] }} registered
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="subheader">Total Products</div>
                                </div>
                                <div class="h1 mb-3">{{ number_format($stats['total_products']) }}</div>
                                <div class="d-flex mb-2">
                                    <div class="flex-fill">
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-info" style="width: {{ min(100, $stats['total_products'] * 3) }}%" role="progressbar"></div>
                                        </div>
                                    </div>
                                    <div class="ms-3">
                                        <span class="text-blue d-inline-flex align-items-center lh-1">
                                            {{ $stats['total_products'] }} items
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="subheader">Total Orders</div>
                                </div>
                                <div class="h1 mb-3">{{ number_format($stats['total_orders']) }}</div>
                                <div class="d-flex mb-2">
                                    <div class="flex-fill">
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-warning" style="width: {{ min(100, $stats['total_orders'] * 5) }}%" role="progressbar"></div>
                                        </div>
                                    </div>
                                    <div class="ms-3">
                                        <span class="text-yellow d-inline-flex align-items-center lh-1">
                                            {{ $stats['total_orders'] }} orders
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="subheader">Total Revenue</div>
                                </div>
                                <div class="h1 mb-3">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
                                <div class="d-flex mb-2">
                                    <div class="flex-fill">
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-success" style="width: {{ min(100, $stats['total_revenue'] / 10000) }}%" role="progressbar"></div>
                                        </div>
                                    </div>
                                    <div class="ms-3">
                                        <span class="text-green d-inline-flex align-items-center lh-1">
                                            Total sales
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Data -->
                <div class="row row-deck row-cards mt-4">
                    <!-- Recent Users -->
                    <div class="col-md-6 col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Recent Users</h3>
                            </div>
                            <div class="card-body p-0">
                                <div class="list-group list-group-flush">
                                    @forelse($recentUsers as $user)
                                        <div class="list-group-item">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <span class="avatar">{{ substr($user['name'], 0, 2) }}</span>
                                                </div>
                                                <div class="col text-truncate">
                                                    <strong>{{ $user['name'] }}</strong>
                                                    <div class="text-muted">{{ $user['email'] }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="list-group-item text-center text-muted">
                                            No users found
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('users.index') }}" class="btn btn-primary btn-sm">View All Users</a>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Products -->
                    <div class="col-md-6 col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Recent Products</h3>
                            </div>
                            <div class="card-body p-0">
                                <div class="list-group list-group-flush">
                                    @forelse($recentProducts as $product)
                                        <div class="list-group-item">
                                            <div class="row align-items-center">
                                                <div class="col text-truncate">
                                                    <strong>{{ $product['name'] }}</strong>
                                                    <div class="text-muted">
                                                        Rp {{ number_format($product['price'], 0, ',', '.') }} 
                                                        | Stock: {{ $product['stock'] }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="list-group-item text-center text-muted">
                                            No products found
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('products.index') }}" class="btn btn-info btn-sm">View All Products</a>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Orders -->
                    <div class="col-md-6 col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Recent Orders</h3>
                            </div>
                            <div class="card-body p-0">
                                <div class="list-group list-group-flush">
                                    @forelse($recentOrders as $order)
                                        <div class="list-group-item">
                                            <div class="row align-items-center">
                                                <div class="col text-truncate">
                                                    <strong>Order #{{ $order['id'] }}</strong>
                                                    <div class="text-muted">
                                                        User: {{ $order['user_id'] }} | 
                                                        Qty: {{ $order['quantity'] }} |
                                                        Rp {{ number_format(($order['product_price'] ?? 0) * ($order['quantity'] ?? 0), 0, ',', '.') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="list-group-item text-center text-muted">
                                            No orders found
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('orders.index') }}" class="btn btn-warning btn-sm">View All Orders</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Quick Actions</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <a href="{{ route('users.create') }}" class="btn btn-primary w-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <circle cx="12" cy="7" r="4" />
                                                <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                            </svg>
                                            Add New User
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <a href="{{ route('products.create') }}" class="btn btn-info w-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <rect x="4" y="4" width="16" height="16" rx="2" />
                                                <line x1="9" y1="9" x2="15" y2="15" />
                                                <line x1="15" y1="9" x2="9" y2="15" />
                                            </svg>
                                            Add New Product
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <a href="{{ route('orders.create') }}" class="btn btn-warning w-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <circle cx="9" cy="19" r="2" />
                                                <circle cx="20" cy="19" r="2" />
                                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2 -1.61l1.6 -8.39h-15.2" />
                                            </svg>
                                            Create New Order
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <a href="http://localhost:15672" target="_blank" class="btn btn-success w-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <rect x="3" y="12" width="6" height="8" rx="1" />
                                                <rect x="9" y="8" width="6" height="12" rx="1" />
                                                <rect x="15" y="4" width="6" height="16" rx="1" />
                                                <line x1="4" y1="20" x2="18" y2="20" />
                                            </svg>
                                            RabbitMQ Monitor
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-footer />
    </div>
</x-app>
