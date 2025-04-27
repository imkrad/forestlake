<?php

namespace App\Http\Resources;

use App\Models\Lot;
use App\Models\Owner;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        $subject_id = $this->subject_id;
        if($this->log_name == 'Lot'){
            $lot = Lot::with('block.section.section','block.section.area','block.section.phase')->where('id',$subject_id)->first();
            $description = 'Lot '.$lot->lot.' Block '.$lot->block->block.' Section '.$lot->block->section->section->name.' '.$lot->block->section->area->name.' '.$lot->block->section->phase->name.' details '.$this->event;
        }else if($this->log_name == 'Owner Lot'){
            if($this->event == 'updated'){
                $old = Owner::where('id',$this->properties['old']['owner_id'])->first();
                $new = Owner::where('id',$this->properties['attributes']['owner_id'])->first();
                $description = 'Lot sold from '.$old->firstname.' '.$old->lastname.' to '.$new->firstname.' '.$new->lastname;
            }else{
                $description = $this->description;
            }
        }else{
            $description = $this->description;
        }
        return [
            'log_name' => $this->log_name,
            'description' => $description,
            'event' => $this->event,
            'causer' => $this->causer,
            'properties' => $this->properties,
            'created_at' => $this->created_at ? $this->created_at->format('F d, Y g:i A') : null,
        ];
    }
}
