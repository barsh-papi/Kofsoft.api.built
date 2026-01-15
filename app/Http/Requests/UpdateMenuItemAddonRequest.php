<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMenuItemAddonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
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
            'description'=> ['required'],
            'price'=> ['required'],
        ];
        }else{
        return [
            'description'=> ['sometimes','required'],
            'price'=> ['sometimes','required'],
        ];
        }
    }
}
