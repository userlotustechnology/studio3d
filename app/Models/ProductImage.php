<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'path',
        'position',
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
}

