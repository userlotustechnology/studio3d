<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'name',
        'description',
        'image',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // append computed URL attribute
    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if (! $this->image) {
            return null;
        }

        if (app()->environment('production')) {
            $minutes = (int) env('S3_URL_EXPIRE_MINUTES', 60);
            try {
                return \Illuminate\Support\Facades\Storage::disk('s3')->temporaryUrl($this->image, now()->addMinutes($minutes));
            } catch (\Throwable $e) {
                // fallback to public url if temporaryUrl not supported or fails
                return \Illuminate\Support\Facades\Storage::disk('s3')->url($this->image);
            }
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->url($this->image);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
