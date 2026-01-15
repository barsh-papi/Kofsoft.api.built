<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'note',
        'customer_id',
        'restaurant_id',
        'waiter_id',
        'address',
        'phone',
        'payment',
        'items',
        'totals',
        'status',
    ];

    protected $casts = [
        'items' => 'array',
    ];

    public function Customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function waiter()
    {
        return $this->belongsTo(Waiter::class);
    }
}
