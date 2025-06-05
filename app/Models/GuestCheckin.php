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
        'modified_by', 'is_active'
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

}
