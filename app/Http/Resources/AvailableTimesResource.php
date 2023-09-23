<?php

namespace App\Http\Resources;

use App\Helpers\FileHelper;
use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AvailableTimesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'from' => $this['from'],
            'to' => $this['to'],
            'date' => $this['date'],
            'day' => $this['day']['name_'. app()->getLocale()],
            'start_time' => ResponseHelper::convertDate( $this['date'], $this['from']),
            'end_time' => ResponseHelper::convertDate( $this['date'], $this['to']),
        ];
    }
}
