<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMenuItemVariationRequest;
use App\Http\Requests\UpdateMenuItemVariationRequest;
use App\Models\MenuItemVariation;
use Illuminate\Http\Request;
use App\ApiFilter\MenuItemVariationFilterQuery;
use App\Http\Resources\MenuItemVariationResource;
use App\Http\Resources\MenuItemVariationCollection;

class MenuItemVariationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new MenuItemVariationFilterQuery();
        $filterItems = $filter->transform($request); //column-operator-value
        $MenuItemVariation = MenuItemVariation::where($filterItems);
        return new MenuItemVariationCollection($MenuItemVariation->paginate()->appends($request->query()));
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
    public function store(StoreMenuItemVariationRequest $request)
    {
        return new MenuItemVariationResource(MenuItemVariation::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(MenuItemVariation $MenuItemVariation)

    {
        return new MenuItemVariationResource($MenuItemVariation);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MenuItemVariation $MenuItemVariation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMenuItemVariationRequest $request, MenuItemVariation $MenuItemVariation)
    {
        $MenuItemVariation->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MenuItemVariation $MenuItemVariation)
    {
        $MenuItemVariation->delete();
        return response()->json(['message' => 'Variation deleted successfully']);
    }
    
      public function delete(MenuItemVariation $MenuItemVariation)
    {
        $MenuItemVariation->delete();
    }
}
