<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\CheckInvoiceResource;
use App\Http\Resources\GetAllInvoiceResource;
use App\Models\Invoice;
use App\Models\Rent;
use App\Models\User;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function createInvoice(Request $request)
    {

        $request->validate([
            'date' => 'required|date',
        ]);

        $invoiceDate = $request->input('date');
        $rentIds = Rent::pluck('id')->toArray();

        if (empty($rentIds)) {
            return response()->json([
                'message' => 'No rent records found',
            ], 404);
        }

        $invoice = Invoice::where('invoice_date', $invoiceDate)->exists();
        
        if ($invoice) {
            return response()->json([
                'message' => 'Invoice sudah pernah dibuat',
            ], 409);
        }

        foreach ($rentIds as $rentId) {
            Invoice::create([
                'rent_id' => $rentId,
                'invoice_date' => $invoiceDate,
                'status' => 'Belum bayar',
            ]);
        }

        return response()->json([
            'message' => 'Success',
        ], 201);
    }

    public function getInvoiceByStatus()
    {
        $statuses = ['Belum bayar', 'Pending'];
        $invoices = Invoice::with('rent')
                    ->whereIn('status', $statuses)
                    ->get();

        return response()->json(
            GetAllInvoiceResource::collection($invoices),
        );
    }

    public function getCheckInvoice(Request $request)
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

        $invoice = Invoice::where('rent_id', $rent->id)
                ->where('status', 'Belum bayar')
                ->where('status', 'Pending')
                ->first();

        if (!$invoice) {
            return response()->json(['message' => 'Tagihan sudah lunas'], 404);
        }

        return new CheckInvoiceResource($invoice);
    }
}
