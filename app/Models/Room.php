<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_code',
        'name',
        'room_type_code',
        'status',
        'created_by',
        'modified_by',
        'is_active',
        'description', // <-- Add this line
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($room) {
            if (empty($room->room_code)) {
                $room->room_code = (string) Str::uuid();
            }
        });
    }

    /**
     * Always store status as lowercase in DB.
     */
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = strtolower($value);
    }

    // Relationships
    public function reservations()
    {
        return $this->hasMany(GuestReservation::class, 'room_code', 'room_code');
    }

    public function checkins()
    {
        return $this->hasMany(GuestCheckin::class, 'room_code', 'room_code');
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_code', 'room_type_code');
    }

    // Helper: returns GuestCheckin model or null (NOT a relationship)
    public function currentCheckin()
    {
        return $this->checkins()->where('is_checkout', false)->latest('checkin_date')->first();
    }

    public function futureReservations()
    {
        return $this->reservations()->where('checkin_date', '>=', now())->orderBy('checkin_date');
    }

    // Helper: returns the next reservation with status pending or confirmed
    public function nextReservation()
    {
        return $this->reservations()
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('checkin_date')
            ->with('guest')
            ->first();
    }

    // Helper: next available check-in date
    public function nextAvailableDate()
    {
        // Find the latest ongoing or future checkout for this room
        $latestCheckout = $this->checkins()
            ->where('checkout_date', '>=', now())
            ->orderByDesc('checkout_date')
            ->first();

        if ($latestCheckout) {
            $checkout = \Carbon\Carbon::parse($latestCheckout->checkout_date);
            return $checkout->copy()->addMinute(); // first minute after current guest leaves
        }

        // If no active/future checkin, room is available now
        return now();
    }

    public function getStatusColorAttribute()
    {
        return match(strtolower($this->status)) {
            'available' => 'success',
            'occupied' => 'danger',
            'cleaning' => 'warning',
            default => 'secondary',
        };
    }
}
