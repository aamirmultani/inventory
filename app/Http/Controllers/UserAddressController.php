<?php
namespace App\Http\Controllers;

use App\Models\UserAddress;
use Illuminate\Http\Request;

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
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'country' => 'required|string',
            'zip_code' => 'required|string',
            'phone_number' => 'required|string',
        ]);

        $userAddress = UserAddress::create($validatedData);

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

        $validatedData = $request->validate([
            'address' => 'sometimes|required|string',
            'city' => 'sometimes|required|string',
            'state' => 'sometimes|required|string',
            'country' => 'sometimes|required|string',
            'zip_code' => 'sometimes|required|string',
            'phone_number' => 'sometimes|required|string',
        ]);

        $userAddress->update($validatedData);

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
