@extends('layouts.app')

@section('title', 'Users')

@section('page-title', 'Users')

@section('page-actions')
<div class="col-auto ms-auto d-print-none">
    <div class="btn-list">
        <a href="{{ route('users.create') }}" class="btn btn-primary d-none d-sm-inline-block">
            <i class="ti ti-plus"></i>
            Add User
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
                        <th>Email</th>
                        <th>Created At</th>
                        <th class="w-1">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user['id'] }}</td>
                            <td>{{ $user['name'] }}</td>
                            <td>{{ $user['email'] }}</td>
                            <td>{{ $user['created_at'] ?? 'N/A' }}</td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a href="{{ route('users.edit', $user['id']) }}" class="btn btn-sm btn-primary">
                                        Edit
                                    </a>
                                    <a href="{{ route('users.orders', $user['id']) }}" class="btn btn-sm btn-info">
                                        Orders
                                    </a>
                                    <form action="{{ route('users.destroy', $user['id']) }}" method="POST" class="d-inline">
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
                            <td colspan="5" class="text-center">No users found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection