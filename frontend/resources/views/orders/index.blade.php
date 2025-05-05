@extends('layouts.app')

@section('title', 'Orders')

@section('page-title', 'Orders')

@section('page-actions')
<div class="col-auto ms-auto d-print-none">
    <div class="btn-list">
        <a href="{{ route('orders.create') }}" class="btn btn-primary d-none d-sm-inline-block">
            <i class="ti ti-plus"></i>
            Create Order
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-body border-bottom py-3">
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
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
                            <td>{{ $order['user_id'] }}</td>
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
                                    <form action="{{ route('orders.destroy', $order['id']) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No orders found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection