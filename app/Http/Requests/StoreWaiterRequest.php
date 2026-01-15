<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWaiterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fullname' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:waiters,email',
            'password' => 'required|min:6',
            'loginStatus' => 'required|in:active,inactive',
            'image' => 'nullable|image|max:2048',
            'restaurant_id' => 'required|exists:restaurants,id',
        ];
    }
}
