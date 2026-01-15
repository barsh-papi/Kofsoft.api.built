<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\CustomerResetPasswordNotification;


class Customer extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'password',
        'phone',
        // 'address',
        'email',
        'restaurant_id'
    ];

    public function sendPasswordResetNotification($token)
    {
        $restaurant = $this->Restaurant;
        $restaurantName = $restaurant->restaurantName ?? 'default';
        $this->notify(new CustomerResetPasswordNotification($token, $restaurantName));
    }

    public function Order()
    {
        return $this->hasMany(Order::class);
    }

    public function Restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function review()
    {
        return $this->hasMany(Review::class);
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
