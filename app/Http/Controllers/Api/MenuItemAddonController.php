<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMenuItemAddonRequest;
use App\Http\Requests\UpdateMenuItemAddonRequest;
use App\Models\MenuItemAddon;
use Illuminate\Http\Request;
use App\ApiFilter\MenuItemAddonFilterQuery;
use App\Http\Resources\MenuItemAddonResource;
use App\Http\Resources\MenuItemAddonCollection;

class MenuItemAddonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new MenuItemAddonFilterQuery();
        $filterItems = $filter->transform($request);//column-operator-value
        $MenuItemAddon = MenuItemAddon::where($filterItems);
        return new MenuItemAddonCollection($MenuItemAddon->paginate()->appends($request->query()));
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
    public function store(StoreMenuItemAddonRequest $request)
    {
        return new MenuItemAddonResource(MenuItemAddon::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(MenuItemAddon $MenuItemAddon)

    { 
        return new MenuItemAddonResource($MenuItemAddon);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MenuItemAddon $MenuItemAddon)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMenuItemAddonRequest $request, MenuItemAddon $MenuItemAddon)
    {
        $MenuItemAddon->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MenuItemAddon $MenuItemAddon)
    {
        $MenuItemAddon->delete();
        //
    }

    public function delete(MenuItemAddon $MenuItemAddon)
    {
        $MenuItemAddon->delete();
    }
}
