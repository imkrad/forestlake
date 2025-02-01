<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use App\Http\Resources\OwnerResource;
use Illuminate\Http\Request;

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

    public function store(Request $request){
        $data = new Owner;
        $data->name = $request->name;
        $data->gender = $request->gender;
        $data->contact_number = $request->contact_number;
        $data->email = $request->email;
        $data->address = $request->address;
        $data->save();
        
        return back()->with([
            'data' => $data,
            'message' => 'Owner was created!', 
            'info' => "You've successfully created new owner.",
            'status' => true
        ]);
    }
}
