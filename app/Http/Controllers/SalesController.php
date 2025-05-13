<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SalesController extends Controller
{
    /**
     * Create a new sale
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createSale(Request $request)
    {
        try {
            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'order_items' => 'required|array',
                'order_items.*.product_id' => 'required|exists:products,id',
                'order_items.*.quantity' => 'required|numeric|min:1',
                'order_items.*.price' => 'required|numeric|min:0',
                'discount_percent' => 'nullable|numeric|min:0|max:100',
                'total_amount' => 'required|numeric|min:0',
                'amount_paid' => 'required|numeric|min:0',
                'payment_method' => 'required|in:cash,gcash',
                'order_type' => 'required|in:dine-in,take-out'
            ]);

            // Check validation
            if ($validator->fails()) {
                \Log::warning('Sale validation failed', [
                    'errors' => $validator->errors()->toArray(),
                    'request' => $request->all()
                ]);

                return response()->json([
                    'errors' => $validator->errors()
                ], 400);
            }

            // Start a database transaction
            return DB::transaction(function () use ($request) {
                // Calculate subtotal
                $subtotal = collect($request->order_items)
                    ->sum(fn($item) => $item['quantity'] * $item['price']);

                $discountPercent = $request->input('discount_percent', 0);

                // Create sale record
                $sale = Sale::create([
                    'subtotal' => $subtotal,
                    'discount' => $discountPercent, // Store discount as percentage
                    'total_amount' => $request->input('total_amount'),
                    'order_type' => $request->input('order_type'),
                    'payment_method' => $request->input('payment_method'),
                    'amount_paid' => $request->input('amount_paid'),
                    'created_at' => now()
                ]);

                // Process sale items
                $saleItems = [];
                foreach ($request->order_items as $item) {
                    // Fetch the product to ensure it exists and check stock if tracking is enabled
                    $product = Product::findOrFail($item['product_id']);

                    // Check stock if tracking is enabled
                    if ($product->track_stock && $product->stock < $item['quantity']) {
                        throw new \Exception("Insufficient stock for product: {$product->name}");
                    }

                    // Create sale item
                    $saleItems[] = [
                        'sale_id' => $sale->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'total' => $item['quantity'] * $item['price'],
                        'created_at' => now()
                    ];

                    // Update product stock if tracking is enabled
                    if ($product->track_stock) {
                        $product->decrement('stock', $item['quantity']);
                    }
                }

                // Bulk insert sale items
                SaleItem::insert($saleItems);

                return response()->json([
                    'message' => 'Sale created successfully',
                    'sale_id' => $sale->id,
                    'total_amount' => $sale->total_amount,
                    'change' => $request->input('change', 0)
                ], 201);
            });
        } catch (\Exception $e) {
            \Log::error('Error creating order', [
                'message' => $e->getMessage(),
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }


    /**
     * Get sales report
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSalesReport(Request $request)
    {
        // Validate input parameters
        $validator = Validator::make($request->all(), [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'order_type' => 'nullable|in:dine-in,take-out',
            'payment_method' => 'nullable|in:cash,gcash'
        ]);

        // Check validation
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        // Build query
        $query = Sale::query();

        // Apply filters
        if ($request->has('start_date')) {
            $query->whereDate('created_at', '>=', $request->input('start_date'));
        }

        if ($request->has('end_date')) {
            $query->whereDate('created_at', '<=', $request->input('end_date'));
        }

        if ($request->has('order_type')) {
            $query->where('order_type', $request->input('order_type'));
        }

        if ($request->has('payment_method')) {
            $query->where('payment_method', $request->input('payment_method'));
        }

        // Get sales data
        $sales = $query->with('saleItems.product')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Calculate summary
        $summary = [
            'total_sales' => $sales->sum('total_amount'),
            'total_discount' => $sales->sum('discount'),
            'total_count' => $sales->total()
        ];

        // Add change to each sale
        $sales->getCollection()->transform(function ($sale) {
            $sale->change = max(0, $sale->amount_paid - $sale->total_amount);
            return $sale;
        });

        return response()->json([
            'sales' => $sales,
            'summary' => $summary
        ]);
    }
}
