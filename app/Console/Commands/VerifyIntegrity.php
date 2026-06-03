<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class VerifyIntegrity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'security:verify-integrity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify the cryptographic HMAC integrity of conversions at the row level';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting conversion transaction HMAC integrity verification...');

        $conversions = DB::table('conversions')->orderBy('id', 'asc')->get();
        
        if ($conversions->isEmpty()) {
            $this->info('No conversions found to verify.');
            return self::SUCCESS;
        }

        $errors = [];
        $key = config('app.signature_key');

        foreach ($conversions as $conversion) {
            // 1. Re-calculate HMAC signature
            $data = [
                $conversion->vendor_id,
                $conversion->affiliate_id,
                $conversion->vendor_order_id,
                $conversion->product_name ?? '',
                (string)$conversion->sale_amount,
                (string)$conversion->commission_amount,
                $conversion->status,
            ];
            $calculatedSignature = hash_hmac('sha256', implode('|', $data), $key);

            // 2. Verify signature matches
            if ($conversion->signature !== $calculatedSignature) {
                $errors[] = [
                    'id' => $conversion->id,
                    'order_id' => $conversion->vendor_order_id,
                    'error' => 'HMAC signature mismatch (data tampered).',
                    'expected' => $calculatedSignature,
                    'actual' => $conversion->signature,
                ];
            }
        }

        if (!empty($errors)) {
            $this->error('INTEGRITY FAILURE: Tampered database records detected!');
            $this->table(
                ['ID', 'Order ID', 'Error Type', 'Expected Signature', 'Actual Signature'],
                array_map(fn($e) => [
                    $e['id'],
                    $e['order_id'],
                    $e['error'],
                    substr($e['expected'], 0, 16) . '...',
                    substr($e['actual'], 0, 16) . '...'
                ], $errors)
            );
            return self::FAILURE;
        }

        $this->info('INTEGRITY VERIFIED: All ' . $conversions->count() . ' records have valid HMAC signatures.');
        return self::SUCCESS;
    }
}
