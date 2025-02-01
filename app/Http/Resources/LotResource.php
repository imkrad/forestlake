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
            'number' => $this->number, 
            'price' => $this->price, 
            'area' => ($this->area) ? $this->area : '-', 
            'is_available' => $this->is_available, 
            'status' => $this->status,
            'coordinate' => $this->coordinate,
            'block_number' => $this->block->number,
            'block_name' => $this->block->name,
            'section' => $this->block->section->name,
            'created_at' => $this->created_at
        ];
    }
}
