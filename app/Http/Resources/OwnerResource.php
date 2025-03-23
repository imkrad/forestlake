<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OwnerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'middlename' => $this->middlename,
            'lastname' => $this->lastname,
            'gender' => $this->gender,
            'civil_status' => $this->civil_status,
            'contact_number' => $this->contact_number,
            'email' => $this->email,
            'address' => $this->address,
            'birth_date' => $this->birth_date,
            'birth_place' => $this->birth_place,
            'occupation' => $this->occupation,
            'lots' => BlockResource::collection($this->lots)
        ];
    }
}
