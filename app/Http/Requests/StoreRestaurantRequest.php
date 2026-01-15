<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRestaurantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'restaurantName' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'banner' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:4096'],
            'isBrandingVisible' => ['required', 'boolean'],
            'secondaryColor' => ['required', 'string'],
            'primaryColor' => ['required', 'string'],
            'currency' => ['required', 'string'],
            'user_id' => ['required', 'integer'],
            'restaurantDescription' => ['nullable', 'string'],
            'orderStatus' => ['nullable', 'string'],

            'restaurantPhone' => ['required', 'string'],
            'restaurantEmail' => ['required', 'email', 'unique:restaurants,restaurantEmail'],
        ];
    }
}
