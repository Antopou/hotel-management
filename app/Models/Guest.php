<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;

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
}
