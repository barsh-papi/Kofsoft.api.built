<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class WaiterResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'fullname' => $this->fullname,
            'username' => $this->username,
            'email' => $this->email,
            'loginStatus' => $this->loginStatus,
            'image' => $this->image,
            'restaurant' => $this->restaurant?->restaurantName,
            'orders' => OrderResource::collection($this->whenLoaded('orders')),
            'dateOfCreation' => $this->created_at
        ];
    }
}
