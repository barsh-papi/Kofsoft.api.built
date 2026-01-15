<?php

namespace App\Http\Controllers\Api;

use App\ApiFilter\MenuFilterQuery;
use App\Http\Controllers\Controller;
use App\Http\Requests\BulkCreateRequest;
use App\Http\Requests\BulkStoreMenuRequest;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Http\Resources\MenuCollection;
use App\Http\Resources\MenuResource;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\MenuItemAddon;
use App\Models\MenuItemVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new MenuFilterQuery();
        $filterItems = $filter->transform($request);  // column-operator-value
        $Menu = Menu::where($filterItems)->with('menuItems', 'restaurant');
        return new MenuCollection($Menu->paginate()->appends($request->query()));
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
    public function store(StoreMenuRequest $request)
    {
        return new MenuResource(Menu::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $Menu)
    {
        return $Menu;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $Menu)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMenuRequest $request, Menu $Menu)
    {
        $Menu->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $Menu)
    {
        $Menu->delete();
        //
    }

    public function bulkCreate(Request $request)
{
    // Validate the FormData input
    $request->validate([
        'menus' => 'required|array',
        'menus.*.name' => 'required|string',
        'menus.*.layout' => 'nullable|string',
        'menus.*.restaurant_id' => 'required|exists:restaurants,id',

        'menus.*.items' => 'nullable|array',
        'menus.*.items.*.name' => 'required|string',
        'menus.*.items.*.price' => 'required|numeric',
        'menus.*.items.*.description' => 'nullable|string',
        'menus.*.items.*.image' => 'nullable|file|image',

        'menus.*.items.*.variations' => 'nullable|array',
        'menus.*.items.*.variations.*.type' => 'nullable|string',
        'menus.*.items.*.variations.*.values' => 'nullable|array',
        'menus.*.items.*.variations.*.values.*.value' => 'nullable|string',
        'menus.*.items.*.variations.*.values.*.additionalPrice' => 'nullable|numeric',

        'menus.*.items.*.addons' => 'nullable|array',
        'menus.*.items.*.addons.*.name' => 'nullable|string',
        'menus.*.items.*.addons.*.price' => 'nullable|numeric',
    ]);

    $menusData = $request->input('menus', []);

    DB::transaction(function () use ($menusData, $request) {
        foreach ($menusData as $menuIndex => $menuData) {

            // Create Menu
            $menu = Menu::create([
                'name' => $menuData['name'],
                'layout' => $menuData['layout'] ?? null,
                'restaurant_id' => $menuData['restaurant_id'],
            ]);

            // Create Menu Items
            if (!empty($menuData['items'])) {
                foreach ($menuData['items'] as $itemIndex => $item) {

                  
                $imageValue = null;

                if ($request->hasFile("menus.$menuIndex.items.$itemIndex.image")) {
                    $path = $request
                        ->file("menus.$menuIndex.items.$itemIndex.image")
                        ->store('menu_items', 's3');
                    $imageValue = Storage::disk('s3')->url($path);

                } elseif (is_string($item['image'] ?? null)) {
                    $imageValue = $item['image'];
                }
                
                    // Create Menu Item
                    $menuItem = MenuItem::create([
                        'name' => $item['name'],
                        'price' => $item['price'],
                        'description' => $item['description'] ?? null,
                        'image' => $imageValue,
                        'menu_id' => $menu->id,
                    ]);

                    // Create Variations
                    if (!empty($item['variations'])) {
                        foreach ($item['variations'] as $variation) {
                            $type = $variation['type'] ?? null;
                            if (!empty($variation['values'])) {
                                foreach ($variation['values'] as $value) {
                                    MenuItemVariation::create([
                                        'type' => $type,
                                        'value' => $value['value'] ?? null,
                                        'price' => $value['additionalPrice'] ?? 0,
                                        'menu_item_id' => $menuItem->id,
                                    ]);
                                }
                            }
                        }
                    }

                    // Create Addons
                    if (!empty($item['addons'])) {
                        foreach ($item['addons'] as $addon) {
                            MenuItemAddon::create([
                                'description' => $addon['name'] ?? null,
                                'price' => $addon['price'] ?? 0,
                                'menu_item_id' => $menuItem->id,
                            ]);
                        }
                    }
                }
            }
        }
    });

    return response()->json(['message' => 'Menus created successfully']);
    
}

}
