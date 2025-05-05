@extends('layouts.app')

@section('title', 'User Orders')

@section('page-title', 'Orders for ' . $user['name'])

@section('page-actions')
<div class="col-auto ms-auto d-print-none">
    <div class="btn-list">
        <a href="{{ route('users.index') }}" class="btn btn-secondary d-none d-sm-inline-block">
            <i class="ti ti-arrow-left"></i>
            Back to Users
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
        <h3 class="card-title">User Information</h3>
    </div>
    <div class="card-body">
        <div class="datagrid">
            <div class="datagrid-item">
                <div class="datagrid-title">User ID</div>
                <div class="datagrid-content">{{ $user['id'] }}</div>
            </div>
            <div class="datagrid-item">
                <div class="datagrid-title">Name</div>
                <div class="datagrid-content">{{ $user['name'] }}</div>
            </div>
            <div class="datagrid-item">
                <div class="datagrid-title">Email</div>
                <div class="datagrid-content">{{ $user['email'] }}</div>
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
                        <th>Product</th>
                        <th>Price</th>
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
                            <td>{{ $order['product_name'] }}</td>
                            <td>${{ number_format($order['product_price'], 2) }}</td>
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
                            <td colspan="7" class="text-center">No orders found for this user</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection