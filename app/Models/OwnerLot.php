<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class OwnerLot extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'owner_id',
        'lot_id',
        'user_id'
    ];

    public function lot()
    {
        return $this->belongsTo('App\Models\Lot', 'lot_id', 'id');
    }

    public function owner()
    {
        return $this->belongsTo('App\Models\Owner', 'owner_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
        ->logOnly([ 'owner_id',
        'lot_id',
        'user_id'])
        ->setDescriptionForEvent(fn(string $eventName) => "{$eventName} the owner lot information")
        ->useLogName('Owner Lot')
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }
}
