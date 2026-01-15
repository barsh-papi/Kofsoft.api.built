<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderSettingRequest extends FormRequest
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
                'orderingSet' => ['required'],
                'tax' => ['nullable'],
                'cashPayment' => ['nullable'],
                'mobilePayment' => ['nullable'],
                'guestCheckout' => ['nullable'],
                'deliveryInstruction' => ['nullable'],
                'shortText' => ['nullable'],
                'requiredBtn' => ['nullable'],
            ];
        } else {
            return [
                'orderingSet' => ['sometimes', 'required'],
                'tax' => ['sometimes', 'nullable'],
                'cashPayment' => ['sometimes', 'nullable'],
                'mobilePayment' => ['sometimes', 'nullable'],
                'guestCheckout' => ['sometimes', 'nullable'],
                'deliveryInstruction' => ['sometimes', 'nullable'],
                'shortText' => ['sometimes', 'nullable'],
                'requiredBtn' => ['sometimes', 'nullable'],
            ];
        }
    }
}
