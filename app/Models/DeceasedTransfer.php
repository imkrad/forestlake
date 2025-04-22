<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeceasedTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'cementery',
        'information',
        'transfered_at',
        'deceased_id'
    ];
}
