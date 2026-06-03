<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payout extends Model
{
    use HasFactory;

    protected $fillable = [
        'affiliate_id',
        'vendor_id',
        'amount',
        'status',
        'proof_of_transfer_path',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function conversions(): HasMany
    {
        return $this->hasMany(Conversion::class);
    }
}
