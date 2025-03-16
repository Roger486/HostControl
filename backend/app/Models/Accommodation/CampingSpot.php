<?php

namespace App\Models\Accommodation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampingSpot extends Model
{
    use HasFactory;

    protected $fillable = [
        'accommodation_id',
        'area_size_m2',
        'has_electricity',
        'accepts_caravan'
    ];

    protected $casts = [
        'area_size_m2' => 'integer',
        'has_electricity' => 'boolean',
        'accepts_caravan' => 'boolean'
    ];

    /**
     * Get the main accommodation details for this Camping Spot.
     *
     * A Camping Spot belongs to an accommodation.
     */
    public function accommodation(): BelongsTo
    {
        return $this->belongsTo(Accommodation::class, 'accommodation_id');
    }
}
