<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestFolioItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'folio_id',
        'type',
        'description',
        'amount',
        'reference',
        'posted_at',
    ];

    protected $casts = [
        'amount' => 'float',
        'posted_at' => 'datetime',
    ];

    public function folio()
    {
        return $this->belongsTo(GuestFolio::class, 'folio_id');
    }
}
