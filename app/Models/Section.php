<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_available'
    ];

    public function blocks()
    {
        return $this->hasMany('App\Models\Block', 'section_id');
    }
}
