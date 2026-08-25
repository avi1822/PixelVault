<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Reservation;
use App\Models\GamingSession;
use App\Models\InvoiceItem;
use App\Models\Payment;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    protected $fillable = [
        'invoice_number',
        'user_id',
        'reservation_id',
        'gaming_session_id',
        'subtotal',
        'discount',
        'tax',
        'total',
        'paid_amount',
        'status',
        'issued_at',
        'notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }

    public function gamingSession()
    {
        return $this->belongsTo(GamingSession::class, 'gaming_session_id');
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'invoice_id');
    }
}
