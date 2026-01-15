<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkStoreMenuItemsRequest extends FormRequest
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
            '*.description' => ['required'],
            '*.price' => ['required'],
            '*.image' => ['required'],
            '*.menu_id' => ['required'],
            //
        ];

    }
    protected function prepareForValidation(){
        $data =[];
        foreach ($this->toArray() as $obj){
            $obj ['menu_id'] = $obj['menu_id'] ?? null;
            $data[] = $obj;
        }
        $this->merge($data);
    }
}
