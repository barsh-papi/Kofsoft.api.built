<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'note' => ['nullable'],
            'customer_id' => 'nullable|exists:customers,id',
            'restaurant_id' => 'nullable|exists:restaurants,id',
            'waiter_id' => 'nullable|exists:waiter,id',
            'address' => 'required|string|max:255',
            'payment' => 'required|in:cash,card,mobile_money',
            'items' => 'required|array',
            'totals' => 'required|numeric',
            'phone' => 'nullable|numeric',
            'status' => 'required|in:pending,processing,completed,cancelled',
            //
        ];
    }
}
