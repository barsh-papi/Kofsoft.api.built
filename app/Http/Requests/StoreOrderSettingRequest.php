<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderSettingRequest extends FormRequest
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
            'orderingSet' => ['required'],
            'tax' => ['nullable'],
            'cashPayment' => ['nullable'],
            'mobilePayment' => ['nullable'],
            'guestCheckout' => ['nullable'],
            'deliveryInstruction' => ['nullable'],
            'shortText' => ['nullable'],
            'requiredBtn' => ['nullable'],
            'restaurant_id' => ['nullable'],
            //
        ];
    }
}
