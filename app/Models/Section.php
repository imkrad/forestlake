<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'area_id',
        'phase_id',
        'is_active'
    ];

    public function section()
    {
        return $this->belongsTo('App\Models\ListDropdown', 'section_id', 'id');
    }

    public function area()
    {
        return $this->belongsTo('App\Models\ListDropdown', 'area_id', 'id');
    }

    public function phase()
    {
        return $this->belongsTo('App\Models\ListDropdown', 'phase_id', 'id');
    }

    public function blocks()
    {
        return $this->hasMany('App\Models\Block', 'section_id');
    }
}
