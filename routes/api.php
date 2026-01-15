<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\CustomerFieldController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\MenuItemAddonController;
use App\Http\Controllers\Api\MenuItemController;
use App\Http\Controllers\Api\MenuItemVariationController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderSettingController;
use App\Http\Controllers\Api\OrderViewController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RestaurantController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WaiterController;

use Illuminate\Support\Facades\Storage;

Route::get('/s3-health', function () {
    try {
        $path = 'health-check.txt';

        Storage::disk('s3')->put($path, 'S3 OK', 'public');

        $url = Storage::disk('s3')->url($path);

        return response()->json([
            'status' => 'success',
            'message' => 'AWS S3 is working',
            'url' => $url
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'AWS S3 failed',
            'error' => $e->getMessage()
        ], 500);
    }
});

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/contact', [ContactController::class, 'send']);
Route::post('/orders/views', [OrderViewController::class, 'store']);
Route::get('/restaurants/{id}/order-stats', [RestaurantController::class, 'getOrderStats']);
Route::post('/searchEmail', [UserController::class, 'searchEmail']);
Route::post('/forgot-password', [UserController::class, 'sendResetLink']);
Route::post('/reset-password', [UserController::class, 'reset']);
Route::post('/customer/searchEmail', [CustomerController::class, 'searchEmail']);
Route::post('/customer/forgot-password', [CustomerController::class, 'sendResetLink']);
Route::post('/customer/reset-password', [CustomerController::class, 'reset']);
Route::apiResource('orders', OrderController::class);
Route::apiResource('customers', CustomerController::class);
Route::post('/customers/login', [CustomerController::class, 'login']);
Route::post('/waiter/login', [WaiterController::class, 'login']);
Route::apiResource('addons', MenuItemAddonController::class);
Route::apiResource('menuitems', MenuItemController::class);
Route::patch('/updateItem/{MenuItem}', [MenuItemController::class, 'updateItem']);
// Route::apiResource('products', ProductController::class);
Route::post('/menu/bulk', [MenuController::class, 'bulkCreate']);
Route::apiResource('menus', MenuController::class);
Route::apiResource('customerfeilds', CustomerFieldController::class);
Route::apiResource('restaurants', RestaurantController::class);
Route::apiResource('reviews', ReviewController::class);
Route::post('/item/bulk', [MenuItemController::class, 'bulkstore']);
Route::post('/user/validate', [UserController::class, 'userValidate']);
Route::post('/restaurant/validate',  [RestaurantController::class, 'restaurantValidate']);
Route::post('/restaurant/register',  [RestaurantController::class, 'register']);

Route::apiResource('ordersettings', OrderSettingController::class);
Route::get('/restaurants/name/{name}', [RestaurantController::class, 'getByName']);
Route::put('/waiters/bulk-update-status', [WaiterController::class, 'bulkUpdateStatus']);
Route::apiResource('waiters', WaiterController::class);

Route::delete('/variation/{MenuItemVariation}', [MenuItemVariationController::class, 'delete']);
Route::delete('/addon/{MenuItemAddon}', [MenuItemAddonController::class, 'delete']);

// Route::get('/product/{MenuItem}', [ProductController::class, 'Show']);
Route::delete('/product/{MenuItem}', [ProductController::class, 'destroy']);
// Route::put('/product/{MenuItem}', [ProductController::class, 'Update']);
Route::patch('/product/{MenuItem}', [ProductController::class, 'update']);
Route::patch('/ordersetting/{OrderSetting}', [OrderSettingController::class, 'updates']);
Route::post('/ordersetting/add', [OrderSettingController::class, 'add']);


Route::middleware('auth:web')->group(function () {
    Route::apiResource('variations', MenuItemVariationController::class);
    Route::post('/logout', [UserController::class, 'logout']);
   Route::apiResource('users', UserController::class);
    Route::get('/user', [UserController::class, 'getUser']);
    Route::post('/orders/view', [OrderViewController::class, 'recordView']);
    Route::get('/orders/usage', [OrderViewController::class, 'getUsage']);
    Route::patch('/restaurants/{restaurant}', [RestaurantController::class, 'update']);

});
Route::post('/createItem', [MenuItemController::class, 'createItem']);

Route::middleware('auth:customer')->group(function () {
    Route::post('/customer/logout', [CustomerController::class, 'logout']);
    Route::get('/customer', [CustomerController::class, 'getCustomer']);
});

Route::middleware('auth:waiter')->group(function () {
    Route::post('/waiter/logout', [WaiterController::class, 'logout']);
    Route::get('/waiter', [WaiterController::class, 'getWaiter']);
});