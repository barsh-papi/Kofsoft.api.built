<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReviewRequest extends FormRequest
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
            'review'=> ['required'],
            'rating'=> ['required'],
            'restaurant_id'=>['required'],
            'customer_id'=>['required'],
        ];
        }else{
        return [
            'review'=> ['sometimes','required'],
            'rating'=> ['sometimes','required'],
            'restaurant_id'=>['sometimes','required'],
            'customer_id'=>['sometimes','required'],
        ];
        }
    }
}
