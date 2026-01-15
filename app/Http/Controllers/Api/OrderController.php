<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use App\ApiFilter\OrderFilterQuery;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderCollection;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $filter = new OrderFilterQuery();
        // $filterItems = $filter->transform($request);//column-operator-value
        // $Order = Order::where($filterItems);
        // return new OrderCollection( $Order->paginate()->appends($request->query()));
        
         return response()->json(Order::latest()->get());
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
    public function store(StoreOrderRequest $request)
    {
        return new OrderResource(Order::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $Order)

    { 
        //  return response()->json($Order);
        return new OrderResource($Order);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $Order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $Order)
    {
        $Order->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $Order)
    {
        $Order->delete();
        return response()->json(['message' => 'order deleted successfully']);

        //
    }
}
