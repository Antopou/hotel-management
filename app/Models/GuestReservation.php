<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // <-- Add this for soft deletes

class GuestReservation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reservation_code', 'guest_code', 'room_code', 'checkin_date', 'checkout_date',
        'cancelled_date', 'reason', 'rate', 'total_payment', 'payment_method',
        'number_of_guest', 'is_checkin', 'created_by', 'modified_by', 'is_active',
        'status',
    ];

    protected $casts = [
        'checkin_date' => 'datetime',
        'checkout_date' => 'datetime',
        'cancelled_date' => 'datetime',
        'is_checkin' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function guest()
    {
        return $this->belongsTo(Guest::class, 'guest_code', 'guest_code');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_code', 'room_code');
    }

    // Optional: Relationship to room type via room
    public function roomType()
    {
        return $this->hasOneThrough(RoomType::class, Room::class, 'room_code', 'room_type_code', 'room_code', 'room_type_code');
    }
}
