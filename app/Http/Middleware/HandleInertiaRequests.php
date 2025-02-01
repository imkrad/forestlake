<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\ListStatus;
use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Http\Resources\UserResource;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'user' => (\Auth::check()) ? new UserResource(User::with('profile')->where('id',\Auth::user()->id)->first()) : '',
            'flash' => [
                'data' => session('data'),
                'message' => session('message'),
                'info' => session('info'),
                'status' => session('status'),
                'type' => session('type')
            ],
            'lot_statuses' => ListStatus::where('type','Lot')->where('is_active',1)->get()->map(function ($item) {
                return [
                    'value' => $item->id,
                    'name' => $item->name,
                    'type' => $item->type,
                    'color' => $item->color,
                    'others' => $item->others,
                ];
            })
        ];
    }
}
