<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderView extends Model
{
    /** @use HasFactory<\Database\Factories\OrderSettingFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'restaurant_id',
        'count',
        'month'
    ];



    public function Restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
