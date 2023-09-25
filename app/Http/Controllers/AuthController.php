<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Queue\Jobs\RedisJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
  public function login(Request $request)
  {
    $request->validate([
      'email' => 'required',
      'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();
    if (!$user || !Hash::check($request->password, $user->password) || !$user->active) {
      return response()->json([
        'message' => 'credentials does not match',
      ], 401);
    }

    $token = $user->createToken('access_token')->plainTextToken;
    return response()->json([
      'token' => $token,
      'role' => $user->role->name,
    ]);
  }

  public function register(Request $request)
  {
    try {
      $request->validate([
        'name' => 'required',
        'email' => [
          'required',
          'string',
          'email',
          Rule::unique('users'), // Memastikan email unik dalam tabel 'users'
        ],
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
    } catch (ValidationException $e) {
      return response()->json([
        'message' => 'Data sudah ada'
      ], 409);
    }
  }

  public function getUser()
  {
    $user = Auth::user();
    return response()->json($user, 200);
  }
}
