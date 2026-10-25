<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedTinyInteger('stars')->default(3); // 1 to 5 stars
            $table->decimal('price_per_night', 10, 2);
            $table->string('price_currency', 10)->default('USD');
            $table->string('neighborhood');
            $table->string('address')->nullable();
            $table->decimal('latitude', 10, 7)->default(35.5558);
            $table->decimal('longitude', 10, 7)->default(45.4351);
            $table->string('phone');
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('primary_image')->nullable();
            $table->json('images')->nullable();
            $table->json('amenities')->nullable(); // e.g. wifi, pool, breakfast, gym, parking, etc.
            $table->text('description')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('status', 20)->default('active'); // active, inactive
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
