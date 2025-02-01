<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OwnerLot extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'lot_id'
    ];

    public function lot()
    {
        return $this->belongsTo('App\Models\Lot', 'lot_id', 'id');
    }

    public function owner()
    {
        return $this->belongsTo('App\Models\Owner', 'owner_id', 'id');
    }
}
