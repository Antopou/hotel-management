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

    // Helper: returns next confirmed future reservation (or null)
    public function nextReservation()
    {
        return $this->reservations()
            ->where('status', 'confirmed')
            ->where('checkin_date', '>=', now())
            ->orderBy('checkin_date')
            ->with('guest')
            ->first();
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
