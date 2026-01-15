<?php

namespace App\Http\Controllers\Api;

use App\ApiFilter\CustomerFilterQuery;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\CustomerCollection;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new CustomerFilterQuery();
        $filterItems = $filter->transform($request);  // column-operator-value
        $customer = Customer::with('order', 'review')->where($filterItems);
        return new CustomerCollection($customer->paginate()->appends($request->query()));
        //
    }

    // Customer Signup (already done earlier)

    // ✅ Customer Login
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'restaurant_id' => 'required|exists:restaurants,id',
        ]);

        // Find customer by username & restaurant
        $customer = Customer::where('username', $request->username)
            ->where('restaurant_id', $request->restaurant_id)
            ->first();

        if (!$customer || !Hash::check($request->password, $customer->password)) {
            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);
        }

        // ✅ Create token
        $token = $customer->createToken('customer_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => new CustomerResource($customer),
            'token' => $token,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        $validated = $request->validated();

        // Hash password before saving
        $validated['password'] = Hash::make($validated['password']);

        $customer = Customer::create($validated);

        // Create token
        $token = $customer->createToken('customer-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => new CustomerResource($customer),
            'token' => $token,
            'token_type' => 'Bearer'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        return new CustomerResource($customer);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        //
    }

    public function logout(Request $request)
    {
        try {
            $customer = Auth::guard('customer')->user();  
            if ($customer) {
                $customer->tokens()->delete(); 
                return response()->json(['message' => 'Successfully logged out'], 200);
            }

            return response()->json(['message' => 'No customer logged in'], 401);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error logging out',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getCustomer()
    {
        $customer = Auth::guard('customer')->user();
        // return response()->json($user);
        return new CustomerResource($customer);
    }


      public function searchEmail(Request $request)
    {
        $email = $request->input('email');
        $user = Customer::where('email', $email)->first();

        if ($user) {
            return response()->json(['message' => 'Email found', 'user' => $user], 200);
        } else {
            return response()->json(['message' => 'Email not found'], 404);
        }
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::broker('customers')->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => __($status)], 200)
            : response()->json(['message' => __($status)], 400);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::broker('customers')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (Customer $customer, string $password) {
                $customer->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => __($status)], 200)
            : response()->json(['message' => __($status)], 400);
    }
}
