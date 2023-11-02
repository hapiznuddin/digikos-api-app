<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Occupant;
use App\Models\Payment;
use App\Models\Rent;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function createPayment(Request $request)
    {
        $request->validate([
            'price' => 'required|integer',
            'occupant_id' => 'required|string',
            'room_id' => 'required|integer',
            'rent_id' => 'required|integer',
        ]);

        $payment = Payment::create([
            'order_id' => Str::uuid(),
            'price' => $request->price,
            'status' => 'pending',
            'occupant_id' => $request->occupant_id,
            'room_id' => $request->room_id,
            'rent_id' => $request->rent_id
        ]);


        $params = array(
            'transaction_details' => array(
                'order_id' => $payment->order_id,
                'gross_amount' => $payment->price,
            ),
            // 'occupant_id' => $request->occupant_id,
            'customer_details' => array(
                'first_name' => $payment->occupant->name, // Menggunakan variabel $occupant yang telah didefinisikan sebelumnya
                'email' => $payment->occupant->user->email,
                'phone' => $payment->occupant->phone,
            ),
            // 'room_id' => $request->room_id,
            'item_details' => array(
                'name' => $payment->room->classRoom->room_name,
                'quantity' => 1,
                'price' => $payment->price,
            ),
            // 'rent_id' => $request->rent_id,
        );
        // dd($params);
        $auth = base64_encode(env('MIDTRANS_SERVER_KEY'));

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => "Basic $auth",
        ])->post('https://app.sandbox.midtrans.com/snap/v1/transactions', $params);

        $response = json_decode($response->body());

        $payment->update([
            'order_id' => $params['transaction_details']['order_id'],
            'checkout_link' => $response->redirect_url,
            'checkout_token' => $response->token
        ]);

        return response()->json($response);
    }

    public function webhookPayment(Request $request)
    {
        try {
        $auth = base64_encode(env('MIDTRANS_SERVER_KEY'));

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => "Basic $auth",
        ])->timeout(10)->get("https://api.sandbox.midtrans.com/v2/$request->order_id/status");

        $response = json_decode($response->body());

        $payment = Payment::where('order_id', $response->order_id)->firstOrFail();

        if ($payment->status === 'settlement' || $payment->status === 'capture') {
            return response()->json('Payment success');
        }

        if ($response->transaction_status === 'capture') {
            $payment->update([
                'status' => 'Lunas',
            ]);
            $payment->rent->update([
                'status_id' => 4,
            ]);
        } else if ($response->transaction_status === 'settlement') {
            $payment->update([
                'status' => 'Lunas',
            ]);
            $payment->rent->update([
                'status_id' => 4,
            ]);
        } else if ($response->transaction_status === 'pending') {
            $payment->update([
                'status' => 'pending',
            ]);
        } else if ($response->transaction_status === 'deny') {
            $payment->update([
                'status' => 'deny',
            ]);
        } else if ($response->transaction_status === 'cancel') {
            $payment->update([
                'status' => 'cancel',
            ]);
        }
        return response()->json('Berhasil');
    } catch (\Exception $e) {
        Log::error('Webhook error: ' . $e->getMessage());
        return response()->json('Terjadi kesalahan internal', 500);
    }
    }

    public function getHistoryPayment(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $payment = Payment::where('occupant_id', $request->id)->get();
        if ($payment->isEmpty()) {
            return response()->json(['message' => 'Payment not found'], 404);
        }
        return response()->json($payment, 200);
    }
}
