@extends('layouts.app')

@section('title', 'Edit User')

@section('page-title', 'Edit User')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('users.update', $user['id']) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label required">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user['name']) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label required">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user['email']) }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-footer">
                <button type="submit" class="btn btn-primary">Update User</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection