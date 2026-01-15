<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class OrderResource extends JsonResource
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
            'customer' => $this->customer?->phone,
            'restaurant' => $this->restaurant?->restaurantName,
            'address' => $this->address,
            'payment' => $this->payment,
            'items' => $this->items,
            'totals' => $this->totals,
            'phone' => $this->phone,
            'status' => $this->status,
            'note' => $this->note,
            'waiter' => $this->waiter?->fullname,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
