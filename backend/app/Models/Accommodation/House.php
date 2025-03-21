<?php

namespace App\Models\Accommodation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class House extends Model
{
    use HasFactory;

    protected $fillable = [
        'accommodation_id',
        'bed_amount',
        'room_amount',
        'has_air_conditioning'
    ];

    protected $casts = [
        'bed_amount' => 'integer',
        'room_amount' => 'integer',
        'has_air_conditioning' => 'boolean'
    ];

    /**
     * Get the main accommodation details for this House.
     *
     * A House belongs to an accommodation.
     */
    public function accommodation(): BelongsTo
    {
        return $this->belongsTo(Accommodation::class, 'accommodation_id');
    }
}
