<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestCheckin extends Model
{
    use HasFactory;

    protected $table = 'guest_checkin';

    protected $fillable = [
        'checkin_code', 'reservation_ref', 'guest_code', 'room_code',
        'checkin_date', 'checkout_date', 'cancelled_date', 'rate', 'total_payment',
        'payment_method', 'number_of_guest', 'is_checkout', 'created_by',
        'modified_by', 'is_active', 'note'
    ];

    public function guest()
    {
        return $this->belongsTo(Guest::class, 'guest_code', 'guest_code');
    }

    public function room() {
        return $this->belongsTo(Room::class, 'room_code', 'room_code');
    }

    public function reservation() {
        return $this->belongsTo(GuestReservation::class, 'reservation_ref', 'reservation_code');
    }

    // App\Models\GuestCheckin.php
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($checkin) {
            // Calculate number of nights
            $checkinDate = $checkin->checkin_date ? \Carbon\Carbon::parse($checkin->checkin_date) : null;
            $checkoutDate = $checkin->checkout_date ? \Carbon\Carbon::parse($checkin->checkout_date) : null;
            $nights = 1;
            if ($checkinDate && $checkoutDate && $checkoutDate > $checkinDate) {
                $nights = $checkinDate->diffInDays($checkoutDate);
            }
            $checkin->total_payment = ($checkin->rate ?? 0) * ($checkin->number_of_guest ?? 1) * $nights;
        });
    }

    public function folio()
    {
        return $this->hasOne(\App\Models\GuestFolio::class, 'checkin_code', 'checkin_code');
    }

    protected $casts = [
    'checkin_date' => 'datetime',
    'checkout_date' => 'datetime',
];


}
