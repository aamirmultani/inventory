<?php
namespace App\Http\Controllers;

use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserAddressController extends Controller
{
    // Fetch all user addresses
    public function index()
    {
        $userAddresses = UserAddress::all();
        return response()->json($userAddresses);
    }

    // Store a new user address
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'country' => 'required|string',
            'zip_code' => 'required|string',
            'phone_number' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = Auth::user();
        $roleName = $user->roles()->pluck('name')->first(); // Fetch the first role name

        if($roleName === "Super Admin" || $roleName === "Seller") {
            return response()->json(['message' => 'You are not authorized to perform action'], 401);
        }

        $userAddress = UserAddress::create($request->all());

        return response()->json(['message' => 'User address created successfully', 'data' => $userAddress], 201);
    }

    // Fetch a single user address by ID
    public function show($id)
    {
        $userAddress = UserAddress::findOrFail($id);
        return response()->json($userAddress);
    }

    // Update a user address
    public function update(Request $request, $id)
    {
        $userAddress = UserAddress::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'address' => 'sometimes|required|string',
            'city' => 'sometimes|required|string',
            'state' => 'sometimes|required|string',
            'country' => 'sometimes|required|string',
            'zip_code' => 'sometimes|required|string',
            'phone_number' => 'sometimes|required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = Auth::user();
        $roleName = $user->roles()->pluck('name')->first(); // Fetch the first role name

        if($roleName === "Super Admin" || $roleName === "Seller") {
            return response()->json(['message' => 'You are not authorized to perform action'], 401);
        }

        $userAddress->update($request->all());
        
        return response()->json(['message' => 'User address updated successfully', 'data' => $userAddress], 200);
    }

    // Delete a user address
    public function destroy($id)
    {
        $userAddress = UserAddress::findOrFail($id);
        $userAddress->delete();

        return response()->json(['message' => 'User address deleted successfully'], 200);
    }
}
