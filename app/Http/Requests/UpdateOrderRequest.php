<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
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
                'note' => ['required'],
                'customer_id' => 'nullable|exists:customers,id',
                'restaurant_id' => 'nullable|exists:restaurants,id',
                'address' => 'required|string|max:255',
                'payment' => 'required|in:cash,card,mobile_money',
                'items' => 'required|array',
                'waiter_id' => 'nullable|exists:waiters,id',
                'totals' => 'required|numeric',
                'phone' => 'nullable|numeric',
                'status' => 'required|in:pending,processing,completed,cancelled',
            ];
        } else {
            return [
                'note' => ['sometimes', 'string', 'nullable'],
                'customer_id' => ['sometimes', 'nullable', 'exists:customers,id'],
                'restaurant_id' => ['sometimes', 'nullable', 'exists:restaurants,id'],
                'address' => ['sometimes', 'string', 'max:255'],
                'payment' => ['sometimes', 'in:cash,card,mobile_money'],
                'items' => ['sometimes', 'array'],
                'waiter_id' => 'sometimes|nullable|exists:waiters,id',
                'totals' => ['sometimes', 'numeric'],
                'phone' => ['sometimes', 'numeric'],
                'status' => ['sometimes', 'in:pending,processing,completed,cancelled'],
            ];
        }
    }
}
