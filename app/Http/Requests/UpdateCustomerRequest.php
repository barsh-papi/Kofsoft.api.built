<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
            'password'=> ['required'],
            'username'=> ['required'],
            'phone'=> ['required'],
            // 'address'=> ['required'],
            'email'=> ['required'],
        ];
        }else{
        return [
            'password'=> ['sometimes','required'],
            'username'=> ['sometimes','required'],
            'phone'=> ['sometimes','required'],
            // 'address'=> ['sometimes','required'],
            'email'=> ['sometimes','required'],
        ];
        }
    }
}
