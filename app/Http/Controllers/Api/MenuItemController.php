<?php

namespace App\Http\Controllers\Api;

use App\ApiFilter\MenuItemFilterQuery;
use App\Http\Controllers\Controller;
use App\Http\Requests\BulkStoreMenuItemsRequest;
use App\Http\Requests\StoreMenuItemRequest;
use App\Http\Requests\UpdateMenuItemRequest;
use App\Http\Resources\MenuItemCollection;
use App\Http\Resources\MenuItemResource;
use App\Models\MenuItem;
use Illuminate\Support\Facades\DB;

use App\Models\MenuItemAddon;
use App\Models\MenuItemVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class MenuItemController extends Controller
{
   
    public function index(Request $request)
    {
        $filter = new MenuItemFilterQuery();
        $filterItems = $filter->transform($request);  // column-operator-value
        $MenuItem = MenuItem::with('variations', 'addons')->where($filterItems);

        return new MenuItemCollection($MenuItem->paginate()->appends($request->query()));
        //
    }

  
    public function create()
    {
        //
    }


    public function store(StoreMenuItemRequest $request)
    {
        $data = $request->except('image');

     if ($request->hasFile('image')) {
    $imagePath = $request->file('image')->store('menu_items', 's3');
    $data['image'] = $imagePath;
}


        $menuItem = MenuItem::create($data);

        return new MenuItemResource($menuItem);
    }

    /**
     * Display the specified resource.
     */
    public function show(MenuItem $MenuItem)
    {
        return new MenuItemResource($MenuItem);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MenuItem $MenuItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMenuItemRequest $request, MenuItem $MenuItem)
    {
        $MenuItem->update($request->all());
        return response()->json($request);

        // return new MenuItemResource($MenuItem);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MenuItem $MenuItem)
    {
        $MenuItem->delete();
    }

    public function bulkstore(BulkStoreMenuItemsRequest $request)
    {
        $bulk = collect($request->all())->map(function ($arr, $key) {
            return Arr::except($arr, ['']);
        });
        MenuItem::insert($bulk->toArray());
    }

    public function createItem(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'image' => 'nullable|file|image',
            'menu_id' => 'required|exists:menus,id',
            'addons' => 'nullable|array',
            'variations' => 'nullable|array',
            'variations.*.type' => 'nullable|string',
            'variations.*.value' => 'nullable|string',
            'variations.*.price' => 'nullable|numeric',
            'addons.*.name' => 'nullable|string',
            'addons.*.price' => 'nullable|numeric',
        ]);

        DB::transaction(function () use ($request) {

            $imagePath = null;

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('menu_items', 's3');
            } elseif (is_string($request->image)) {
                $imagePath = $request->image;
            }


            $menuItem = MenuItem::create([
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
                'image' => $imagePath,
                'menu_id' => $request->menu_id,
            ]);

                if (!empty($request->variations)) {
                foreach ($request->variations as $variation) {
                    MenuItemVariation::create([
                        'type' => $variation['type'] ?? null,
                        'value' => $variation['value'] ?? null,
                        'price' => $variation['price'] ?? 0,
                        'menu_item_id' => $menuItem->id,
                    ]);
                }
            }

            // Create Addons
            if (!empty($request->addons)) {
                foreach ($request->addons as $addon) {
                    MenuItemAddon::create([
                        'description' => $addon['name'] ?? null,
                        'price' => $addon['price'] ?? 0,
                        'menu_item_id' => $menuItem->id,
                    ]);
                }
            }
        });

        return response()->json(['message' => 'Menu item created successfully']);
        // return $request;
    }


    public function updateItem(Request $request, MenuItem $MenuItem)
    {

       
        $request->validate([
            'name' => 'sometimes|string',
            'price' => 'sometimes|numeric',
            'description' => 'sometimes|nullable|string',
            'menu_id' => 'sometimes|exists:menus,id',
            'image' => 'sometimes|nullable|file|image',
            'addons' => 'sometimes|array',
            'addons.*.name' => 'nullable|string',
            'addons.*.price' => 'nullable|numeric',
            'variations' => 'sometimes|array',
            'variations.*.type' => 'nullable|string',
            'variations.*.value' => 'nullable|string',
            'variations.*.price' => 'nullable|numeric',
        ]);

     DB::transaction(function () use ($request, $MenuItem) {

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('menu_items', 's3');
        } elseif (is_string($request->image)) {
            $imagePath = $request->image;
        }

        if ($request->has('name')) $MenuItem->name = $request->name;
        if ($request->has('image')) $MenuItem->image = $imagePath;

        if ($request->has('price')) $MenuItem->price = $request->price;
        if ($request->has('description')) $MenuItem->description = $request->description;
        if ($request->has('menu_id')) $MenuItem->menu_id = $request->menu_id;

        $MenuItem->save();

             // ---------- VARIATIONS (ADD ONLY) ----------
             if ($request->has('variations')) {
    
                foreach ($request->variations as $variation) {
                    menuItemVariation::create([
                        'type' => $variation['type'] ?? null,
                        'value' => $variation['value'] ?? null,
                        'price' => $variation['price'] ?? 0,
                        'menu_item_id' => $MenuItem->id,
                    ]);
                }
            }
    
            // ---------- ADDONS (ADD ONLY) ----------
            if ($request->has('addons')) {
    
                foreach ($request->addons as $addon) {
                    menuItemAddon::create([
                        'description' => $addon['name'] ?? null,
                        'price' => $addon['price'] ?? 0,
                        'menu_item_id' => $MenuItem->id,
                    ]);
                }
            }
    });

        return  response()->json($request);
    }
    
    
}
