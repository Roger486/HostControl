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

    /**
     * Add the 'url' attribute to the model's JSON output.
     */
    protected $appends = ['url'];

    /**
     * Accessor for the image URL.
     *
     * If the image is in the demo folder, use public path.
     * Otherwise, assume it's stored in 'public' disk (storage/app/public).
     *
     * @return string
     */
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
     * The accommodation that this image belongs to.
     *
     * @return BelongsTo
     */
    public function accommodation(): BelongsTo
    {
        return $this->belongsTo(Accommodation::class, 'accommodation_id');
    }
}
