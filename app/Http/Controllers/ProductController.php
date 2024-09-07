<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        if($products) {
            return response()->json(['products' => $products], 201);
        }
        else {
            return response()->json(['message' => 'Product created failed'], 400);
        }
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0.01',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:category,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

       $product =  Product::create($request->all());

        if($product) {
            return response()->json(['message' => 'Product created successfully'], 201);
        }
        else {
            return response()->json(['message' => 'Product created failed'], 400);
        }
    }

    public function show(Product $product)
    {
        $product = $product->load(['category:id,name']); 
        if($product) {
            return response()->json(['product' => $product], 201);
        }
        else {
            return response()->json(['message' => 'Product created failed'], 400);
        }
    }

    public function edit(Product $product)
    {
    }

    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0.01',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:category,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $product->update($request->all());

        if($product) {
            return response()->json(['message' => 'Product updated successfully'], 201);
        }
        else {
            return response()->json(['message' => 'Product update failed'], 400);
        }
    }

    public function destroy(Product $product)
    {
        $product->delete();

        if($product) {
            return response()->json(['message' => 'Product deleted successfully'], 201);
        }
        else {
            return response()->json(['message' => 'Product delete failed'], 400);
        }
    }
}
