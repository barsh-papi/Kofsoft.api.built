<?php

namespace App\Http\Controllers\Api;

use App\ApiFilter\UserFilterQuery;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Mail\WelcomeEmail;
use App\Models\OrderView;
use App\Models\Restaurant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\UsesTrait;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new UserFilterQuery();
        $filterItems = $filter->transform($request);
        $user = User::with('restaurant')->where($filterItems);
        return new UserCollection($user->paginate()->appends($request->query()));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /** Store a newly created resource in storage. */

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        //
    }

    public function store(StoreUserRequest $request)
    {
        return new UserResource(User::create($request->all()));
    }

    public function userValidate(Request $request)
    {
        $fields = $request->validate([
            'firstname' => ['required'],
            'lastname' => ['required'],
            'username' => ['required', 'unique:users'],
            'phone' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:8', 'confirmed']
        ]);

        return [
            'user' => $fields,
        ];
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'username' => 'required|string|unique:users,username',
            'phone' => 'nullable|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'username' => $data['username'],
            'plan' => 'starter',
            'planDuration' => 14,
            'trial_ends_at' => Carbon::now()->addDays(14),
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);


        // optionally create restaurant
        // if ($request->has('restaurant')) {
        //     $r = $request->input('restaurant');
        //     $r['user_id'] = $user->id;
        //     Restaurant::create($r);
        // }
        // Mail::to($user->email)->queue(new WelcomeEmail($user));

        Mail::to($user->email)->send(new WelcomeEmail($user));

        // $token = $user->createToken($user->username)->plainTextToken;

        return response()->json(['user' => new UserResource($user)]);
    }





    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember' => 'sometimes|boolean',
        ]);
        $user = User::where('email', $request->email)->first();

        $remember = $request->boolean('remember');

        if (! Auth::attempt(
            $request->only('email', 'password'),
            $remember
            )) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        if ($user->isTrialExpired()) {
            $user->update(['plan_status' => 'expired']);
        }

        $request->session()->regenerate();

        return response()->json([
            'user' => new UserResource($user),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out']);
    }

    /**
     * Get the authenticated user.
     */
    public function getUser(Request $request)
    {
        $user = Auth::user();
        return [
            'user' => new UserResource($user),
        ];
    }


    public function searchEmail(Request $request)
    {
        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        if ($user) {
            return response()->json(['message' => 'Email found', 'user' => $user], 200);
        } else {
            return response()->json(['message' => 'Email not found'], 404);
        }
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
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

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => __($status)], 200)
            : response()->json(['message' => __($status)], 400);
    }
}
