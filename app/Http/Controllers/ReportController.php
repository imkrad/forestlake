<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request){
        switch($request->option){
            case 'lists':
                return [];
            break;
            default: 
                return inertia('Report/Index');
        }
    }
}
