<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo_url', // <-- Add this line
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Accessor for profile photo URL.
     */
    public function getProfilePhotoUrlAttribute($value)
    {
        if ($value) {
            return asset($value);
        }
        return asset('images/default-avatar.png');
    }

    /**
     * Accessor for profile image URL.
     */
    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_image) {
            // If already starts with http or /storage, return as is
            if (str_starts_with($this->profile_image, 'http') || str_starts_with($this->profile_image, '/storage')) {
                return asset($this->profile_image);
            }
            // Otherwise, assume it's in storage
            return asset('storage/' . $this->profile_image);
        }
        return asset('images/default-avatar.png');
    }
}
