<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkStoreMenuRequest extends FormRequest
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
            '*.name' => ['required'],
            '*.layout' => ['required'],
            '*.restaurant_id' => ['required'],
            //
        ];

    }
    protected function prepareForValidation(){
        $data =[];
        foreach ($this->toArray() as $obj){
            $obj ['restaurant_id'] = $obj['restaurant_id'] ?? null;
            $data[] = $obj;
        }
        $this->merge($data);
    }
}
