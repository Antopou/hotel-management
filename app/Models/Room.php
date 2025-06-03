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

    /**
     * Automatically generate a room_code if not provided.
     */
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
     * Get the room type this room belongs to.
     */
    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_code', 'room_type_code');
    }
}
