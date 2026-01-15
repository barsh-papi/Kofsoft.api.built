<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderSetting extends Model
{
    /** @use HasFactory<\Database\Factories\OrderSettingFactory> */
    use HasFactory;

    protected $fillable = [
        'orderingSet',
        'tax',
        'cashPayment',
        'mobilePayment',
        'guestCheckout',
        'deliveryInstruction',
        'shortText',
        'requiredBtn',
        'restaurant_id'
    ];

    protected $casts = [
        'orderingSet' => 'boolean',
        'cashPayment' => 'boolean',
        'mobilePayment' => 'boolean',
        'guestCheckout' => 'boolean',
        'requiredBtn' => 'boolean',
    ];

    public function Restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
