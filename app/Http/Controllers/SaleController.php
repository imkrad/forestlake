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
            case 'buy':
                $this->buy($request);
            break;
        }
    }

    private function buy($request){
        $request->validate([
            'owner_id' => 'required',
            'lists' => 'required|array|min:1',
        ]);

        foreach($request->lists as $list){
            $data = new OwnerLot;
            $data->owner_id = $request->owner_id;
            $data->lot_id = $list['value'];
            $data->user_id = Auth::user()->id;
            if($data->save()){
                Lot::where('id',$list['value'])->update(['is_available' => 0, 'status_id' => 1]);
            }
        }
       
        return back()->with([
            'data' => [],
            'message' => 'Lot was added!', 
            'info' => "You've successfully added new lot to owner.",
            'status' => true
        ]);
    }
}
