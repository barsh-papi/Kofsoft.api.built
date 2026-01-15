<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWaiterRequest;
use App\Http\Requests\UpdateWaiterRequest;
use App\Http\Resources\WaiterResource;
use App\Models\Waiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class WaiterController extends Controller
{
    public function index()
    {
        return WaiterResource::collection(Waiter::with('restaurant', 'orders')->paginate(10));
    }

    public function store(StoreWaiterRequest $request)
    {
        $data = $request->validated();

        $data['password'] = Hash::make($data['password']);

if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = Str::random(20) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('waiters', $filename, 's3');
            $data['image'] = Storage::disk('s3')->url($path);
        }
   

        $waiter = Waiter::create($data);

        return new WaiterResource($waiter->load('restaurant'));
    }

    public function show(Waiter $waiter)
    {
        return new WaiterResource($waiter->load('restaurant', 'orders'));
    }

    public function update(UpdateWaiterRequest $request, Waiter $waiter)
    {
        $data = $request->validated();

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $waiter->update($data);

        return new WaiterResource($waiter->load('restaurant', 'orders'));
    }

    public function destroy(Waiter $waiter)
    {
        $waiter->delete();

        return response()->json(['message' => 'Waiter deleted successfully']);
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'restaurant_id' => 'required|exists:restaurants,id',
        ]);

        $waiter = Waiter::where('username', $request->username)
            ->where('restaurant_id', $request->restaurant_id)
            ->first();

        if (!$waiter || !Hash::check($request->password, $waiter->password)) {
            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);
        }


        if ($waiter->loginStatus === 'inactive') {
            return response()->json([
                'message' => 'Your account is inactive. Please contact your administrator.',
            ], 403);
        }

        $token = $waiter->createToken('waiter_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $waiter,
            'token' => $token,
        ]);
    }


    public function logout(Request $request)
    {
        try {
            $waiter = Auth::guard('waiter')->user();  // or auth()->user() if using default
            if ($waiter) {
                $waiter->tokens()->delete();  // Revoke all tokens
                return response()->json(['message' => 'Successfully logged out'], 200);
            }

            return response()->json(['message' => 'No waiter logged in'], 401);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error logging out',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getWaiter(Request $request)
    {
        $waiter = Auth::guard('waiter')->user();

        if (!$waiter) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($waiter->loginStatus === 'inactive') {
            return response()->json(['message' => 'Account inactive'], 403);
        }

        if ($request->has('restaurant_id') && $waiter->restaurant_id != $request->restaurant_id) {
            return response()->json(['message' => 'Invalid restaurant access'], 403);
        }

        return new WaiterResource($waiter);
    }



    public function bulkUpdateStatus(Request $request)
    {
        $data = $request->validate([
            'waiters' => 'required|array',
            'waiters.*.id' => 'required|integer|exists:waiters,id',
            'waiters.*.loginStatus' => 'required|in:active,inactive',
        ]);

        foreach ($data['waiters'] as $waiterData) {
            Waiter::where('id', $waiterData['id'])
                ->update(['loginStatus' => $waiterData['loginStatus']]);
        }

        return response()->json([
            'message' => 'Waiter statuses updated successfully',
        ]);
    }
}
