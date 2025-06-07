<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// If you are using SoftDeletes on your Guests table, uncomment the line below:
// use Illuminate\Database\Eloquent\SoftDeletes;

class Guest extends Model
{
    use HasFactory;
    // If you are using SoftDeletes on your Guests table, uncomment the line below:
    // use SoftDeletes;

    // IMPORTANT: If 'guest_code' is the PRIMARY KEY for your 'guests' table,
    // you must also define these:
    // protected $primaryKey = 'guest_code';
    // public $incrementing = false; // Only if 'guest_code' is not auto-incrementing integer
    // protected $keyType = 'string'; // Only if 'guest_code' is a string (e.g., 'GUEST001')


    protected $fillable = [
        'guest_code',
        'name',
        'gender',
        'date_of_birth',
        'tel',
        'email',
        'created_by',
        'modified_by',
        'is_active',
    ];

    /**
     * Get the reservations for the guest.
     */
    public function reservations()
    {
        // 'foreign_key' on the GuestReservation table (matches this Guest's 'guest_code')
        // 'local_key' on the Guest table (the key this model uses)
        return $this->hasMany(GuestReservation::class, 'guest_code', 'guest_code');
    }

    /**
     * Get the checkins for the guest.
     */
    public function checkins()
    {
        // 'foreign_key' on the GuestCheckin table (matches this Guest's 'guest_code')
        // 'local_key' on the Guest table (the key this model uses)
        return $this->hasMany(GuestCheckin::class, 'guest_code', 'guest_code');
    }

    /**
     * Get the folios for the guest.
     * Assuming GuestFolio also has a 'guest_code' foreign key linking to Guest
     */
    public function folios()
    {
        return $this->hasMany(GuestFolio::class, 'guest_code', 'guest_code');
    }
}