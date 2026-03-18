<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{

    protected $fillable = [
        'part_no',
        'brand',
        'part_name',
        'description'
    ];
//
}
