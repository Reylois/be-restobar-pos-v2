<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;

class ExpensesController extends Controller
{
     public function index(Request $request) {
        try {

           $pageSize = $request->query('pageSize', 10); 

           $expenses = Expense::orderBy('created_at', 'desc')
                                ->paginate($pageSize);

           return response()->json($expenses);

        } catch (\Exception $e) {
            \Log::error('Error fetching expenses: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error fetching expenses',
                'error' => $e->getMessage()
            ]);
        }
    }

     public function store(Request $request) {
        try {
            $validated = $request->validate([
                'description' => 'required|string',
                'total_amount' => 'required|numeric|min:1',
                'date' => 'required|string',
                'time' => 'required|string'
            ]);

            $newExpense = Expense::create([
                'description' => $validated['description'],
                'total_amount' => $validated['total_amount'],
                'created_at' => "{$validated['date']} {$validated['time']}"
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Added expense successfully',
                'expense' => $newExpense
            ]);
        } catch (\Exception $e) {
            \Log::error('Error adding expense: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error fetching expenses'
            ]);
        }
    }
}
