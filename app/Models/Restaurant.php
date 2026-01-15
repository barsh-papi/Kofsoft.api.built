<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    /** @use HasFactory<\Database\Factories\RestaurantFactory> */
    use HasFactory;

    protected $fillable = [
        'restaurantName',
        'restaurantDescription',
        'logo',
        'banner',
        'primaryColor',
        'user_id',
        'secondaryColor',
        'restaurantEmail',
        'restaurantPhone',
        'isBrandingVisible',
        'orderStatus',
        'currency'
    ];

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function Customer()
    {
        return $this->hasMany(Customer::class);
    }

    public function Order()
    {
        return $this->hasMany(Order::class);
    }

    public function review()
    {
        return $this->hasMany(Review::class);
    }

    public function OrderSetting()
    {
        return $this->hasOne(OrderSetting::class);
    }
     public function OrderView()
    {
        return $this->hasOne(OrderView::class);
    }

    public function menu()
    {
        return $this->hasMany(Menu::class);
    }

    public function waiter()
    {
        return $this->hasMany(Waiter::class);
    }

    // Accessor to return full image URLs
    protected $appends = ['logo_url', 'banner_url'];

    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }

    public function getBannerUrlAttribute()
    {
        return $this->banner ? asset('storage/' . $this->banner) : null;
    }
}
