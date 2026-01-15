<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderViewRequest extends FormRequest
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
                'month' => ['nullable'],
                'count' => ['nullable'],
                'restaurant_id' => ['nullable'],
                'user_id' => ['nullable'],
            ];
        } else {
            return [
                'month' => ['sometimes', 'nullable'],
                'count' => ['sometimes', 'nullable'],
                'restaurant_id' => ['sometimes', 'nullable'],
                'user_id' => ['sometimes', 'nullable'],
            ];
        }
    }
}
