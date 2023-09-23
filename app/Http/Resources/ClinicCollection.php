<?php

namespace App\Http\Resources;

use App\Http\Resources\Admin\ClinicResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ClinicCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }

    public function toWorkingDays()
    {
        return $this->collection->groupBy(function ($day) {
            return $day->day->{'name_'.app()->getLocale()};
        })->map(function ($days) {
            return $days->map(function ($day) {
                return (new ClinicResource($day))->toWorkingDays();
            });
        })->toArray();
    }
}
