<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_name',
        'phone_number',
        'hours_played',
        'game_played',
        'food_item',
        'zone_location',
        'entry_date'
    ];
}
