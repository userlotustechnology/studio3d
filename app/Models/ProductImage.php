<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'path',
        'position',
        'is_main',
    ];

    protected $casts = [
        'is_main' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getImageUrlAttribute()
    {
        if (! $this->path) {
            return null;
        }

        if (app()->environment('production')) {
            $minutes = (int) env('S3_URL_EXPIRE_MINUTES', 60);
            try {
                return \Illuminate\Support\Facades\Storage::disk('s3')->temporaryUrl($this->path, now()->addMinutes($minutes));
            } catch (\Throwable $e) {
                return \Illuminate\Support\Facades\Storage::disk('s3')->url($this->path);
            }
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->url($this->path);
    }

    /**
     * Set this image as the main image for the product.
     * This will unset any other main image for the same product.
     */
    public function setAsMain(): void
    {
        // Unset other main images for this product
        self::where('product_id', $this->product_id)
            ->where('id', '!=', $this->id)
            ->update(['is_main' => false]);
        
        // Set this one as main
        $this->update(['is_main' => true]);
    }
}

