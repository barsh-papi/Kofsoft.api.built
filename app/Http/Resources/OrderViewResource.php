<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class OrderViewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // 'id' => $this->id,
            'count' => $this->count,
            'month' => $this->month,
            'restaurantId' => $this->restaurant_id,
            'userId' => $this->user_id,
        ];
    }
}
