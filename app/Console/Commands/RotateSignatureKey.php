<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Conversion;

class RotateSignatureKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'security:rotate-key {--old-key= : The old signature key used to verify existing signatures}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rotate conversion signature HMAC key and re-sign all transaction records';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $oldKey = $this->option('old-key');

        if (blank($oldKey)) {
            $this->error('The --old-key option is required to verify the integrity of existing signatures before rotation.');
            return self::FAILURE;
        }

        $newKey = config('app.signature_key');

        if ($oldKey === $newKey) {
            $this->error('The old key is identical to the current active signature key. Rotation is unnecessary.');
            return self::FAILURE;
        }

        $this->info('Starting conversion signature key rotation...');

        $conversions = Conversion::orderBy('id', 'asc')->get();

        if ($conversions->isEmpty()) {
            $this->info('No conversions found to rotate.');
            return self::SUCCESS;
        }

        $this->info('Verifying existing signatures with the old key first...');
        $errors = [];

        foreach ($conversions as $conversion) {
            $data = [
                $conversion->vendor_id,
                $conversion->affiliate_id,
                $conversion->vendor_order_id,
                $conversion->product_name ?? '',
                (string)$conversion->sale_amount,
                (string)$conversion->commission_amount,
                $conversion->status,
            ];
            $calculatedSignature = hash_hmac('sha256', implode('|', $data), $oldKey);

            if ($conversion->signature !== $calculatedSignature) {
                $errors[] = [
                    'id' => $conversion->id,
                    'order_id' => $conversion->vendor_order_id,
                    'expected' => $calculatedSignature,
                    'actual' => $conversion->signature,
                ];
            }
        }

        if (!empty($errors)) {
            $this->error('INTEGRITY FAILURE: Cannot rotate key because existing data is tampered or the old key is incorrect!');
            $this->table(
                ['ID', 'Order ID', 'Expected Signature (Old Key)', 'Actual Signature'],
                array_map(fn($e) => [
                    $e['id'],
                    $e['order_id'],
                    substr($e['expected'], 0, 16) . '...',
                    substr($e['actual'], 0, 16) . '...'
                ], $errors)
            );
            return self::FAILURE;
        }

        $this->info('Verification successful. Re-signing conversions with the new key...');

        try {
            DB::transaction(function () use ($conversions, $newKey) {
                foreach ($conversions as $conversion) {
                    $data = [
                        $conversion->vendor_id,
                        $conversion->affiliate_id,
                        $conversion->vendor_order_id,
                        $conversion->product_name ?? '',
                        (string)$conversion->sale_amount,
                        (string)$conversion->commission_amount,
                        $conversion->status,
                    ];
                    $newSignature = hash_hmac('sha256', implode('|', $data), $newKey);

                    DB::table('conversions')
                        ->where('id', $conversion->id)
                        ->update(['signature' => $newSignature]);
                }
            });

            $this->info('SUCCESS: Key rotation completed. ' . $conversions->count() . ' records re-signed successfully.');
            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('ERROR: Rotation transaction failed: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
