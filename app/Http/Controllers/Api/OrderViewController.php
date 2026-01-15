<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderViewRequest;
use App\Http\Resources\OrderViewResource;
use App\Models\Order;
use App\Models\OrderView;
use Illuminate\Http\Request;

class OrderViewController extends Controller
{

    public function recordView(Request $request)
    {
        $user = $request->user();
        $restaurant = $user->restaurant;

        // Check plan status
        if ($user->plan_status !== 'active') {
            return response()->json([
                'message' => 'Your plan is inactive or expired. Please renew your subscription.'
            ], 403);
        }

        // 🧮 Define plan-based limits
        $planLimits = [
            'starter' => 400,
            'essential' => 1100,
            'pro' => 2500,
            'proPlus' => 5000,
        ];

        // ✅ Get limit based on user's plan name
        $limit = $planLimits[$user->plan] ?? 400; // default to Starter if undefined

        // 🧾 Count restaurant’s orders for the current month
        $monthlyOrderCount = Order::where('restaurant_id', $restaurant->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // 🚫 If limit reached
        if ($monthlyOrderCount >= $limit) {
            return response()->json([
                'message' => "You have reached your monthly order limit ({$limit} orders) for your {$user->plan} plan.",
                'limit' => $limit,
                'used' => $monthlyOrderCount
            ], 403);
        }

        // ✅ Optionally record view count
        $view = OrderView::firstOrCreate(
            ['restaurant_id' => $restaurant->id, 'user_id' => $user->id],
            ['count' => 0]
        );
        $view->increment('count');

        return response()->json([
            'message' => 'Order view recorded successfully.',
            'used_orders' => $monthlyOrderCount,
            'limit' => $limit,
            'remaining' => $limit - $monthlyOrderCount,
        ]);
    }

    // Optional endpoint for dashboard usage tracking
    public function getUsage(Request $request)
    {
        $user = $request->user();
        $restaurant = $user->restaurant;

        $planLimits = [
            'Starter' => 400,
            'Essential' => 1100,
            'Pro' => 2500,
            'Pro Plus' => 5000,
        ];

        $limit = $planLimits[$user->plan] ?? 400;

        $monthlyOrderCount = Order::where('restaurant_id', $restaurant->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return response()->json([
            'limit' => $limit,
            'used' => $monthlyOrderCount,
            'remaining' => max(0, $limit - $monthlyOrderCount),
            'plan' => $user->plan,
        ]);
    }

    public function store(StoreOrderViewRequest $request)
    {
        return new OrderViewResource(OrderView::create($request->all()));
    }
}
