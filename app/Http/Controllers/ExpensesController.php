<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;

class ExpensesController extends Controller
{
     public function index(Request $request) {
        try {
           $pageSize = $request->query('per_page', 10);
           $page = $request->query('page', 1);
           $startDate = $request->query('start_date');
           $endDate = $request->query('end_date');

           $query = Expense::orderBy('created_at', 'desc');
           
           // Apply date range filter if both start_date and end_date are provided
           if ($startDate && $endDate) {
               // Add time to make the range inclusive
               $startDateTime = $startDate . ' 00:00:00';
               $endDateTime = $endDate . ' 23:59:59';
               
               $query->whereBetween('created_at', [$startDateTime, $endDateTime]);
           }

           $expenses = $query->paginate($pageSize);

           return response()->json($expenses);

        } catch (\Exception $e) {
            \Log::error('Error fetching expenses: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error fetching expenses',
                'error' => $e->getMessage()
            ], 500);
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
                'message' => 'Error adding expense',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
