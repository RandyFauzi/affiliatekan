<?php

namespace App\Http\Requests;

use App\Models\Affiliate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdminAffiliateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        /** @var Affiliate|null $managedAffiliate */
        $managedAffiliate = $this->route('affiliate');
        $managedAffiliateUserId = $managedAffiliate?->user_id;

        return [
            'editing_affiliate_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($managedAffiliateUserId),
            ],
            'referral_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('affiliates', 'referral_code')->ignore($managedAffiliate?->id),
            ],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'bank_account_number' => ['nullable', 'string', 'max:255'],
            'bank_account_name' => ['nullable', 'string', 'max:255'],
        ];
    }
}
