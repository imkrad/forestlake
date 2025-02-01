<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lot extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'price',
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
}
