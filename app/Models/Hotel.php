<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'stars',
        'price_per_night',
        'price_currency',
        'neighborhood',
        'address',
        'latitude',
        'longitude',
        'phone',
        'whatsapp',
        'email',
        'website',
        'primary_image',
        'images',
        'amenities',
        'description',
        'is_featured',
        'status',
    ];

    protected $casts = [
        'stars' => 'integer',
        'price_per_night' => 'float',
        'latitude' => 'float',
        'longitude' => 'float',
        'images' => 'array',
        'amenities' => 'array',
        'is_featured' => 'boolean',
    ];

    public static array $amenityDefinitions = [
        'wifi' => ['label' => 'وایفای خێرا', 'icon' => 'fa-wifi'],
        'pool' => ['label' => 'مەلەوانگە', 'icon' => 'fa-person-swimming'],
        'breakfast' => ['label' => 'نانی بەیانی خۆڕایی', 'icon' => 'fa-utensils'],
        'gym' => ['label' => 'هۆڵی لەشجوانی (جیم)', 'icon' => 'fa-dumbbell'],
        'parking' => ['label' => 'پارکینی پارێزراو', 'icon' => 'fa-square-parking'],
        'restaurant' => ['label' => 'چێشتخانە و کافێ', 'icon' => 'fa-bowl-food'],
        'spa' => ['label' => 'ساونا و سپا', 'icon' => 'fa-spa'],
        'ac' => ['label' => 'فێنککەرەوە و سپلیت', 'icon' => 'fa-snowflake'],
        'room_service' => ['label' => 'خزمەتگوزاری ژوور (٢٤ کاتژمێر)', 'icon' => 'fa-bell-concierge'],
        'elevator' => ['label' => 'ئەسانسۆر', 'icon' => 'fa-elevator'],
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getPrimaryImageUrlAttribute(): string
    {
        if (!empty($this->primary_image)) {
            if (str_starts_with($this->primary_image, 'http://') || str_starts_with($this->primary_image, 'https://')) {
                return $this->primary_image;
            }
            return asset('storage/' . $this->primary_image);
        }

        // Luxury fallback image
        return 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1200&q=80';
    }

    public function getCleanWhatsappAttribute(): ?string
    {
        if (empty($this->whatsapp)) {
            return null;
        }

        $clean = preg_replace('/[^0-9]/', '', $this->whatsapp);
        if (str_starts_with($clean, '07')) {
            $clean = '964' . substr($clean, 1);
        }
        return $clean;
    }

    /**
     * Calculate direct distance to given coordinates in kilometers (Haversine in PHP)
     */
    public function distanceTo(float $latitude, float $longitude): float
    {
        if (empty($this->latitude) || empty($this->longitude)) {
            return 9999.0;
        }

        $earthRadius = 6371; // km

        $dLat = deg2rad($this->latitude - $latitude);
        $dLon = deg2rad($this->longitude - $longitude);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($latitude)) * cos(deg2rad($this->latitude)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return round($earthRadius * $c, 2);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($hotel) {
            if (empty($hotel->slug)) {
                $hotel->slug = Str::slug($hotel->name) . '-' . Str::random(5);
            }
        });
    }
}
