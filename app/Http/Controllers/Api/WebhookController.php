<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CommissionCalculator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WebhookController extends Controller
{
    public function __construct(
        private readonly CommissionCalculator $commissionCalculator
    ) {
    }

    public function store(Request $request): JsonResponse
    {
        $normalizedPayload = [
            'affiliate_code' => $request->input('affiliate_code', $request->input('ref_code')),
            'vendor_order_id' => $request->input('vendor_order_id', $request->input('order_id')),
            'sale_amount' => $request->input('sale_amount', $request->input('amount')),
            'commission_amount' => $request->input('commission_amount'),
            'product_name' => $request->input('product_name', $request->input('product')),
        ];

        $validatedPayload = Validator::make($normalizedPayload, [
            'affiliate_code' => ['required', 'string'],
            'vendor_order_id' => ['required', 'string', 'max:255'],
            'sale_amount' => ['required', 'numeric'],
            'commission_amount' => ['nullable', 'numeric', 'min:0'],
            'product_name' => ['nullable', 'string', 'max:255'],
        ])->validate();

        $conversion = $this->commissionCalculator->processConversion(
            (int) $request->attributes->get('vendor_id'),
            $validatedPayload['affiliate_code'],
            $validatedPayload['vendor_order_id'],
            $validatedPayload['sale_amount'],
            $validatedPayload['commission_amount'] ?? null,
            $validatedPayload['product_name'] ?? null
        );

        return response()->json([
            'success' => true,
            'message' => 'Conversion processed successfully.',
            'data' => [
                'id' => $conversion->id,
                'vendor_id' => $conversion->vendor_id,
                'affiliate_id' => $conversion->affiliate_id,
                'vendor_order_id' => $conversion->vendor_order_id,
                'product_name' => $conversion->product_name,
                'sale_amount' => $conversion->sale_amount,
                'commission_amount' => $conversion->commission_amount,
                'status' => $conversion->status,
            ],
        ], 201);
    }
}
