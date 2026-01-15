<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

      protected $fillable = ['name', 'price', 'description', 'menu_id', 'image'];

    public function menu() {
        return $this->belongsTo(Menu::class);
    }
    public function variations() {
        return $this->hasMany(MenuItemVariation::class);
    }
    public function addons() {
        return $this->hasMany(MenuItemAddon::class);
    }
}
