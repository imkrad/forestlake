<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class DeceasedTransfer extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'cementery',
        'information',
        'transfered_at',
        'deceased_id'
    ];

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
        ->logOnly(['cementery','information','transfered_at'])
        ->setDescriptionForEvent(fn(string $eventName) => "{$eventName} the user information")
        ->useLogName('User')
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }
}
