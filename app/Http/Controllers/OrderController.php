<?php

namespace App\Http\Controllers;

use App\Mail\SellerOrderPlacedMail;
use App\Mail\UserOrderPlacedMail;
use App\Models\Order;
use App\Models\Product;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


class OrderController extends Controller
{
    // Get all orders for the authenticated user
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())->with(['user', 'address', 'product', 'category'])->get();
        return response()->json($orders);
    }

    // Place a new order
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address_id' => 'required|exists:user_address,id',
            'product_id' => 'required|exists:product,id',
            'total_amount' => 'required|numeric',
            'payment_method' => 'required|in:cod,online', // Validate payment method
            'qty' => 'required|numeric'
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user = Auth::user();
        $roleName = $user->roles()->pluck('name')->first(); // Fetch the first role name

        if($roleName === "Seller") {
            return response()->json(['message' => 'You are not authorized to create order'], 401);
        }

        $order = Order::create([
            'user_id' => auth()->id(),
            'address_id' => $request->address_id,
            'product_id' => $request->product_id,
            'qty' => $request->qty,
            'total_amount' => $request->total_amount * $request->qty,
            'payment_status' => 'pending',  // Default payment status is 'pending'
            'payment_method' => $request->payment_method,
        ]);

        // Send email to the user
        //Mail::to($order->user->email)->send(new UserOrderPlacedMail($order));

        // Send email to the seller (assuming seller's email is stored in the product or a related entity)
        $Role = Role::where('name', 'seller')->first();
        $sellerEmail = RoleUser::where('role_id',$Role->id)->first()->user->email;
        //Mail::to($sellerEmail)->send(new SellerOrderPlacedMail($order)); 
        // mail trap connection geting that why commented mail sending code 
        
        return response()->json(['message' => 'Order placed successfully', 'order' => $order], 201);
    }

    // Show a specific order
    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $orderDetails = $order->load(['user', 'address', 'product', 'category']);
        return response()->json($orderDetails);
    }

    // Update an existing order (could be used to update payment status)
    public function update(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'payment_status' => 'sometimes|required|in:pending,paid',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
        $user = Auth::user();
        $roleName = $user->roles()->pluck('name')->first(); // Fetch the first role name

        if($roleName === "Seller") {
            return response()->json(['message' => 'You are not authorized to update order'], 401);
        }

        $order->update($request->all());
        $prodcutId = $order->product_id;
        $product = Product::find($prodcutId);
        $product->update(['quantity' => $product->quantity - $order->qty]);
        return response()->json(['message' => 'Order updated successfully', 'order' => $order]);
    }

    // Delete an order
    public function destroy(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $user = Auth::user();
        $roleName = $user->roles()->pluck('name')->first(); // Fetch the first role name

        if($roleName === "Seller") {
            return response()->json(['message' => 'You are not authorized to delete order'], 401);
        }
        $order->delete();
        return response()->json(['message' => 'Order deleted successfully']);
    }
}

