<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Rent;
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
        $invoices = Invoice::whereIn('status', $statuses)->get();

        return response()->json($invoices, 200);
    }
}
