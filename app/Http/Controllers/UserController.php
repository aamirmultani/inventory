<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function assignRole(Request $request, $userId)
    {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required|exists:roles,id'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->roles()->sync([$request->role_id], false);
        return response()->json(['message' => 'Role assigned']);
    }

    public function assignPermission(Request $request, $roleId)
    {
        $validator = Validator::make($request->all(), [
            'permission_id' => 'required|exists:permissions,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $role = Role::find($roleId);
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }
        $role->permissions()->sync([$request->permission_id], false);
        return response()->json(['message' => 'Permission assigned']);
    }
}
