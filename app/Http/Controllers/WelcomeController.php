<?php

namespace App\Http\Controllers;

use App\Models\Lot;
use App\Models\Block;
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
        }
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
}
