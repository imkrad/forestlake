<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LotResource extends JsonResource
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
            'lot' => $this->lot, 
            'max_count' => $this->max_count,
            'price' => $this->price, 
            'area' => ($this->area) ? $this->area : '-', 
            'is_available' => $this->is_available, 
            'status' => $this->status,
            'coordinate' => $this->coordinate,
            'block' => $this->block->block,
            'section' => $this->block->section->section->name,
            'area' => $this->block->section->area->name,
            'phase' => $this->block->section->phase->name,
            'created_at' => $this->created_at
        ];
    }
}
