<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'name',
        'description',
        'area',
        'is_active',
        'section_id',
        'is_available'
    ];

    public function lots()
    {
        return $this->hasMany('App\Models\Lot', 'block_id');
    }

    public function section()
    {
        return $this->belongsTo('App\Models\Section', 'section_id', 'id');
    }
}
