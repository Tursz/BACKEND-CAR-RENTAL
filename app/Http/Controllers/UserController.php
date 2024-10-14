<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!User::first()) {
            return response()->json(['data' => 'No users'], Response::HTTP_NO_CONTENT);
        }

        return response()->json(['data' => User::all()], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:120', 'min:5'],
            'email' => ['required', 'email'],
            'is_admin' => ['in:admin,user'],
        ]);

        $user = User::create($request->all());
        return response()->json(['data' => $user], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['data' => 'User not found'], Response::HTTP_NO_CONTENT);
        }

        return response()->json(['data' => $user], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['data' => 'User not found'], Response::HTTP_NO_CONTENT);
        }

        $user->update([
            'name' => $request->name ?: $user->name,
            'email' => $request->email ?: $user->email,
        ]);
        return response()->json(['data' => $user], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['data' => 'User not found'], Response::HTTP_NO_CONTENT);
        }
        if (!$request->user()->is_admin === 'admin') {
            return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted'], Response::HTTP_NO_CONTENT);
    }
}
