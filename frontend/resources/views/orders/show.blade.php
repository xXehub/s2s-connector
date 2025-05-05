@extends('layouts.app')

@section('title', 'Order Details')

@section('page-title', 'Order Details')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Order #{{ $order['id'] }}</h3>
        </div>
        <div class="card-body">
            <div class="datagrid">
                <div class="datagrid-item">
                    <div class="datagrid-title">Order ID</div>
                    <div class="datagrid-content">{{ $order['id'] }}</div>
                </div>
                <div class="datagrid-item">
                    <div class="datagrid-title">User ID</div>
                    <div class="datagrid-content">{{ $order['user_id'] }}</div>
                </div>
                <div class="datagrid-item">
                    <div class="datagrid-title">Product ID</div>
                    <div class="datagrid-content">{{ $order['product_id'] }}</div>
                </div>
                <div class="datagrid-item">
                    <div class="datagrid-title">Product Name</div>
                    <div class="datagrid-content">{{ $order['product_name'] }}</div>
                </div>
                <div class="datagrid-item">
                    <div class="datagrid-title">Product Price</div>
                    <div class="datagrid-content">${{ number_format($order['product_price'], 2) }}</div>
                </div>
                <div class="datagrid-item">
                    <div class="datagrid-title">Quantity</div>
                    <div class="datagrid-content">{{ $order['quantity'] }}</div>
                </div>
                <div class="datagrid-item">
                    <div class="datagrid-title">Total Price</div>
                    <div class="datagrid-content">${{ number_format($order['product_price'] * $order['quantity'], 2) }}
                    </div>
                </div>
                <div class="datagrid-item">
                    <div class="datagrid-title">Created At</div>
                    <div class="datagrid-content">{{ $order['created_at'] }}</div>
                </div>
                <div class="datagrid-item">
                    <div class="datagrid-title">Updated At</div>
                    <div class="datagrid-content">{{ $order['updated_at'] ?? 'N/A' }}</div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex">
                <a href="{{ route('orders.index') }}" class="btn btn-link">Back to Orders</a>
                <form action="{{ route('orders.destroy', $order['id']) }}" method="POST" class="ms-auto">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                        Delete Order
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
