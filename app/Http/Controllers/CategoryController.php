<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Role;
use App\Models\RoleUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        if($categories) {
            return response()->json(['categories' => $categories], 201);
        }
        else {
            return response()->json(['message' => 'Category created failed'], 400);
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
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user = Auth::user();
        $roleName = $user->roles()->pluck('name')->first(); // Fetch the first role name

        if($roleName === "Customer") {
            return response()->json(['message' => 'You are not authorized to create category'], 401);
        }
        $category = Category::create($request->all());
        if($category) {
            return response()->json(['message' => 'Category created successfully'], 201);
        }
        else {
            return response()->json(['message' => 'Category created failed'], 400);
        }
    }

    public function show(Category $category)
    {
        $user = Auth::user();
        $roleName = $user->roles()->pluck('name')->first(); // Fetch the first role name

        if($roleName === "Customer") {
            return response()->json(['message' => 'You are not authorized to view category'], 401);
        }
        $category = Category::find($category);
        if($category) {
            return response()->json(['category' => $category], 201);
        }
        else {
            return response()->json(['message' => 'Category created failed'], 400);
        }
    }

    public function edit(Category $category)
    {
    }

    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user = Auth::user();
        $roleName = $user->roles()->pluck('name')->first(); // Fetch the first role name

        if($roleName === "Customer") {
            return response()->json(['message' => 'You are not authorized to update category'], 401);
        }

        $category->update($request->all());

        if($category) {
            return response()->json(['message' => 'Category updated successfully'], 201);
        }
        else {
            return response()->json(['message' => 'Category update failed'], 400);
        }
    }

    public function destroy(Category $category)
    {
        $user = Auth::user();
        $roleName = $user->roles()->pluck('name')->first(); // Fetch the first role name

        if($roleName === "Customer") {
            return response()->json(['message' => 'You are not authorized to delete category'], 401);
        }

        $category->delete();
        if($category) {
            return response()->json(['message' => 'Category deleted successfully'], 201);
        }
        else {
            return response()->json(['message' => 'Category delete failed'], 400);
        }
    }
}
