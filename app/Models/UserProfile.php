<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class UserProfile extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'gender',
        'mobile',
        'user_id',
        'avatar'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords(strtolower($value));
    }

    protected static $recordEvents = ['updated'];
    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
        ->logOnly(['firstname','lastname','middlename','mobile'])
        ->useLogName('User Profile')
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }
}
