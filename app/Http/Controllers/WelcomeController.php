<?php

namespace App\Http\Controllers;

use App\Models\Lot;
use App\Models\Block;
use App\Models\Owner;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index(){
        return inertia('Auth/Login');
    }

    public function search(Request $request){
        $option = $request->option;
        switch($option){
            case 'blocks':
                return $this->blocks($request->code);
            break;
            case 'lots':
                return $this->lots($request->code);
            break;
            case 'lots2':
                return $this->lots2($request->code);
            break;
            case 'lots3':
                return $this->lots3($request);
            break;
            case 'owner':
                return $this->owner($request->code,$request->id);
            break;
        }
    }

    private function owner($code,$id){
        $id = (int) $id;
        $data = Owner::when($code, function ($query, $code,) {
            $query->where(\DB::raw('concat(firstname," ",lastname)'), 'LIKE', "%{$code}%")
                    ->orWhere(\DB::raw('concat(lastname," ",firstname)'), 'LIKE', "%{$code}%");
        })
        ->take(5)->get()
        ->filter(function ($item) use ($id) {
            return $item->id != $id;
        })
        ->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->firstname.' '.$item->lastname
            ];
        });
        return $data;
    }

    private function blocks($code){
        $data = Block::where('section_id',$code)->where('is_active',1)->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'number' => $item->number,
                'name' => 'Block '.$item->number.' - '.$item->name
            ];
        });
        return $data;
    }

    private function lots($code){
        $data = Lot::where('block_id',$code)->where('is_available',1)->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => 'Lot - '.$item->number
            ];
        });
        return $data;
    }

    private function lots2($code){
        $data = Lot::with('block.section')
        ->whereHas('owner', function ($query) use ($code) {
            $query->where('owner_id',$code);
        })
        ->where('status_id',1)->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => 'Lot '.$item->lot.' - Block '.$item->block->block.' - Section '.$item->block->section->section->name.' - Area '.$item->block->section->area->name.' - '.$item->block->section->phase->name
            ];
        });
        return $data;
    }

    private function lots3($request){
        $code = $request->code;
        $data = Lot::with('block.section.section','block.section.area','block.section.phase')
        ->where('status_id',1)->where('is_available',1)->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => 'Lot '.$item->lot,
                'block' => $item->block->block,
                'section' => $item->block->section->section->name,
                'area'=> $item->block->section->area->name,
                'phase'=> $item->block->section->phase->name
            ];
        });
        return $data;
    }
}
