<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRestaurantRequest extends FormRequest
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
        $method = $this->method;
        if ($method == 'PUT') {
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
                'restaurantPhone' => ['required', 'string'],
                'orderStatus' => ['nullable', 'string'],

                'restaurantEmail' => ['required', 'email', 'unique:restaurants,restaurantEmail'],
            ];
        } else {
            return [
                'restaurantName' => ['sometimes', 'string', 'max:255'],
                'logo' => ['sometimes', 'nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
                'banner' => ['sometimes', 'nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:4096'],
                'isBrandingVisible' => ['sometimes', 'boolean'],
                'secondaryColor' => ['sometimes', 'string'],
                'primaryColor' => ['sometimes', 'string'],
                'currency' => ['sometimes', 'string'],
                'user_id' => ['sometimes', 'integer'],
                'restaurantDescription' => ['sometimes', 'nullable', 'string'],
                'restaurantPhone' => ['sometimes', 'string'],
                'orderStatus' => ['sometimes', 'string'],

                'restaurantEmail' => ['sometimes', 'email', 'unique:restaurants,restaurantEmail']
            ];
        }
    }
}
