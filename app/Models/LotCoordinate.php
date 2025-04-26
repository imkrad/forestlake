<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class LotCoordinate extends Model
{
    use HasFactory, LogsActivity;

    public function lot()
    {
        return $this->belongsTo('App\Models\Lot', 'lot_id', 'id');
    }

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
        ->logOnly(['latitude','longitude','order'])
        ->setDescriptionForEvent(fn(string $eventName) => "{$eventName} the lot coordinates information")
        ->useLogName('Coordinate')
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }
}
