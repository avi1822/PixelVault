<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Game;

class Computer extends Model
{
    use HasFactory;
    protected $primaryKey = 'cid';
    public $incrementing = false;
    protected $fillable = ['cid', 'spec1', 'spec2', 'spec3', 'spec4', 'spec5', 'spec6', 'spec7', 'status'];
    public function games()
    {
        return $this->belongsToMany(Game::class, "computer_game", "computer_id", "game_id");
    }
    public function sessions()
    {
        return $this->hasMany(GamingSession::class, 'station_id', 'cid');
    }
}
