<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return response()->json($roles);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:roles,name'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user = Auth::user();
        $roleName = $user->roles()->pluck('name')->first(); // Fetch the first role name

        if($roleName === "Customer" || $roleName === "Seller") {
            return response()->json(['message' => 'You are not authorized to perform action'], 401);
        }

        $role = Role::create($request->only('name'));
        return response()->json($role, 201);
    }

    public function show($id)
    {
        $user = Auth::user();
        $roleName = $user->roles()->pluck('name')->first(); // Fetch the first role name

        if($roleName === "Customer" || $roleName === "Seller") {
            return response()->json(['message' => 'You are not authorized to perform action'], 401);
        }

        $role = Role::find($id);
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }
        return response()->json($role);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:roles,name,' . $id
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user = Auth::user();
        $roleName = $user->roles()->pluck('name')->first(); // Fetch the first role name

        if($roleName === "Customer" || $roleName === "Seller") {
            return response()->json(['message' => 'You are not authorized to perform action'], 401);
        }
        
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }
        $role->update($request->only('name'));
        return response()->json($role);
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $roleName = $user->roles()->pluck('name')->first(); // Fetch the first role name

        if($roleName === "Customer" || $roleName === "Seller") {
            return response()->json(['message' => 'You are not authorized to perform action'], 401);
        }
        
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }
        $role->delete();
        return response()->json(['message' => 'Role deleted']);
    }
}
