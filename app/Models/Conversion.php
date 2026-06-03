<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Conversion extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'affiliate_id',
        'payout_id',
        'vendor_order_id',
        'product_name',
        'sale_amount',
        'commission_amount',
        'status',
        'signature',
    ];

    protected $casts = [
        'sale_amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (Conversion $conversion): void {
            $conversion->signature = $conversion->calculateSignature();
        });

        static::updating(function (Conversion $conversion): void {
            $conversion->signature = $conversion->calculateSignature();
        });
    }

    public function calculateSignature(): string
    {
        $data = [
            $this->vendor_id,
            $this->affiliate_id,
            $this->vendor_order_id,
            $this->product_name ?? '',
            (string)$this->sale_amount,
            (string)$this->commission_amount,
            $this->status,
        ];
        return hash_hmac('sha256', implode('|', $data), config('app.signature_key'));
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }

    public function payout(): BelongsTo
    {
        return $this->belongsTo(Payout::class);
    }
}
