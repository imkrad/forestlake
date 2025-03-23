<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Lot;
use App\Models\Section;
use App\Models\Deceased;
use Illuminate\Http\Request;
use App\Http\Requests\GraveRequest;
use App\Http\Resources\DeceasedResource;

class GraveController extends Controller
{
    public function index(Request $request){
        switch($request->option){
            case 'lists':
                return $this->lists($request);
            break;
            default: 
                return inertia('Grave/Index',[
                    'sections' => Section::where('is_active',1)->get()->map(function ($item) {
                        return [
                            'value' => $item->id,
                            'name' => $item->name
                        ];
                    })
                ]);
        }
    }

    private function lists($request){
        $data = DeceasedResource::collection(
            Deceased::query()
            ->with('lot.owner.owner')
            ->when($request->keyword, function ($query, $keyword) {
                $query->where('name', 'LIKE', "%{$keyword}%");
            })
            ->orderBy('created_at','DESC')
            ->paginate($request->count)
        );
        return $data;
    }

    public function store(GraveRequest $request){
        $data = new Deceased;
        $data->name = $request->name;
        $data->death_date = $request->death_date;
        $data->birth_date = $request->birth_date;
        $data->burial_date = $request->burial_date;
        $data->cause_of_death = $request->cause_of_death;
        $data->type_id = $request->type_id;
        $data->lot_id = $request->lot_id;
        $data->user_id = Auth::user()->id;
        if($data->save()){
            $lot = Lot::where('id',$request->lot_id)->first();
            $count = $lot->deceaseds()->count();
            $lot->is_available = 0;
            $lot->status_id = ($lot->max_count <= $count) ? 3 : 1;
            $lot->save();
            // Lot::where('id',$request->lot_id)->update(['is_available' => 0, 'status_id' => 3]);
        }
        
        return back()->with([
            'data' => $data,
            'message' => 'Deceased was added!', 
            'info' => "You've successfully added new deceased.",
            'status' => true
        ]);
    }
}
