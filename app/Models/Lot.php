<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Lot extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'lot',
        'price',
        'max_count',
        'area',
        'is_active',
        'is_available',
        'block_id',
        'status_id'
    ];

    public function block()
    {
        return $this->belongsTo('App\Models\Block', 'block_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\ListStatus', 'status_id', 'id');
    }

    public function coordinate()
    {
        return $this->hasOne('App\Models\LotCoordinate', 'lot_id');
    }

    public function owner()
    {
        return $this->hasOne('App\Models\OwnerLot', 'lot_id');
    }

    public function deceaseds()
    {
        return $this->hasMany('App\Models\Deceased', 'lot_id')->where('is_active',1);
    }

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = trim(str_replace(',','',$value),'₱');
    }

    public function getPriceAttribute($value)
    {
        return '₱'.number_format($value,2,'.',',');
    }

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
        ->logOnly(['max_count','price','area','is_available','is_active','status_id','block_id'])
        ->setDescriptionForEvent(fn(string $eventName) => "{$eventName} the lot information")
        ->useLogName('Lot')
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }
}
