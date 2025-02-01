<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'gender',
        'contact_number',
        'email',
        'address',
        'avatar'
    ];

    public function lots()
    {
        return $this->hasMany('App\Models\OwnerLot', 'owner_id');
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords(strtolower($value));
    }
}
