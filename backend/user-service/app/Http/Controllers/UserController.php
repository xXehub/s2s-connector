<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => UserResource::collection(User::all())
        ]);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => new UserResource($user)
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
        ]);

        $user = User::create($validated);
        return response()->json([
            'success' => true,
            'data' => new UserResource($user)
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User not found'
        ], 404);
    }

    $validated = $request->validate([
        'name'  => 'sometimes|required|string',
        'email' => 'sometimes|required|email|unique:users,email,' . $id,
    ]);

    $user->update($validated);

    return response()->json([
        'success' => true,
        'message' => 'User updated successfully',
        'data' => new UserResource($user)
    ]);
    
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }
}
