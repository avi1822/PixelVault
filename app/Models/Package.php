<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'package_name',
        'package_time',
        'package_price',
        'ground_floor_price',
        'upper_floor_price'
    ];
}
