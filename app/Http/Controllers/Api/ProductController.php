<?php

namespace App\Http\Controllers\Api;

use App\ApiFilter\MenuItemFilterQuery;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMenuItemRequest;
use App\Http\Requests\UpdateMenuItemRequest;
use App\Http\Resources\MenuItemCollection;
use App\Http\Resources\MenuItemResource;
use App\Models\MenuItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function Show(MenuItem $MenuItem)
    {
        return new MenuItemResource($MenuItem);
    }

    public function destroy(MenuItem $MenuItem)
    {
        $MenuItem->delete();
    }

    public function updateItem(Request $request, MenuItem $MenuItem){
        $request->validate([
            'name' => 'sometimes|string',
            'price' => 'sometimes|numeric',
            'description' => 'sometimes|nullable|string',
            'menu_id' => 'sometimes|exists:menus,id',
    
            'image' => 'sometimes|file|image',
    
            'addons' => 'sometimes|array',
            'addons.*.name' => 'nullable|string',
            'addons.*.price' => 'nullable|numeric',
    
            'variations' => 'sometimes|array',
            'variations.*.type' => 'nullable|string',
            'variations.*.value' => 'nullable|string',
            'variations.*.price' => 'nullable|numeric',
        ]);
    
        DB::transaction(function () use ($request, $MenuItem) {
    
    

        if ($request->hasFile('image')) {

            if ($MenuItem->image && Storage::disk('s3')->exists($MenuItem->image)) {
                Storage::disk('s3')->delete($MenuItem->image);
            }

            $MenuItem->image = $request->file('image')->store('menu_items', 's3');
        }

    
            // ---------- MAIN FIELDS ----------
            if ($request->has('name')) $MenuItem->name = $request->name;
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
    
        
            return response()->json([
            'message' => 'Menu item updated successfully'
        ]);

        // return $request;

    }
    
}
