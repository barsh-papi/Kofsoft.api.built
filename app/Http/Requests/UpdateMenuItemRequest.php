<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMenuItemRequest extends FormRequest
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
        if( $method == 'PUT'){
        return [
           'name'=> ['required'],
            'price'=> ['required'],
            'description'=> ['nullable'],
            'image'=> ['nullable'],
            'menu_id'=> ['required'],
        ];
        }else{
        return [
            'name'=> ['sometimes','required'],
            'price'=> ['sometimes','required'],
            'description'=> ['sometimes','required'],
            'menu_id'=> ['sometimes','required'],
            'image'=> ['sometimes','required'],
        ];
        }
    }
}
