<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // Fetch category-wise product reports
    public function categoryWiseReports()
    {
        // Fetch all categories with their respective products
        $categories = Category::with('products:id,name,price,category_id,quantity')->get(['id', 'name']);

        // Check if categories exist
        if($categories->isEmpty()){
            return response()->json(['message' => 'No category data found'], 404);
        }

        // Return the categories with products
        return response()->json(['status' => 'success', 'data' => $categories], 200);
    }

    public function fetchPaymentReports(Request $request)
{
    // Validate input query parameters
    $request->validate([
        'payment_method' => 'string|in:cod,online',  // Assuming valid methods are COD, CreditCard, and PayPal
        'year' => 'integer|min:2000|max:' . date('Y'),  // Valid year range
        'payment_status' => 'string|in:pending,paid'  // Assuming valid statuses are pending and completed
    ]);

    // Get filters from the request
    $paymentMethod = $request->payment_method;
    $year = $request->year;
    $paymentStatus = $request->payment_status;

    // Build the query
    $query = Order::where('user_id', auth()->id())
        ->with(['user', 'address', 'product', 'category']);

    // Apply payment method filter if provided
    if ($paymentMethod) {
        $query->where('payment_method', $paymentMethod);
    }

    // Apply year filter if provided
    if ($year) {
        $query->whereYear('created_at', $year);
    }

    // Apply payment status filter if provided
    if ($paymentStatus) {
        $query->where('payment_status', $paymentStatus);
    }

    // Execute the query
    $orders = $query->get();

    // Check if the result is empty
    if ($orders->isEmpty()) {
        return response()->json([
            'status' => 'error',
            'message' => 'No payment reports found'
        ], 404);
    }

    // Return the result
    return response()->json([
        'status' => 'success',
        'data' => $orders
    ], 200);
}
}
