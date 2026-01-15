<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'username' => $this->username,
            'restaurant' => new RestaurantResource($this->restaurant),
            'phone' => $this->phone,
            'plan' => $this->plan,
            'planDuration' => $this->planDuration,
            'plan_status' => $this->plan_status,
            'email' => $this->email,
        ];
    }
}
