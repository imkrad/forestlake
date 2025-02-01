<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GraveController extends Controller
{
    public function index(Request $request){
        switch($request->option){
            case 'lists':
                return [];
            break;
            default: 
                return inertia('Grave/Index');
        }
    }
}
