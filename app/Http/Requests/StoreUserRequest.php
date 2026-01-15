<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            //
            'firstname'=> ['required'],
            'lastname'=> ['required'],
            'username'=> ['required','unique:users'],
            'phone'=> ['required'],
            // 'plan'=> ['required'],
            // 'planDuration'=> ['required'],
            'email'=> ['required','email','unique:users'],
            'password'=> ['required','min:8','confirmed']
        ];
            
    }
}
