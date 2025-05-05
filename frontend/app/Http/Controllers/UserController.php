<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\UserService;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $response = $this->userService->getAllUsers();
        $users = $response['success'] ? $response['data'] : [];
        
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        $response = $this->userService->createUser($validated);

        if ($response['success'] ?? false) {
            return redirect()->route('users.index')->with('success', 'User created successfully');
        }

        return back()->withInput()->with('error', $response['message'] ?? 'Failed to create user');
    }

    public function edit($id)
    {
        $response = $this->userService->getUserById($id);
        
        if (!($response['success'] ?? false)) {
            return redirect()->route('users.index')->with('error', 'User not found');
        }

        $user = $response['data'];
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        $response = $this->userService->updateUser($id, $validated);

        if ($response['success'] ?? false) {
            return redirect()->route('users.index')->with('success', 'User updated successfully');
        }

        return back()->withInput()->with('error', $response['message'] ?? 'Failed to update user');
    }

    public function destroy($id)
    {
        $response = $this->userService->deleteUser($id);

        if ($response['success'] ?? false) {
            return redirect()->route('users.index')->with('success', 'User deleted successfully');
        }

        return redirect()->route('users.index')->with('error', $response['message'] ?? 'Failed to delete user');
    }
}