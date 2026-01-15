<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'firstname'=> ['required'],
            'lastname'=> ['required'],
            'username'=> ['required','unique:users'],
            'email'=> ['required','email','unique:users'],
            'password'=> ['required','min:8','confirmed'],
            'phone'=> ['required'],
            'plan'=> ['required'],
            'planDuration'=> ['required']
        ];
        
        }else{
        return [
            'firstname'=> ['sometimes','required'],
            'lastname'=> ['sometimes','required'],
            'username'=> ['sometimes','required','unique:users'],
            'email'=> ['sometimes','required','email','unique:users'],
            'password'=> ['sometimes','required','min:8','confirmed'],
            'phone'=> ['sometimes','required'],
            'plan'=> ['sometimes','required'],
            'trial_ends_at'=>['sometimes','required'],
            'planDuration'=> ['sometimes','required']
        ];
        }
    }
}
