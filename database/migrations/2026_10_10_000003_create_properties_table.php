<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->enum('property_type', ['house', 'apartment', 'villa', 'floor', 'commercial'])->default('house');
            $table->enum('status', ['available', 'reserved', 'rented'])->default('available');
            $table->decimal('rent_price', 12, 2);
            $table->string('price_currency', 10)->default('USD');
            $table->string('rent_period', 20)->default('monthly');
            $table->unsignedSmallInteger('bedrooms')->default(2);
            $table->unsignedSmallInteger('bathrooms')->default(1);
            $table->unsignedInteger('area_sqm')->default(150);
            $table->string('neighborhood'); // گەڕەک وەک: سەرچنار، تووی مەلیک، بەختیاری، هتد
            $table->string('street_address')->nullable();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->boolean('is_furnished')->default(false);
            $table->integer('floor_number')->nullable();
            $table->boolean('has_parking')->default(false);
            $table->boolean('has_elevator')->default(false);
            $table->boolean('has_generator')->default(true);
            $table->string('primary_image')->nullable();
            $table->json('gallery_images')->nullable();
            $table->json('features')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedBigInteger('views_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
