<?php

namespace App\Http\Controllers\Api;

use App\ApiFilter\RestaurantFilterQuery;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRestaurantRequest;
use App\Http\Requests\UpdateRestaurantRequest;
use App\Http\Resources\RestaurantCollection;
use App\Http\Resources\RestaurantResource;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new RestaurantFilterQuery();
        $filterItems = $filter->transform($request);  // column-operator-value
        $Restaurant = Restaurant::with('order', 'review', 'waiter', 'menu', 'customer', 'ordersetting')->where($filterItems);
        return new RestaurantCollection($Restaurant->paginate()->appends($request->query()));
        //
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
    public function store(StoreRestaurantRequest $request)
    // public function store(Request $request)
    {
        if ($request->logo) {
    $image = $request->logo;
    $imageName = Str::random(20) . '.png';
    $path = storage_path('app/public/logos/' . $imageName);
    file_put_contents($path, base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image)));
    $data['logo'] = 'logos/' . $imageName;
}
        // return new RestaurantResource(Restaurant::create($request->all()));
        return $request;
    }

    /**
     * Display the specified resource.
     */
    // public function show(Restaurant $Restaurant)
    // {
    //     return new RestaurantResource($Restaurant);
    // }
    public function show($id)
    {
        $restaurant = Restaurant::find($id);
        if (!$restaurant) {
            return response()->json(['error' => 'Restaurant not found'], 404);
        }
        return new RestaurantResource($restaurant);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Restaurant $Restaurant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRestaurantRequest $request, Restaurant $restaurant)
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $filename = Str::random(20) . '.' . $logo->getClientOriginalExtension();
            $path = $logo->storeAs('logos', $filename, 's3');
            $data['logo'] = Storage::disk('s3')->url($path);
        }

        if ($request->hasFile('banner')) {
            $banner = $request->file('banner');
            $filename = Str::random(20) . '.' . $banner->getClientOriginalExtension();
            $path = $banner->storeAs('banners', $filename, 's3');
            $data['banner'] = Storage::disk('s3')->url($path);
        }

        $restaurant->update($data);

        return response()->json([
            'message' => 'Restaurant updated successfully.',
            'restaurant' => $restaurant,
            'logo_url' => $restaurant->logo ? asset($restaurant->logo) : null,
            'banner_url' => $restaurant->banner ? asset($restaurant->banner) : null,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Restaurant $Restaurant)
    {
        $Restaurant->delete();
        //
    }

    public function restaurantValidate(Request $request)
{
    // Validate all the restaurant fields
    $data = $request->validate([
        'restaurantName' => ['required', 'string', 'max:255'],
        'isBrandingVisible' => ['required', 'boolean'],
        'secondaryColor' => ['required', 'string'],
        'primaryColor' => ['required', 'string'],
        'currency' => ['required', 'string'],
        'user_id' => ['required', 'integer'],
        'restaurantDescription' => ['nullable', 'string'],
        'restaurantPhone' => ['required', 'string'],
        'restaurantEmail' => ['required', 'email', 'unique:restaurants,restaurantEmail'],

        // Image validation — allow either File upload or base64 string
        'logo' => ['nullable'],
        'banner' => ['nullable'],
    ]);

    // Manually check for valid image or base64 formats (without saving)
    if ($request->hasFile('logo')) {
        $file = $request->file('logo');
        if (!$file->isValid() || !str_starts_with($file->getMimeType(), 'image/')) {
            return response()->json(['errors' => ['logo' => ['The logo must be an image.']]], 422);
        }
    } elseif ($request->logo && !preg_match('/^data:image\/(\w+);base64,/', $request->logo)) {
        return response()->json(['errors' => ['logo' => ['The logo must be a valid base64 image.']]], 422);
    }

    if ($request->hasFile('banner')) {
        $file = $request->file('banner');
        if (!$file->isValid() || !str_starts_with($file->getMimeType(), 'image/')) {
            return response()->json(['errors' => ['banner' => ['The banner must be an image.']]], 422);
        }
    } elseif ($request->banner && !preg_match('/^data:image\/(\w+);base64,/', $request->banner)) {
        return response()->json(['errors' => ['banner' => ['The banner must be a valid base64 image.']]], 422);
    }

    // Return validated data (no file saving)
    return response()->json([
        'message' => 'Validation successful',
        'validated' => $data,
    ]);
}


    public function register(Request $request)
{
    $fields = $request->validate([
        'restaurantName' => ['required', 'string', 'max:255'],
        'isBrandingVisible' => ['required', 'boolean'],
        'secondaryColor' => ['required', 'string'],
        'primaryColor' => ['required', 'string'],
        'currency' => ['required', 'string'],
        'orderStatus' => ['required', 'string'],
        'user_id' => ['required', 'integer'],
        'restaurantDescription' => ['nullable', 'string'],
        'restaurantPhone' => ['required', 'string'],
        'restaurantEmail' => ['required', 'email', 'unique:restaurants,restaurantEmail'],
        // Remove `image` validation since we'll check it manually
        'logo' => ['nullable'],
        'banner' => ['nullable'],
    ]);


        $data = $fields;

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $filename = Str::random(20) . '.' . $logo->getClientOriginalExtension();
            $path = $logo->storeAs('logos', $filename, 's3');
            $data['logo'] = $path;
        } elseif ($request->logo && preg_match('/^data:image\/(\w+);base64,/', $request->logo)) {
            $image = $request->logo;
            $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            $filename = Str::random(20) . '.' . $extension;
            $path = 'logos/' . $filename;
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image));
            Storage::disk('s3')->put($path, $imageData, 'public');
            $data['logo'] = $path;
        }

        if ($request->hasFile('banner')) {
            $banner = $request->file('banner');
            $filename = Str::random(20) . '.' . $banner->getClientOriginalExtension();
            $path = $banner->storeAs('banners', $filename, 's3');
            $data['banner'] = $path;
        } elseif ($request->banner && preg_match('/^data:image\/(\w+);base64,/', $request->banner)) {
            $image = $request->banner;
            $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            $filename = Str::random(20) . '.' . $extension;
            $path = 'banners/' . $filename;
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image));
            Storage::disk('s3')->put($path, $imageData, 'public');
            $data['banner'] = $path;
        }


    $restaurant = Restaurant::create($data);

    return response()->json([
        'message' => 'Restaurant registered successfully!',
        'restaurant' => $restaurant,
        'logo_url' => $restaurant->logo ? asset($restaurant->logo) : null,
        'banner_url' => $restaurant->banner ? asset($restaurant->banner) : null,
    ]);
}


    public function getByName($name)
    {
        $restaurant = Restaurant::with(['ordersetting', 'menu', 'review', 'customer'])
            ->where('restaurantName', $name)
            ->first();

        if (!$restaurant) {
            return response()->json([
                'success' => false,
                'message' => 'Restaurant not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new RestaurantResource($restaurant)
        ], 200);
    }

   public function getOrderStats($restaurantId)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);

        $owner = $restaurant->user;

        if (!$owner) {
            return response()->json([
                'message' => 'No owner user found for this restaurant.'
            ], 404);
        }

        $plan = $owner->plan ?? 'Starter';
        $planStatus = $owner->plan_status ?? 'inactive';

        if ($planStatus !== 'active') {
            return response()->json([
                'message' => 'This restaurant’s plan is inactive. Please renew or upgrade.',
                'plan' => $plan,
                'status' => $planStatus,
            ]);
        }

        $planLimits = [
            'starter' => 400,
            'essential' => 1100,
            'pro' => 2500,
            'proPlus' => 5000,
        ];

        $limit = $planLimits[$plan] ?? 0;

        $monthlyOrders = Order::where('restaurant_id', $restaurant->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year);

        $statusCounts = [
            'pending' => (clone $monthlyOrders)->where('status', 'pending')->count(),
            'completed' => (clone $monthlyOrders)->where('status', 'completed')->count(),
            'cancelled' => (clone $monthlyOrders)->where('status', 'cancelled')->count(),
            'total' => $monthlyOrders->count(),
        ];

        $limitReached = $statusCounts['total'] >= $limit;

        return response()->json([
            'restaurant' => $restaurant->restaurantName,
            'plan' => $plan,
            'plan_status' => $planStatus,
            'limit' => $limit,
            'orders' => $statusCounts,
            'limit_reached' => $limitReached,
            'remaining' => max(0, $limit - $statusCounts['total']),
        ]);
    }

}
