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
      'name' => $user->name,
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

  public function logout(Request $request)
  {
    $request->user()->tokens()->delete();

    return response()->json(['message' => 'berhasil logout'], 200);
  }

  public function getUser()
  {
    $user = Auth::user();
    $data = [
      'user_id' => $user->id,
      'name' => $user->name,
  ];
    return response()->json($data, 200);
  }

  public function editPassword(Request $request)
  {
    $userId = Auth::user()->id;

    $request->validate([
      'password' => 'required|string|min:8',
    ]); 

    $user = User::whereId($userId)->first();
    if (!$user) return response()->json(['message' => 'user tidak ditemukan'], 404);

    $user->update([
      'password' => Hash::make($request->password)
    ]);

    return response()->json([
      'message' => 'Berhasil mengubah password',
    ], 200);
  }

  public function resetPassword(Request $request)
{
    $request->validate([
      'user_id' => 'required|string',
    ]);

    $user = User::whereId($request->user_id)->first();
        if (!$user) return response()->json(['message' => 'user tidak ditemukan'], 404);

        $user->update([
            'password' => Hash::make('12345678')
        ]);

    return response()->json([
        'message' => 'Berhasil mereset password',
    ], 200);
}
}
