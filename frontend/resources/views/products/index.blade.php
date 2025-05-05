@extends('layouts.app')

@section('title', 'Products')

@section('page-title', 'Products')

@section('page-actions')
<div class="col-auto ms-auto d-print-none">
    <div class="btn-list">
        <a href="{{ route('products.create') }}" class="btn btn-primary d-none d-sm-inline-block">
            <i class="ti ti-plus"></i>
            Add Product
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
                        <th>Name</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Created At</th>
                        <th class="w-1">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>{{ $product['id'] }}</td>
                            <td>{{ $product['name'] }}</td>
                            <td>Rp. {{ number_format($product['price'], 2) }}</td>
                            <td>{{ $product['stock'] }}</td>
                            <td>{{ $product['created_at'] }}</td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a href="{{ route('products.edit', $product['id']) }}" class="btn btn-sm btn-primary">
                                        Edit
                                    </a>
                                    <a href="{{ route('products.orders', $product['id']) }}" class="btn btn-sm btn-info">
                                        Orders
                                    </a>
                                    <form action="{{ route('products.destroy', $product['id']) }}" method="POST" class="d-inline">
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
                            <td colspan="6" class="text-center">No products found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection