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
                // 'sections' => Section::where('is_active',1)
                // ->select('section_id') // Select only distinct section IDs
                // ->distinct()
                // ->with('section')
                // ->get()->map(function ($item) {
                //     return [
                //         'value' => $item->id,
                //         'name' => $item->section->name
                //     ];
                // }),
                'lots' => [
                    'available' => Lot::where('is_available',1)->count(),
                    'notavailable' => Lot::where('status_id',2)->count()
                ]
            ]);
        }
    }

    public function lists(){
        return [
            [
                'name' => 'Available Lot',
                'count' => Lot::where('is_available',1)->where('status_id',1)->count(),
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
                'name' => 'Sold Lot',
                'count' => Lot::where('is_available',0)->where('status_id',1)->count(),
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
