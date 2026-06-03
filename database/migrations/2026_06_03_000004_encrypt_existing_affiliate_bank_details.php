<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

return new class extends Migration
{
    public function up(): void
    {
        $affiliates = DB::table('affiliates')->get();
        foreach ($affiliates as $affiliate) {
            $isEncrypted = false;
            if ($affiliate->bank_name) {
                $decoded = json_decode(base64_decode($affiliate->bank_name), true);
                if (is_array($decoded) && isset($decoded['iv'], $decoded['value'], $decoded['mac'])) {
                    $isEncrypted = true;
                }
            }

            if (!$isEncrypted) {
                DB::table('affiliates')->where('id', $affiliate->id)->update([
                    'bank_name' => $affiliate->bank_name ? Crypt::encryptString($affiliate->bank_name) : null,
                    'bank_account_number' => $affiliate->bank_account_number ? Crypt::encryptString($affiliate->bank_account_number) : null,
                    'bank_account_name' => $affiliate->bank_account_name ? Crypt::encryptString($affiliate->bank_account_name) : null,
                ]);
            }
        }
    }

    public function down(): void
    {
        // Decrypt everything back to plain text
        $affiliates = DB::table('affiliates')->get();
        foreach ($affiliates as $affiliate) {
            $decryptedName = null;
            $decryptedNumber = null;
            $decryptedAccountName = null;

            if ($affiliate->bank_name) {
                try {
                    $decryptedName = Crypt::decryptString($affiliate->bank_name);
                } catch (\Exception $e) {
                    $decryptedName = $affiliate->bank_name;
                }
            }
            if ($affiliate->bank_account_number) {
                try {
                    $decryptedNumber = Crypt::decryptString($affiliate->bank_account_number);
                } catch (\Exception $e) {
                    $decryptedNumber = $affiliate->bank_account_number;
                }
            }
            if ($affiliate->bank_account_name) {
                try {
                    $decryptedAccountName = Crypt::decryptString($affiliate->bank_account_name);
                } catch (\Exception $e) {
                    $decryptedAccountName = $affiliate->bank_account_name;
                }
            }

            DB::table('affiliates')->where('id', $affiliate->id)->update([
                'bank_name' => $decryptedName,
                'bank_account_number' => $decryptedNumber,
                'bank_account_name' => $decryptedAccountName,
            ]);
        }
    }
};
