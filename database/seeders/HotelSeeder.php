<?php

namespace Database\Seeders;

use App\Models\Hotel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class HotelSeeder extends Seeder
{
    public function run(): void
    {
        $hotels = [
            [
                'name' => 'ئوتێل گراند میلینیۆم سلێمانی (Grand Millennium)',
                'slug' => 'grand-millennium-hotel-sulaimani',
                'stars' => 5,
                'price_per_night' => 140,
                'price_currency' => 'USD',
                'neighborhood' => 'بەختیاری',
                'address' => 'شەقامی بەختیاری، بەرزایی تەلاری سەرچنار',
                'latitude' => 35.5450,
                'longitude' => 45.4120,
                'phone' => '07701234567',
                'whatsapp' => '07701234567',
                'email' => 'info@grandmillennium.test',
                'website' => 'https://millenniumhotels.com',
                'primary_image' => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=1200&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&w=800&q=80',
                ],
                'amenities' => ['wifi', 'pool', 'breakfast', 'gym', 'parking', 'restaurant', 'spa', 'ac', 'room_service', 'elevator'],
                'description' => 'ئوتێلی ٥ ئەستێرەی شاهانە و مۆدێرن لە شاری سلێمانی. خاوەنی دیمەنی پانۆرامایی ٣٦٠ پلەی هەموو شار، چێشتخانەی سووڕاو، مەلەوانگەی گەورەی هاوینە و زستانە، ساونا و سەنتەری تەندروستی سپا.',
                'is_featured' => true,
                'status' => 'active',
            ],
            [
                'name' => 'ئوتێل و سپای تایتانیک (Titanic Hotel & Spa)',
                'slug' => 'titanic-hotel-spa-sulaimani',
                'stars' => 5,
                'price_per_night' => 110,
                'price_currency' => 'USD',
                'neighborhood' => 'گۆیژە',
                'address' => 'بناری چیای گۆیژە، نزیک تونێلی پێشڕەو',
                'latitude' => 35.5720,
                'longitude' => 45.4420,
                'phone' => '07702345678',
                'whatsapp' => '07702345678',
                'email' => 'info@titanichotel.test',
                'website' => 'https://titanichotel.test',
                'primary_image' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1200&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=800&q=80',
                ],
                'amenities' => ['wifi', 'pool', 'breakfast', 'gym', 'parking', 'restaurant', 'spa', 'ac', 'room_service', 'elevator'],
                'description' => 'ئوتێل و سەنتەری نێودەوڵەتی تایتانیک لە بناری چیای گۆیژەی دڵڕفێن، شوێنێکی ئارام و کەشێکی شاهانە بۆ گەشتیاران و مانەوەی پشووەکان.',
                'is_featured' => true,
                'status' => 'active',
            ],
            [
                'name' => 'ئوتێل های کرێست (Highcrest Hotel)',
                'slug' => 'highcrest-hotel-sulaimani',
                'stars' => 5,
                'price_per_night' => 95,
                'price_currency' => 'USD',
                'neighborhood' => 'سەرچنار',
                'address' => 'هاوینەهەواری سەرچنار، شەقامی گەشتیاری',
                'latitude' => 35.5680,
                'longitude' => 45.3890,
                'phone' => '07703456789',
                'whatsapp' => '07703456789',
                'email' => 'contact@highcrest.test',
                'website' => 'https://highcrest.test',
                'primary_image' => 'https://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&w=1200&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&w=800&q=80',
                ],
                'amenities' => ['wifi', 'pool', 'breakfast', 'gym', 'parking', 'restaurant', 'ac', 'room_service', 'elevator'],
                'description' => 'ئوتێلێکی پێنج ئەستێرەی لوکس لە ناو سرووشتی سەوزی سەرچنار. هەوای پاک، باخچەی گەورە و ژووری ڕازاوە بۆ پشوویەکی لەبیرنەکراو.',
                'is_featured' => true,
                'status' => 'active',
            ],
            [
                'name' => 'ئوتێل کۆپتۆرن بەرانان (Copthorne Hotel Baranan)',
                'slug' => 'copthorne-hotel-baranan',
                'stars' => 4,
                'price_per_night' => 75,
                'price_currency' => 'USD',
                'neighborhood' => 'سەرچنار',
                'address' => 'گەڕەکی بەرانان، سەرچنار',
                'latitude' => 35.5630,
                'longitude' => 45.4050,
                'phone' => '07704567890',
                'whatsapp' => '07704567890',
                'email' => 'baranan@copthorne.test',
                'website' => 'https://millenniumhotels.com/copthorne',
                'primary_image' => 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&w=1200&q=80',
                'images' => [],
                'amenities' => ['wifi', 'breakfast', 'parking', 'restaurant', 'gym', 'ac', 'room_service', 'elevator'],
                'description' => 'ئوتێلێکی مۆدێرن و هێمن سەر بە گرووپی جیهانی میلینیۆم لە شاری سلێمانی بە خزمەتگوزاری چوار ئەستێرەی پەسەند و نرخی گونجاو.',
                'is_featured' => false,
                'status' => 'active',
            ],
            [
                'name' => 'ئوتێل شاهان (Shahan Hotel)',
                'slug' => 'shahan-hotel-sulaimani',
                'stars' => 4,
                'price_per_night' => 55,
                'price_currency' => 'USD',
                'neighborhood' => 'تووی مەلیک',
                'address' => 'شەقامی سالم، نزیک مۆزەخانەی نەتەوەیی',
                'latitude' => 35.5560,
                'longitude' => 45.4330,
                'phone' => '07705678901',
                'whatsapp' => '07705678901',
                'email' => 'info@shahanhotel.test',
                'website' => null,
                'primary_image' => 'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=1200&q=80',
                'images' => [],
                'amenities' => ['wifi', 'restaurant', 'ac', 'room_service', 'elevator'],
                'description' => 'ئوتێل شاهان دەکەوێتە چەقی بازرگانی و گەشتیاری شاری سلێمانی بەردەستە بە ژووری تاک و دووانی و خێزانی، نزیک لە بازاڕی دارتاشەکان و باخی گشتی.',
                'is_featured' => false,
                'status' => 'active',
            ],
            [
                'name' => 'ئوتێل دالاهۆ (Dalaho Hotel)',
                'slug' => 'dalaho-hotel-sulaimani',
                'stars' => 3,
                'price_per_night' => 35,
                'price_currency' => 'USD',
                'neighborhood' => 'زەرگەتە',
                'address' => 'نزیک مەیدانی ئاسنگەران، زەرگەتە',
                'latitude' => 35.5480,
                'longitude' => 45.4270,
                'phone' => '07706789012',
                'whatsapp' => '07706789012',
                'email' => null,
                'website' => null,
                'primary_image' => 'https://images.unsplash.com/photo-1591088398332-8a7791972843?auto=format&fit=crop&w=1200&q=80',
                'images' => [],
                'amenities' => ['wifi', 'ac', 'elevator'],
                'description' => 'ئوتێلێکی خاوێن و ئابووری بۆ مانەوەیەکی ئاسوودە و بێ کێشە بە نرخی گونجاو.',
                'is_featured' => false,
                'status' => 'active',
            ],
        ];

        foreach ($hotels as $data) {
            Hotel::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}
