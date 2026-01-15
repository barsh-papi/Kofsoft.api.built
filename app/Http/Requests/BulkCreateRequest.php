<?php

// app/Http/Requests/BulkCreateRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkCreateRequest extends FormRequest
{
    public function rules()
    {
        return [
             'menu' => 'required|array',
            'restaurant_id' => ['required'],

            'menu.*.name' => 'required|string',
            'menu.*.layout' => 'required|string',
            'menu.*.items' => 'required|array',
            'menu.*.items.*.name' => 'required|string',
            'menu.*.items.*.price' => 'required|numeric',
            'menu.*.items.*.description' => 'required|string',
            'menu.*.items.*.image' => 'nullable|image',
            'menu.*.items.*.variations' => 'array',
            'menu.*.items.*.variations.*.name' => 'required|string',
            'menu.*.items.*.variations.*.price' => 'required|numeric',
            'menu.*.items.*.addons' => 'array',
            'menu.*.items.*.addons.*.name' => 'required|string',
            'menu.*.items.*.addons.*.price' => 'required|numeric',

        ];
    }
}
