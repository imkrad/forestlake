<?php

namespace App\Http\Controllers;

use App\Models\Lot;
use App\Models\OwnerLot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function store(Request $request){
        switch($request->option){
            case 'add':
                $this->add($request);
            break;
        }
    }

    private function add($request){
        $request->validate([
            'owner_id' => 'required',
            'lot_id' => 'required',
        ]);

        $data = new OwnerLot;
        $data->owner_id = $request->owner_id;
        $data->lot_id = $request->lot_id;
        $data->user_id = Auth::user()->id;
        if($data->save()){
            Lot::where('id',$request->lot_id)->update(['is_available' => 0, 'status_id' => 1]);
        }
        return back()->with([
            'data' => $data,
            'message' => 'Lot was added!', 
            'info' => "You've successfully added new lot to owner.",
            'status' => true
        ]);
    }
}
