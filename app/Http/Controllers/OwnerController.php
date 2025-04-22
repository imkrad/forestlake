<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use App\Models\OwnerLot;
use App\Http\Resources\OwnerResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\OwnerRequest;

class OwnerController extends Controller
{
    public function index(Request $request){
        switch($request->option){
            case 'lists':
                return $this->lists($request);
            break;
            default: 
                return inertia('Owner/Index');
        }
    }

    private function lists($request){
        $data = Owner::with('lots.lot.block.section.area','lots.lot.block.section.phase','lots.lot.deceaseds')
            ->when($request->keyword, function ($query, $keyword) {
                $query->where(\DB::raw('concat(firstname," ",lastname)'), 'LIKE', "%{$keyword}%")
                    ->orWhere(\DB::raw('concat(lastname," ",firstname)'), 'LIKE', "%{$keyword}%");
            })
            ->orderBy('created_at','DESC')
            ->paginate($request->count);

        return OwnerResource::collection($data);
    }

    public function store(OwnerRequest $request){
        $data = new Owner;
        $data->firstname = $request->firstname;
        $data->middlename = $request->middlename;
        $data->lastname = $request->lastname;
        $data->suffix = $request->suffix;
        $data->gender = $request->gender;
        $data->contact_number = $request->contact_number;
        $data->email = $request->email;
        $data->address = $request->address;
        $data->birth_date = $request->birth_date;
        $data->birth_place = $request->birth_place;
        $data->civil_status = $request->civil_status;
        $data->occupation = $request->occupation;
        $data->user_id = Auth::user()->id;
        $data->save();
        
        $owned = Owner::findOrFail($data->id);
        $owner = [
            'value' => $owned->id,
            'name' => $owned->name
        ];

        return back()->with([
            'data' => $owner,
            'message' => 'Owner was created!', 
            'info' => "You've successfully created new owner.",
            'status' => true
        ]);
    }

    public function update(OwnerRequest $request){
        $data = Owner::where('id',$request->id)->first();
        $data->update($request->all());
        
        $owned = Owner::findOrFail($data->id);
        $owner = [
            'value' => $owned->id,
            'name' => $owned->name
        ];

        return back()->with([
            'data' => $owner,
            'message' => 'Owner was updated!', 
            'info' => "You've successfully updated owner details.",
            'status' => true
        ]);
    }

    public function sell(Request $request){
        $lot_id = $request->lot_id;
        $owner_id = $request->owner_id;
       
        $data = OwnerLot::where('lot_id',$lot_id)->first();
        $data->owner_id = $owner_id;
        $data->sold_at = $request->date;
        $data->save();
        
    
        return back()->with([
            'data' => $data,
            'message' => 'Lot was sold!', 
            'info' => "You've successfully updated the lot owner.",
            'status' => true
        ]);
    }
}
