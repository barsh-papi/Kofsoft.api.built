<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class OrderSettingResource extends JsonResource
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
            'orderingSet' => $this->orderingSet,
            'tax' => $this->tax,
            'cashPayment' => $this->cashPayment,
            'mobilePayment' => $this->mobilePayment,
            'guestCheckout' => $this->guestCheckout,
            'deliveryInstruction' => $this->deliveryInstruction,
            'shortText' => $this->shortText,
            'restaurantId'=>$this->restaurant_id,
            'requiredBtn' => $this->requiredBtn,
        ];
    }
}
