<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'review' => $this->review,
            'rating' => $this->rating,
            // 'restaurant' => RestaurantResource::collection($this->restaurant),
            // 'customer' =>CustomerResource::collection($this->customer),
        ];

    }
}
