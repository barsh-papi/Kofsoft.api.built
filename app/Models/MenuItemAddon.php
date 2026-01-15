<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItemAddon extends Model
{
    /** @use HasFactory<\Database\Factories\MenuItemAddonFactory> */
    use HasFactory;

   

     protected $fillable = ['menu_item_id', 'description', 'price'];

    public function item() {
        return $this->belongsTo(MenuItem::class);
    }
}
