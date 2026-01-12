<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'category_id',
        'sku',
        'type',
        'stock',
        'is_active',
        'product_url',
        'instagram_url',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'stock' => 'integer',
        'type' => 'string',
    ];

    // append computed URL attribute
    protected $appends = ['image_url'];

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('position')->orderBy('id');
    }

    /**
     * Get the main image for the product.
     */
    public function mainImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_main', true);
    }

    /**
     * Get all images except the main one (for gallery display).
     */
    public function galleryImages()
    {
        return $this->hasMany(ProductImage::class)->where('is_main', false)->orderBy('position')->orderBy('id');
    }

    public function getImageUrlAttribute()
    {
        // Prefer legacy `image` column if set (avoids accidental DB access in lightweight contexts/tests).
        if ($this->image) {
            $path = $this->image;
        } elseif ($this->relationLoaded('mainImage') && $this->mainImage) {
            // Prioritize main image if loaded
            $path = $this->mainImage->path;
        } elseif ($this->relationLoaded('images')) {
            // Fall back to first image marked as main, or just first image
            $mainImg = $this->images->firstWhere('is_main', true);
            $path = $mainImg ? $mainImg->path : $this->images->first()?->path;
        } else {
            // Query for main image first, then any image
            $mainImg = $this->mainImage()->first();
            if ($mainImg) {
                $path = $mainImg->path;
            } else {
                $path = $this->images()->first()?->path;
            }
        }

        if (! $path) {
            return null;
        }

        if (app()->environment('production')) {
            $minutes = (int) env('S3_URL_EXPIRE_MINUTES', 60);
            try {
                return \Illuminate\Support\Facades\Storage::disk('s3')->temporaryUrl($path, now()->addMinutes($minutes));
            } catch (\Throwable $e) {
                // fallback to public url if temporaryUrl not supported or fails
                return \Illuminate\Support\Facades\Storage::disk('s3')->url($path);
            }
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->url($path);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
