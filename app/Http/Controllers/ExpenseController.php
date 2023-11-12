<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function createExpense(Request $request)
    {
        $request->validate([
            'expense' => 'required|string',
            'period' =>  'required|string',
            'date_paid' => 'required|date',
            'total_payment' => 'required|integer',
            'employee' => 'required|string',
        ]);

        $data = [
            'expense' => $request->expense,
            'period' => $request->period,
            'date_paid' => $request->date_paid,
            'total_payment' => $request->total_payment,
            'employee' => $request->employee,
        ];

        $expense = Expense::create($data);

        return response()->json([
            'message' => 'Berhasil',
        ], 200);
    }

    public function getExpense(Request $request)
    {
		$search = $request->input('search', null);

        $expense = Expense::select('id', 'expense', 'period', 'date_paid', 'total_payment', 'employee');
        if ($search) {
            $expense->where(function ($query) use ($search) {
                $query->where('expense', 'like', '%' . $search . '%')
                    ->orWhere('period', 'like', '%' . $search . '%')
                    ->orWhere('date_paid', 'like', '%' . $search . '%')
                    ->orWhere('total_payment', 'like', '%' . $search . '%')
                    ->orWhere('employee', 'like', '%' . $search . '%');
            });
        }
        $expenseData = $expense->paginate(10);

        return response()->json($expenseData, 200);
    }
}
