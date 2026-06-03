<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Affiliate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'referral_code',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
    ];

    protected $casts = [
        'bank_name' => 'encrypted',
        'bank_account_number' => 'encrypted',
        'bank_account_name' => 'encrypted',
    ];

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

    public function vendors()
    {
        return $this->belongsToMany(Vendor::class, 'affiliate_vendor')
            ->withPivot('agreed_to_terms')
            ->withTimestamps();
    }
}
