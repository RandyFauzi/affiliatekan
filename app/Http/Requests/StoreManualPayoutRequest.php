<?php

namespace App\Http\Requests;

use App\Models\Payout;
use Illuminate\Foundation\Http\FormRequest;

class StoreManualPayoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        $authenticatedVendorId = $this->user()?->vendor?->id;
        $requestedPayoutId = $this->route('payoutId');

        if ($authenticatedVendorId === null || $requestedPayoutId === null) {
            return false;
        }

        return Payout::query()
            ->whereKey($requestedPayoutId)
            ->where('vendor_id', $authenticatedVendorId)
            ->exists();
    }

    public function rules(): array
    {
        return [
            'proof_of_transfer_path' => ['required', 'file', 'mimes:jpeg,png,pdf', 'max:2048'],
        ];
    }
}
