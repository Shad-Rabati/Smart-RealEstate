<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_id',
        'title',
        'slug',
        'property_type',
        'status',
        'rent_price',
        'price_currency',
        'rent_period',
        'bedrooms',
        'bathrooms',
        'area_sqm',
        'neighborhood',
        'street_address',
        'latitude',
        'longitude',
        'is_furnished',
        'floor_number',
        'has_parking',
        'has_elevator',
        'has_generator',
        'primary_image',
        'gallery_images',
        'features',
        'description',
        'video_url',
        'is_featured',
        'featured_at',
        'views_count',
    ];

    protected function casts(): array
    {
        return [
            'rent_price' => 'decimal:2',
            'latitude' => 'float',
            'longitude' => 'float',
            'bedrooms' => 'integer',
            'bathrooms' => 'integer',
            'area_sqm' => 'integer',
            'is_furnished' => 'boolean',
            'has_parking' => 'boolean',
            'has_elevator' => 'boolean',
            'has_generator' => 'boolean',
            'is_featured' => 'boolean',
            'featured_at' => 'datetime',
            'gallery_images' => 'array',
            'features' => 'array',
            'views_count' => 'integer',
        ];
    }

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class);
    }

    // Scopes
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('status', 'available');
    }

    public function scopeRented(Builder $query): Builder
    {
        return $query->where('status', 'rented');
    }

    public function scopeReserved(Builder $query): Builder
    {
        return $query->where('status', 'reserved');
    }

    /**
     * Scope query to calculate distance in KM from given lat, lng
     */
    public function scopeWithDistance(Builder $query, float $lat, float $lng): Builder
    {
        // Haversine formula approximation in SQLite & MySQL:
        // 6371 * acos(cos(radians(lat)) * cos(radians(latitude)) * cos(radians(longitude) - radians(lng)) + sin(radians(lat)) * sin(radians(latitude)))
        // Since SQLite by default doesn't have math functions like radians/acos built in, we can either calculate in PHP or use pythagorean approximation for small city distance:
        // 1 deg lat ~= 111 km, 1 deg lng ~= 111 * cos(lat) ~= 111 * 0.813 ~= 90.3 km in Sulaymaniyah (lat ~35.5)
        $latDist = "(latitude - {$lat}) * 111";
        $lngDist = "(longitude - {$lng}) * 90.3";
        return $query->selectRaw("*, sqrt(({$latDist}) * ({$latDist}) + ({$lngDist}) * ({$lngDist})) as distance_km");
    }

    // Kurdish label helpers
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'available' => __('بەردەستە (بەکرێ نەگیراوە)'),
            'rented' => __('بەکرێ گیراوە'),
            'reserved' => __('لە دانوستاندایە / لە گێراندا'),
            default => __('نادیار'),
        };
    }

    public function getStatusColorNameAttribute(): string
    {
        return match ($this->status) {
            'available' => 'سەوز',
            'rented' => 'سور',
            'reserved' => 'رەساسی',
            default => 'شین',
        };
    }

    public function getStatusHexAttribute(): string
    {
        return match ($this->status) {
            'available' => '#10b981', // Emerald green
            'rented' => '#ef4444',    // Bright red
            'reserved' => '#64748b',  // Slate gray
            default => '#3b82f6',
        };
    }

    public function getStatusBadgeHtmlAttribute(): string
    {
        return match ($this->status) {
            'available' => '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span> ' . __('بەردەستە (سەوز)') . '</span>',
            'rented' => '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-300"><span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span> ' . __('بەکرێ گیراوە (سور)') . '</span>',
            'reserved' => '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-300"><span class="w-2.5 h-2.5 rounded-full bg-slate-500"></span> ' . __('لە دانوستاندا (رەساسی)') . '</span>',
            default => '<span class="px-2 py-1 rounded text-xs bg-gray-100">' . __('نادیار') . '</span>',
        };
    }

    public function getPropertyTypeLabelAttribute(): string
    {
        return match ($this->property_type) {
            'house' => __('خانوو'),
            'apartment' => __('شوقە'),
            'villa' => __('ڤێلا'),
            'floor' => __('نهۆم'),
            'commercial' => __('شوێنی بازرگانی / دوکان'),
            default => $this->property_type,
        };
    }

    public function getFormattedPriceAttribute(): string
    {
        $symbol = $this->price_currency === 'USD' ? '$' : __('د.ع');
        $formattedNum = number_format((float) $this->rent_price, 0);
        $period = __('مانگانە');
        return "{$formattedNum} {$symbol} / {$period}";
    }

    public function getPrimaryImageUrlAttribute(): string
    {
        if ($this->primary_image) {
            if (Str::startsWith($this->primary_image, ['http://', 'https://'])) {
                return $this->primary_image;
            }
            return asset('storage/' . $this->primary_image);
        }
        return 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=800&q=80';
    }

    /**
     * Calculate direct distance to given coordinates in kilometers (Haversine in PHP)
     */
    public function distanceTo(float $latitude, float $longitude): float
    {
        $earthRadius = 6371; // km

        $dLat = deg2rad($this->latitude - $latitude);
        $dLon = deg2rad($this->longitude - $longitude);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($latitude)) * cos(deg2rad($this->latitude)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return round($earthRadius * $c, 2);
    }

    public function getHasVideoAttribute(): bool
    {
        return !empty($this->video_url);
    }

    public function getVideoEmbedUrlAttribute(): ?string
    {
        if (empty($this->video_url)) {
            return null;
        }

        $url = trim($this->video_url);

        // YouTube Shorts: https://www.youtube.com/shorts/VIDEO_ID
        if (preg_match('/(?:youtube\.com\/shorts\/)([a-zA-Z0-9_-]+)/i', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1] . '?rel=0&modestbranding=1';
        }

        // Standard YouTube / youtu.be: https://www.youtube.com/watch?v=VIDEO_ID
        if (preg_match('/(?:youtube\.com\/(?:watch\?.*v=|embed\/|v\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/i', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1] . '?rel=0&modestbranding=1';
        }

        return null;
    }

    public function getVideoTypeAttribute(): string
    {
        if (empty($this->video_url)) return 'none';
        $url = strtolower($this->video_url);
        if (str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be')) return 'youtube';
        if (str_contains($url, 'instagram.com')) return 'instagram';
        if (str_contains($url, 'tiktok.com')) return 'tiktok';
        return 'other';
    }
}
