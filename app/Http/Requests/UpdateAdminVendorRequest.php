<?php

namespace App\Http\Requests;

use App\Models\Vendor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdminVendorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        /** @var Vendor|null $managedVendor */
        $managedVendor = $this->route('vendor');
        $managedVendorUserId = $managedVendor?->user_id;

        return [
            'editing_vendor_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($managedVendorUserId),
            ],
            'company_name' => ['required', 'string', 'max:255'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'commission_type' => ['required', 'string', Rule::in(['flat', 'percentage'])],
            'commission_value' => ['required', 'numeric', 'min:0'],
            'cookie_duration_days' => ['nullable', 'integer', 'min:1', 'max:365'],
        ];
    }
}
