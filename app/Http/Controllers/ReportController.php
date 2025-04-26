<?php

namespace App\Http\Controllers;

use App\Models\Deceased;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Http\Resources\ActivityResource;

class ReportController extends Controller
{
    public function index(Request $request){
        switch($request->option){
            case 'lists':
                return [];
            break;
            default: 
                return inertia('Report/Index',[
                    'logs' => ActivityResource::collection(Activity::with('causer.profile')->orderBy('created_at','DESC')->get()),
                    'deceased' => Deceased::select('type_id', DB::raw('count(*) as total'))
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->groupBy('type_id')
                    ->get()
                 ]);
        }
    }
}
