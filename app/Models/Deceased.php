<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Deceased extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'death_date',
        'birth_date',
        'burial_date',
        'lot_id',
        'cause_of_death',
        'type_id',
        'user_id',
        'is_active'
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords(strtolower($value));
    }

    public function lot()
    {
        return $this->belongsTo('App\Models\Lot', 'lot_id', 'id');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\ListDropdown', 'type_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function transfer()
    {
        return $this->hasOne('App\Models\DeceasedTransfer', 'deceased_id');
    } 

    public function getBirthDateAttribute($value)
    {
        return date('F d, Y', strtotime($value));
    }

    public function getDeathDateAttribute($value)
    {
        return date('F d, Y', strtotime($value));
    }

    public function getBurialDateAttribute($value)
    {
        return date('F d, Y', strtotime($value));
    }

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
        ->logOnly(['name','birth_date','death_date','burial_date','cause_of_death','lot_id','type_id'])
        ->setDescriptionForEvent(fn(string $eventName) => "{$eventName} the deceased information")
        ->useLogName('Decease')
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }
}
