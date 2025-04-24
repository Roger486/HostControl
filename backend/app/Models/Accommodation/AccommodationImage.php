<?php

namespace App\Models\Accommodation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccommodationImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'accommodation_id',
        'image_path'
    ];

    protected $appends = ['url'];

    public function getUrlAttribute()
    {

        // Starting demo images
        if (str_starts_with($this->image_path, 'demo-images/')) {
            return asset($this->image_path);
        }

        // Images uploaded in production
        return asset('storage/' . $this->image_path);
    }

    /**
     * Get the main accommodation details for this image.
     *
     * An image belongs to an accommodation.
     */
    public function accommodation(): BelongsTo
    {
        return $this->belongsTo(Accommodation::class, 'accommodation_id');
    }
}
