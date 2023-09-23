<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
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
            'category' => CategoryResource::make($this->whenLoaded('category')),
            'name' => $this['name_'. app()->getLocale()],
            'minimum_quantity' => $this->minimum_quantity,
            'price' => $this->price,
            'note' => $this->note,
            'quantities' => QuantityResource::collection($this->whenLoaded('quantities'))
        ];
    }
}
