<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deceased extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'death_date',
        'birth_date',
        'burial_date',
        'lot_id',
        'cause_of_death',
        'user_id'
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords(strtolower($value));
    }

    public function lot()
    {
        return $this->belongsTo('App\Models\Lot', 'lot_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
