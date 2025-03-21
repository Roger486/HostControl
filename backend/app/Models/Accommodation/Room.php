<?php

namespace App\Models\Accommodation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'accommodation_id',
        'bed_amount',
        'has_air_conditioning',
        'has_private_wc'
    ];

    protected $casts = [
        'bed_amount' => 'integer',
        'has_air_conditioning' => 'boolean',
        'has_private_wc' => 'boolean'
    ];

    /**
     * Get the main accommodation details for this Room.
     *
     * A Room belongs to an accommodation.
     */
    public function accommodation(): BelongsTo
    {
        return $this->belongsTo(Accommodation::class, 'accommodation_id');
    }
}
