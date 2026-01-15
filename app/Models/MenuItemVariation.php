<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItemVariation extends Model
{
    /** @use HasFactory<\Database\Factories\MenuItemVariationFactory> */
    use HasFactory;

    protected $fillable = ['type', 'menu_item_id', 'value', 'price'];

    public function item()
    {
        return $this->belongsTo(MenuItem::class);
    }
     public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
