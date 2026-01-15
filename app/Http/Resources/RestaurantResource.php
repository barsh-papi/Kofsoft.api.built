<?php

namespace App\Http\Resources;

use App\Models\OrderSetting;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class RestaurantResource extends JsonResource
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
            'restaurantName' => $this->restaurantName,
            'restaurantDescription' => $this->restaurantDescription,
            'restaurantEmail' => $this->restaurantEmail,
            'restaurantPhone' => $this->restaurantPhone,
            'currency' => $this->currency,
            'isBrandingVisible' => $this->isBrandingVisible,
            'logo' => $this->logo,
            'banner' => $this->banner,
            'primaryColor' => $this->primaryColor,
            'orderStatus' => $this->orderStatus,
            'secondaryColor' => $this->secondaryColor,
            'orderSettings' => new OrderSettingResource($this->ordersetting),
            'menus' => MenuResource::collection($this->menu),
            'order' => OrderResource::collection($this->order),
            'waiters' => WaiterResource::collection($this->waiter),
            'reviews' => ReviewResource::collection($this->review),
            'customers' => CustomerResource::collection($this->customer),
        ];
    }
}
