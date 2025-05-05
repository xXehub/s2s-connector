@extends('layouts.app')

@section('title', 'Edit Product')

@section('page-title', 'Edit Product')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('products.update', $product['id']) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label required">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $product['name']) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label required">Price</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price', $product['price']) }}" required>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label required">Stock</label>
                <input type="number" min="0" class="form-control @error('stock') is-invalid @enderror" name="stock" value="{{ old('stock', $product['stock']) }}" required>
                @error('stock')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-footer">
                <button type="submit" class="btn btn-primary">Update Product</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection