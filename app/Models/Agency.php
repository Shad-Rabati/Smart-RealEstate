<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Agency extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'owner_name',
        'phone',
        'whatsapp',
        'address',
        'city',
        'description',
        'logo',
        'cover_image',
        'verified',
        'credits',
    ];

    protected function casts(): array
    {
        return [
            'verified' => 'boolean',
            'credits' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    public function creditTransactions(): HasMany
    {
        return $this->hasMany(CreditTransaction::class)->latest();
    }

    /**
     * Check if agency has enough credits to publish a new property.
     */
    public function hasCreditsForProperty(): bool
    {
        $requiredCredits = (int) SystemSetting::get('credits_per_property', 1);
        return $this->credits >= $requiredCredits;
    }

    /**
     * Deduct credits from agency with transaction log.
     */
    public function deductCredits(int $amount, string $reason = '', string $type = 'property_publish'): bool
    {
        if ($this->credits < $amount) {
            return false;
        }

        $this->decrement('credits', $amount);
        $this->refresh();

        CreditTransaction::create([
            'agency_id' => $this->id,
            'amount' => -$amount,
            'balance_after' => $this->credits,
            'type' => $type,
            'description' => $reason,
        ]);

        return true;
    }

    /**
     * Add credits to agency with transaction log.
     */
    public function addCredits(int $amount, string $reason = '', string $type = 'admin_topup'): void
    {
        $this->increment('credits', $amount);
        $this->refresh();

        CreditTransaction::create([
            'agency_id' => $this->id,
            'amount' => $amount,
            'balance_after' => $this->credits,
            'type' => $type,
            'description' => $reason,
        ]);
    }

    public function availableProperties(): HasMany
    {
        return $this->properties()->where('status', 'available');
    }

    public function rentedProperties(): HasMany
    {
        return $this->properties()->where('status', 'rented');
    }

    public function reservedProperties(): HasMany
    {
        return $this->properties()->where('status', 'reserved');
    }

    public function getLogoUrlAttribute(): string
    {
        if ($this->logo) {
            if (Str::startsWith($this->logo, ['http://', 'https://'])) {
                return $this->logo;
            }
            return asset('storage/' . $this->logo);
        }
        return 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=300&q=80';
    }

    public function getWhatsappLinkAttribute(): ?string
    {
        $number = $this->whatsapp ?: $this->phone;
        if (!$number) return null;
        // Clean non-digit characters
        $clean = preg_replace('/[^0-9]/', '', $number);
        if (Str::startsWith($clean, '07')) {
            $clean = '964' . substr($clean, 1);
        }
        return 'https://wa.me/' . $clean;
    }
}
