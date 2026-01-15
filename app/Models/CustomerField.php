<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerField extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFieldFactory> */
    use HasFactory;

    protected $fillable = [
        'label',
        'inputtype',
        'customer_id',
    ];
}
