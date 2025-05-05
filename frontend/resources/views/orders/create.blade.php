@extends('layouts.app')

@section('title', 'Create Order')

@section('page-title', 'Create Order')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('orders.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label required">User</label>
                <select class="form-select @error('user_id') is-invalid @enderror" name="user_id" required>
                    <option value="">Select User</option>
                    @foreach($users as $user)
                        <option value="{{ $user['id'] }}" {{ old('user_id') == $user['id'] ? 'selected' : '' }}>
                            {{ $user['name'] }} ({{ $user['email'] }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label required">Product</label>
                <select class="form-select @error('product_id') is-invalid @enderror" name="product_id" required>
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                        <option value="{{ $product['id'] }}" {{ old('product_id') == $product['id'] ? 'selected' : '' }}>
                            {{ $product['name'] }} - Rp. {{ number_format($product['price'], 2) }} ({{ $product['stock'] }} in stock)
                        </option>
                    @endforeach
                </select>
                @error('product_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label required">Quantity</label>
                <input type="number" min="1" class="form-control @error('quantity') is-invalid @enderror" name="quantity" value="{{ old('quantity', 1) }}" required>
                @error('quantity')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-footer">
                <button type="submit" class="btn btn-primary">Create Order</button>
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection