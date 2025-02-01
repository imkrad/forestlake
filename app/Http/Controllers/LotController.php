<?php

namespace App\Http\Controllers;

use App\Models\Lot;
use App\Models\Block;
use App\Models\Section;
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
        $data = Lot::with('block.section','coordinate','status')
            ->when($request->keyword, function ($query, $keyword) {
                $query->where('number','LIKE', "%{$keyword}%");
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
        $section->name = $request->name;
        $section->description = $request->description;
        $section->save();
        if($section){
            foreach($request->blocks as $index => $block){
                $data = $section->blocks()->create([
                    'number' => $block['number'],
                    'name' => $block['name'],
                    'area' => $block['area']
                ]);
                if($data){
                    $lots = $block['lots'];
                    for ($i = 1; $i <= $lots; $i++) {
                        $data->lots()->create([
                            'number' => $i,
                            'status_id' => 1
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
}
