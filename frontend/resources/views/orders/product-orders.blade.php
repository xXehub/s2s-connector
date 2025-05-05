@extends('layouts.app')

@section('title', 'Product Orders')

@section('page-title', 'Orders for ' . $product['name'])

@section('page-actions')
    <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
            <a href="{{ route('products.index') }}" class="btn btn-secondary d-none d-sm-inline-block">
                <i class="ti ti-arrow-left"></i>
                Back to Products
            </a>
            <a href="{{ route('orders.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                <i class="ti ti-plus"></i>
                Create Order
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Product Information</h3>
        </div>
        <div class="card-body">
            <div class="datagrid">
                <div class="datagrid-item">
                    <div class="datagrid-title">Product ID</div>
                    <div class="datagrid-content">{{ $product['id'] }}</div>
                </div>
                <div class="datagrid-item">
                    <div class="datagrid-title">Name</div>
                    <div class="datagrid-content">{{ $product['name'] }}</div>
                </div>
                <div class="datagrid-item">
                    <div class="datagrid-title">Price</div>
                    <div class="datagrid-content">Rp. {{ number_format($product['price'], 2) }}</div>
                </div>
                <div class="datagrid-item">
                    <div class="datagrid-title">Stock</div>
                    <div class="datagrid-content">{{ $product['stock'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Order History</h3>
        </div>
        <div class="card-body border-bottom py-3">
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User ID</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Created At</th>
                            <th class="w-1">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>{{ $order['id'] }}</td>
                                <td>{{ $order['user_id'] }}</td>
                                <td>{{ $order['quantity'] }}</td>
                                <td>${{ number_format($order['product_price'] * $order['quantity'], 2) }}</td>
                                <td>{{ $order['created_at'] }}</td>
                                <td>
                                    <div class="btn-list flex-nowrap">
                                        <a href="{{ route('orders.show', $order['id']) }}" class="btn btn-sm btn-primary">
                                            View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No orders found for this product</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
