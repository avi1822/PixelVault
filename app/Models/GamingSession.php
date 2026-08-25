<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Computer;
use App\Models\Reservation;

class GamingSession extends Model
{
    use HasFactory;

    protected $table = 'gaming_sessions';

    protected $fillable = [
        'reservation_id',
        'user_id',
        'station_id',
        'started_at',
        'expected_end_at',
        'ended_at',
        'status',
        'duration_minutes',
        'base_amount',
        'notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function computer()
    {
        return $this->belongsTo(Computer::class, 'station_id', 'cid');
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'gaming_session_id');
    }
}
