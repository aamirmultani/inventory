<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return response()->json($permissions);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:permissions,name'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user = Auth::user();
        $roleName = $user->roles()->pluck('name')->first(); // Fetch the first role name

        if($roleName === "Customer" || $roleName === "Seller") {
            return response()->json(['message' => 'You are not authorized to perform action'], 401);
        }

        $permission = Permission::create($request->only('name'));
        return response()->json($permission, 201);
    }

    public function show($id)
    {
        $user = Auth::user();
        $roleName = $user->roles()->pluck('name')->first(); // Fetch the first role name

        if($roleName === "Customer" || $roleName === "Seller") {
            return response()->json(['message' => 'You are not authorized to perform action'], 401);
        }
        $permission = Permission::find($id);
        if (!$permission) {
            return response()->json(['message' => 'Permission not found'], 404);
        }
        return response()->json($permission);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:permissions,name,' . $id
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user = Auth::user();
        $roleName = $user->roles()->pluck('name')->first(); // Fetch the first role name

        if($roleName === "Customer" || $roleName === "Seller") {
            return response()->json(['message' => 'You are not authorized to perform action'], 401);
        }

        $permission = Permission::find($id);
        if (!$permission) {
            return response()->json(['message' => 'Permission not found'], 404);
        }
        $permission->update($request->only('name'));
        return response()->json($permission);
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $roleName = $user->roles()->pluck('name')->first(); // Fetch the first role name

        if($roleName === "Customer" || $roleName === "Seller") {
            return response()->json(['message' => 'You are not authorized to perform action'], 401);
        }
        
        $permission = Permission::find($id);
        if (!$permission) {
            return response()->json(['message' => 'Permission not found'], 404);
        }
        $permission->delete();
        return response()->json(['message' => 'Permission deleted']);
    }
}
