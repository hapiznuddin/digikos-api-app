<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request) {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();
        if (! $user || ! Hash::check($request->password, $user->password) || ! $user->active) {
            return response()->json([
                'message' => 'credentials does not match',
            ], 401);
        }

        $token = $user->createToken('access_token')->plainTextToken;
        return response()->json([
            'token' => $token,
        ]);

    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'Berhasil mendaftar',
        ], 201);
    }
}
