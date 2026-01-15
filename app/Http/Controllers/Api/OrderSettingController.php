<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\OrderSetting;
use App\Http\Requests\StoreOrderSettingRequest;
use App\Http\Requests\UpdateOrderSettingRequest;
use Illuminate\Http\Request;
use App\ApiFilter\OrderSettingFilterQuery;
use App\Http\Resources\OrderSettingResource;
use App\Http\Resources\OrderSettingCollection;

class OrderSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new OrderSettingFilterQuery();
        $filterItems = $filter->transform($request); //column-operator-value
        $OrderSetting = OrderSetting::where($filterItems);
        return new OrderSettingCollection($OrderSetting->paginate()->appends($request->query()));
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
    public function store(StoreOrderSettingRequest $request)
    {
        return new OrderSettingResource(OrderSetting::create($request->all()));
    }

    public function add(StoreOrderSettingRequest $request)
    {
        return new OrderSettingResource(OrderSetting::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(OrderSetting $OrderSetting)

    {
        return new OrderSettingResource($OrderSetting);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrderSetting $OrderSetting)
    {
        return $OrderSetting;
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updates(UpdateOrderSettingRequest $request, OrderSetting $OrderSetting)
    {
        try {
            $OrderSetting->update($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Order settings updated successfully!',
                'data' => $OrderSetting,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error updating order settings: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderSetting $OrderSetting)
    {
        $OrderSetting->delete();
        //
    }
}
