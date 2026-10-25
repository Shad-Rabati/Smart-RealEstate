<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Get a setting by key, with optional default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting by key and value.
     */
    public static function set(string $key, mixed $value): static
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Get standard defaults.
     */
    public static function defaultSettings(): array
    {
        return [
            'initial_free_credits' => 2,
            'credits_per_property' => 1,
            'credit_price_usd' => 2,
            'admin_phone' => '07700000000',
            'admin_whatsapp' => '07700000000',
            'fastpay_number' => '07700000000',
            'fib_account' => 'IQ00FIB0000000000000000',
            'payment_instructions' => 'بۆ کڕینی پاکێجی کریدت، دەتوانیت لە ڕێگەی فاستپەی (FastPay) یان ئەپڵیکەیشنی FIB یاخود کاش بڕی پارەکە بنێریت، پاشان وێنەی وەسڵەکە یان ژمارەی مامەڵەکە لە واتسئاپ بۆ سەرپەرشتیار بنێریت تا دەستبەجێ باڵانسی کریدتەکەت بۆ چالاک بکرێت.',
        ];
    }
}
