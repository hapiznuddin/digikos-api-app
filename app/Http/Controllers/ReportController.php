<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReportRentResource;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\Rent;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function getReportPayment(Request $request)
    {
        $request->validate([
            'month' => 'nullable|integer',
            'year' => 'required|integer',
        ]);

        $incomeQuery = Payment::query();
        $newIncomeQuery = Rent::query();
        $expenseQuery = Expense::query();

        if ($request->has('month') && $request->month !== null) {
            $incomeQuery->whereYear('created_at', $request->year)->whereMonth('created_at', $request->month);
            $newIncomeQuery->whereYear('updated_at', $request->year)->whereMonth('updated_at', $request->month);
            $expenseQuery->whereYear('date_paid', $request->year)->whereMonth('date_paid', $request->month);
        } else {
            $incomeQuery->whereYear('created_at', $request->year);
            $newIncomeQuery->whereYear('updated_at', $request->year);
            $expenseQuery->whereYear('date_paid', $request->year);
        }

        $reportIncome = $incomeQuery
            ->selectRaw('COUNT(*) as total_payment, SUM(price) as total_price, MONTHNAME(created_at) as month_name, YEAR(created_at) as year')
            ->whereNotIn('price', [1600000, 2100000, 2600000])
            ->groupByRaw('YEAR(created_at), MONTHNAME(created_at)')
            ->get();

        $newIncome = $newIncomeQuery ->selectRaw('COUNT(*) as total_pay, SUM(total_payment) as total_payment, MONTHNAME(updated_at) as month_name, YEAR(updated_at) as year')
            ->groupByRaw('YEAR(updated_at), MONTHNAME(updated_at)')
            ->get();

        $reportExpense = $expenseQuery
            ->selectRaw('COUNT(*) as total_expense, SUM(total_payment) as total_price, MONTHNAME(date_paid) as month_name, YEAR(date_paid) as year')
            ->groupByRaw('YEAR(date_paid), MONTHNAME(date_paid)')
            ->get();

        $expenseCategory = Expense::whereYear('date_paid', $request->year)->whereMonth('date_paid', $request->month);

        if ($request->has('month') && $request->month !== null) {
            $expenseCategory->whereYear('date_paid', $request->year)->whereMonth('date_paid', $request->month);
        } else {
            $expenseCategory->whereYear('date_paid', $request->year);
        }

        $reportExpenseType = $expenseCategory
            ->select('expense', 'total_payment')
            ->get();

        return response()->json(
            [
                'income' => $reportIncome,
                'new_income' => $newIncome,
                'expense' => $reportExpense,
                'expense_category' => $reportExpenseType,
            ],
            200
        );
    }

    public function getReportRent(Request $request)
    {
        $request->validate([
            'month' => 'nullable|integer',
            'year' => 'required|integer',
        ]);

        $rentQuery = Rent::query()->select('id', 'occupant_id', 'status_checkin', 'room_id', 'start_date', 'status_id', 'updated_at')
        ->whereYear('rents.updated_at', $request->year)
        ->when($request->has('month') && $request->month !== null, function ($query) use ($request) {
            $query->whereMonth('rents.updated_at', $request->month);
        })
        ->where('rents.status_checkin', 1)
        ->get();

        $totalCkeckin = Rent::query()->select('status_checkin')->where('status_checkin', 1)
        ->whereYear('updated_at', $request->year)
        ->when($request->has('month') && $request->month !== null, function ($query) use ($request) {
            $query->whereMonth('rents.updated_at', $request->month);
        })
        ->count();

        return response()->json([
            'total_checkin' => $totalCkeckin,
            'data' => ReportRentResource::collection($rentQuery),
        ]);
    }
}
