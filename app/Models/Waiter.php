<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Waiter extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    // // ✅ Force the correct table name
    // protected $table = 'waiters';

    protected $fillable = [
        'fullname',
        'username',
        'email',
        'password',
        'loginStatus',
        'restaurant_id',
        'image'
    ];

    protected $hidden = ['password'];

    // Relationships
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'waiter_id');
    }
}
