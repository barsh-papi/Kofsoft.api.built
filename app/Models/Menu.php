<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    /** @use HasFactory<\Database\Factories\MenuFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'layout',
        'restaurant_id',
    ];

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }
     public function product()
    {
        return $this->hasMany(Product::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
