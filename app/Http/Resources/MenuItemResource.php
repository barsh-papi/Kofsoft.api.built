<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class MenuItemResource extends JsonResource
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
        'name' => $this->name,
        'description' => $this->description,
        'price' => $this->price,
        'image' => $this->image ? Storage::url($this->image) : null,
        'menuCategory' => $this->menu ? $this->menu->name : null,
        'variations' => new MenuItemVariationCollection($this->variations),
        'addons' => new MenuItemAddonCollection($this->addons),
    ];
    }
}
