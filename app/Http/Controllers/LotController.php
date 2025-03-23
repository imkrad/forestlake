<?php

namespace App\Http\Controllers;

use App\Rules\NotZeroPeso;
use App\Models\Lot;
use App\Models\Block;
use App\Models\Section;
use App\Models\ListDropdown;
use App\Models\LotCoordinate;
use Illuminate\Http\Request;
use App\Http\Resources\LotResource;

class LotController extends Controller
{
    public function index(Request $request){
        switch($request->option){
            case 'lists':
                return $this->lists($request);
            break;
            default: 
                return inertia('Lot/Index');
        }
    }

    private function lists($request){
        $data = Lot::with('block.section.section','block.section.area','block.section.phase','coordinate','status')
            ->when($request->keyword, function ($query, $keyword) {
                $query->where('lot','LIKE', "%{$keyword}%");
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status_id',$status);
            })
            ->orderBy('created_at','DESC')
            ->paginate($request->count);

        return LotResource::collection($data);
    }

    public function store(Request $request){
        $section = new Section;
        $section->section_id = $request->section_id;
        $section->area_id = $request->area_id;
        $section->phase_id = $request->phase_id;
        $section->save();
        if($section){
            foreach($request->blocks as $index => $block){
                $data = $section->blocks()->create([
                    'block' => $block['block'],
                    'size' => $block['size']
                ]);
                if($data){
                    $lots = $block['lots'];
                    for ($i = 1; $i <= $lots; $i++) {
                        $data->lots()->create([
                            'lot' => $i,
                            'max_count' => $block['max'],
                            'status_id' => 4
                        ]);
                    }
                }
            }

            return back()->with([
                'data' => $section,
                'message' => 'Section was created!', 
                'info' => "You've successfully created new section.",
                'status' => true
            ]);
        }
    }

    public function update(Request $request){
        
        $request->validate([
            'price' => [new NotZeroPeso],
            'area' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
            'max_count' => 'required|integer'
        ]);

        $data = Lot::where('id',$request->id)->first();
        $data->area = $request->area;
        $data->price = $request->price;
        $data->status_id = 1;
        $data->is_available = 1;
        if($data->save()){
            $count = LotCoordinate::where('lot_id',$request->id)->count();
            if($count > 0){
                $lot = LotCoordinate::where('lot_id',$request->id)->first();
                $lot->longitude = $request->longitude;
                $lot->latitude = $request->latitude;
                $lot->save();
            }else{
                $coordinate = new LotCoordinate;
                $coordinate->longitude = $request->longitude;
                $coordinate->latitude = $request->latitude;
                $coordinate->lot_id = $request->id;
                $coordinate->save();
            }
        }

        $data = Lot::with('block.section','coordinate','status')
            ->where('id',$request->id)->first();
        
        return back()->with([
            'data' =>  new LotResource($data),
            'message' => 'Lot was updated!', 
            'info' => "You've successfully updated the lot.",
            'status' => true
        ]);
    }
}
