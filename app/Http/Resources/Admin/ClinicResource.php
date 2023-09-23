<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\AddressResource;
use App\Http\Resources\DayResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClinicResource extends JsonResource
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
            'logo' => $this->logo ? asset($this->logo) : null,
            'phone' => $this->phone,
            'lat' => $this->lat,
            'long' => $this->long,
            'address' => $this->address != null ?  AddressResource::make($this->whenLoaded('address') )  : null
        ];

    }


    public function toUpdate()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'logo' => $this->photo != null ? asset($this->photo) : null,
            'phone' => $this->phone,
            'lat' => $this->lat,
            'long' => $this->long,
        ];
    }

    public function toWorkingDays()
    {
        return [
            'id' => $this->id,
            'from' => $this->from,
            'to' => $this->to,
            // 'day' => DayResource::make($this->whenLoaded('day')),
        ];
    }
}
