<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DefaultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'birth_date' => $this->birth_date,
            'death_date' => $this->death_date,
            'burial_date' => $this->burial_date,
            'cause_of_death' => $this->cause_of_death,
            'type' => $this->type,
            'lot' => new LotResource($this->lot)
        ];
    }
}
