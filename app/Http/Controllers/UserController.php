<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getAllUser(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string'
        ]);

        $search = $request->input('search');

        if ($search) {
            $users = User::with('role:id,name')->select('id', 'name', 'email', 'active', 'role_id')->where('name', 'like', '%' . $search . '%')->paginate(20);
        } else {
            $users = User::with('role:id,name')->select('id', 'name', 'email', 'active', 'role_id')->paginate(20);
        }

    return response()->json($users, 200); 
    }

    public function promoteAdmin(Request $request){
        $request->validate([
            'user_id' => 'required|string',
        ]);

        User::find($request->input('user_id'))->update([
            'role_id' => 2
        ]);

        return response()->json(['message' => 'Promote success'], 200);
    }
    
    public function deleteUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|string',
        ]);

        $user = User::find($request->input('user_id'));
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->setAttribute('active', 0);
        $user->save();
        $user->delete();

        return response()->json(['message' => 'Delete success'], 200);
    }
}
