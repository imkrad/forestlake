<?php

namespace App\Http\Controllers;

use App\Models\Lot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request){
        if(!Auth::check()){
            return inertia('Auth/Login');
        }else{
            return inertia('Dashboard/Index',[
                'lists' => $this->lists()
            ]);
        }
    }

    public function lists(){
        return [
            [
                'name' => 'Available',
                'count' => Lot::where('status_id',1)->count(),
                'icon' => 'ri-checkbox-circle-fill fs-20',
                'color' => 'text-success'
            ],
            [
                'name' => 'Occupied',
                'count' => Lot::where('status_id',2)->count(),
                'icon' => 'ri-checkbox-circle-fill fs-20',
                'color' => 'text-warning'
            ],
            [
                'name' => 'Full',
                'count' => Lot::where('status_id',3)->count(),
                'icon' => 'ri-information-fill fs-20',
                'color' => 'text-danger'
            ]
        ];
    }
}
