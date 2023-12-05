<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\GetAllMessageResource;
use App\Models\ComplaintMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintMessageController extends Controller
{
    public function createMessage(Request $request)
    {
        $request->validate([
            'user_id' => 'required|string',
            'message' => 'required|string',
            'description' => 'required|string',
        ]);

        $userId = $request->input('user_id');
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $occupant = $user->occupant()->first();
        if (!$occupant) {
            return response()->json(['message' => 'Occupant not found'], 404);
        }

        $rent = $occupant->rent()->first();
        if (!$rent) {
            return response()->json(['message' => 'Rent not found'], 404);
        }
        ComplaintMessage::create([
            'rent_id' => $rent->id,
            'message' => $request->message,
            'description' => $request->description,
            'status' => 'Terkirim',
        ]);

        return response()->json([
            'message' => 'Success',
        ], 201);
    }

    public function getMessageByUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|string',
        ]);

        $userId = $request->input('user_id');
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $occupant = $user->occupant()->first();
        if (!$occupant) {
            return response()->json(['message' => 'Occupant not found'], 404);
        }

        $rent = $occupant->rent()->first();
        if (!$rent) {
            return response()->json(['message' => 'Rent not found'], 404);
        }
        $messages = ComplaintMessage::where('rent_id', $rent->id)->get();

        return response()->json([
            'data' => GetAllMessageResource::collection($messages),
        ]);
    }

    public function getMessageByAdmin(Request $request)
    {
        $request->validate([
            'filter' => 'nullable|string',
        ]);
    
        $filter = $request->input('filter');
    
        $messages = ComplaintMessage::when($filter, function ($query) use ($filter) {
            return $query->where('status', 'like', '%' . $filter . '%');
        })->paginate(20);
    
        return response()->json([
            'data' => GetAllMessageResource::collection($messages),
            'pagination' => [
                'current_page' => $messages->currentPage(),
                'last_page' => $messages->lastPage(),
                'per_page' => $messages->perPage(),
                'total' => $messages->total(),
            ],
        ]);
    }

    public function getDetailMessage(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        $message = ComplaintMessage::find($request->input('id'));

        if (!$message) {
            return response()->json(['message' => 'Message not found'], 404);
        }

        return response()->json($message, 200);
    }

    public function approveMessage(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'set_status' => 'required|string',
        ]);

        $message = ComplaintMessage::find($request->input('id'));
        
        if (!$message) {
            return response()->json(['message' => 'Message not found'], 404);
        }

        $message->update([
            'status' => $request->input('set_status'),
        ]);

        return response()->json([
            'message' => 'Success',
        ], 201);
    }
}
