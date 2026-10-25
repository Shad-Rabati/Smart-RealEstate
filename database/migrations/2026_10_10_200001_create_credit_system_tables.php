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
        // 1. Add credits to agencies table
        if (!Schema::hasColumn('agencies', 'credits')) {
            Schema::table('agencies', function (Blueprint $table) {
                $table->integer('credits')->default(2)->after('verified');
            });
        }

        // 2. Create system settings table
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // 3. Create credit transactions table
        Schema::create('credit_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->constrained('agencies')->onDelete('cascade');
            $table->integer('amount'); // +5, -1, etc.
            $table->integer('balance_after');
            $table->string('type', 40); // initial_gift, admin_topup, admin_deduct, property_publish, refund
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_transactions');
        Schema::dropIfExists('system_settings');

        if (Schema::hasColumn('agencies', 'credits')) {
            Schema::table('agencies', function (Blueprint $table) {
                $table->dropColumn('credits');
            });
        }
    }
};
