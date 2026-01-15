<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWaiterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fullname' => 'sometimes|required|string|max:100',
            'username' => 'sometimes|required|string|max:100',
            'email' => 'sometimes|required|email|unique:waiters,email,' . $this->waiter->id,
            'password' => 'sometimes|nullable|string|min:6|confirmed',
            'loginStatus' => 'sometimes|boolean',
            'image' => 'nullable|string',
            'restaurant_id' => 'sometimes|required|exists:restaurants,id',
        ];
    }
}
