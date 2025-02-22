<?php

namespace App\Http\Controllers;

use App\Models\Lot;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request){
        if(!Auth::check()){
            return inertia('Auth/Login');
        }else{
            return inertia('Dashboard/Index',[
                'counts' => $this->lists(),
                'sections' => Section::where('is_active',1)->get()->map(function ($item) {
                    return [
                        'value' => $item->id,
                        'name' => $item->name
                    ];
                })
            ]);
        }
    }

    public function lists(){
        return [
            [
                'name' => 'Available Lot',
                'count' => Lot::where('status_id',1)->count(),
                'icon' => 'ri-checkbox-circle-fill fs-20',
                'color' => 'text-success'
            ],
            [
                'name' => 'Occupied Lot',
                'count' => Lot::where('status_id',2)->count(),
                'icon' => 'ri-checkbox-circle-fill fs-20',
                'color' => 'text-warning'
            ],
            [
                'name' => 'Full Lot',
                'count' => Lot::where('status_id',3)->count(),
                'icon' => 'ri-information-fill fs-20',
                'color' => 'text-danger'
            ],
            [
                'name' => 'Incomplete',
                'count' => Lot::where('status_id',4)->count(),
                'icon' => 'ri-close-circle-fill fs-20',
                'color' => 'text-dark'
            ]
        ];
    }
}
