<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'website_url',
        'api_key',
        'webhook_url',
        'commission_type',
        'commission_value',
        'cookie_duration_days',
    ];

    protected $casts = [
        'api_key' => 'encrypted',
    ];

    protected static function booted(): void
    {
        static::creating(function (Vendor $vendor): void {
            if (empty($vendor->api_key)) {
                $vendor->api_key = self::generateApiKey();
            }
            $vendor->api_key_hash = hash('sha256', $vendor->api_key);
        });

        static::updating(function (Vendor $vendor): void {
            if ($vendor->isDirty('api_key')) {
                $vendor->api_key_hash = hash('sha256', $vendor->api_key);
            }
        });
    }

    public static function generateApiKey(): string
    {
        do {
            $generatedApiKey = 'vnd_' . Str::random(40);
            $hash = hash('sha256', $generatedApiKey);
        } while (self::query()->where('api_key_hash', $hash)->exists());

        return $generatedApiKey;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function clickLogs(): HasMany
    {
        return $this->hasMany(ClickLog::class);
    }

    public function conversions(): HasMany
    {
        return $this->hasMany(Conversion::class);
    }

    public function payouts(): HasMany
    {
        return $this->hasMany(Payout::class);
    }

    public function affiliates()
    {
        return $this->belongsToMany(Affiliate::class, 'affiliate_vendor')
            ->withPivot('agreed_to_terms')
            ->withTimestamps();
    }
}
