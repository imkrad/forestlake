<?php

namespace App\Http\Controllers;

use App\Models\Owner;
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
        $data = Owner::with('lots')
            ->when($request->keyword, function ($query, $keyword) {
                $query->where('name','LIKE', "%{$keyword}%");
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
}
