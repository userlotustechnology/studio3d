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

    public function getImageUrlAttribute()
    {
        // Prefer legacy `image` column if set (avoids accidental DB access in lightweight contexts/tests).
        if ($this->image) {
            $path = $this->image;
        } elseif ($this->relationLoaded('images')) {
            $path = $this->images->first()?->path;
        } else {
            // finally, check DB for images when absolutely necessary
            $path = $this->images()->exists() ? $this->images()->first()->path : null;
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
