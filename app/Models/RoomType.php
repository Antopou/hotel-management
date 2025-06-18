<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class RoomType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'room_type_code',
        'name',
        'description',
        'price_per_night',
        'max_occupancy',
        'image',
        'created_by',
        'modified_by',
        'is_active',
        // New amenity fields
        'has_wifi',
        'has_tv',
        'has_ac',
        'has_breakfast',
        'has_parking',
        // Add these:
        'size',
        'bed_type',
    ];

    /**
     * Automatically generate a room_type_code if not provided.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($roomType) {
            if (empty($roomType->room_type_code)) {
                $roomType->room_type_code = (string) Str::uuid();
            }
        });
    }

    public function rooms()
    {
        return $this->hasMany(Room::class, 'room_type_code', 'room_type_code');
    }
}
