<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes; // Optional

class GuestFolio extends Model
{
    use HasFactory;
    // use SoftDeletes; // Optional

    protected $fillable = [
        'folio_code',
        'guest_code',
        'checkin_code',
        'room_code',
        'total_amount',
        'paid_amount',
        'status',
        'currency',
        'notes',
    ];

    protected $casts = [
        'total_amount' => 'float',
        'paid_amount' => 'float',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_code', 'room_code');
    }

    public function guest()
    {
        return $this->belongsTo(Guest::class, 'guest_code', 'guest_code');
    }

    public function checkin()
    {
        return $this->belongsTo(GuestCheckin::class, 'checkin_code', 'checkin_code');
    }

    public function items()
    {
        return $this->hasMany(GuestFolioItem::class, 'folio_id');
    }

    public function recalculateTotals()
    {
        $charges = $this->items()->where('type', 'charge')->sum('amount');
        $payments = $this->items()->where('type', 'payment')->sum('amount');
        $this->total_amount = $charges;
        $this->paid_amount = $payments;
        $this->save();
    }

    public function getBalanceAttribute()
    {
        return $this->total_amount - $this->paid_amount;
    }
}
