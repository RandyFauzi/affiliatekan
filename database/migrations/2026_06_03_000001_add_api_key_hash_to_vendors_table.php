<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

return new class extends Migration
{
    public function up(): void
    {
        // Try to drop the unique constraint safely
        try {
            Schema::table('vendors', function (Blueprint $table) {
                $table->dropUnique('vendors_api_key_unique');
            });
        } catch (\Exception $e) {
            // Already dropped or doesn't exist
        }

        // Change column length and add api_key_hash if it doesn't exist
        Schema::table('vendors', function (Blueprint $table) {
            $table->string('api_key', 500)->change();
            if (!Schema::hasColumn('vendors', 'api_key_hash')) {
                $table->string('api_key_hash')->nullable()->unique()->after('api_key');
            }
        });

        // Migrate and encrypt existing api keys
        $vendors = DB::table('vendors')->get();
        foreach ($vendors as $vendor) {
            if ($vendor->api_key) {
                // If it is already encrypted, skip encrypting again.
                $isEncrypted = false;
                $decoded = json_decode(base64_decode($vendor->api_key), true);
                if (is_array($decoded) && isset($decoded['iv'], $decoded['value'], $decoded['mac'])) {
                    $isEncrypted = true;
                }

                $rawKey = $vendor->api_key;
                if ($isEncrypted) {
                    try {
                        $rawKey = Crypt::decryptString($vendor->api_key);
                    } catch (\Exception $e) {
                        // Keep as is
                    }
                }

                $encryptedKey = Crypt::encryptString($rawKey);
                $hash = hash('sha256', $rawKey);

                DB::table('vendors')->where('id', $vendor->id)->update([
                    'api_key' => $encryptedKey,
                    'api_key_hash' => $hash,
                ]);
            }
        }
    }

    public function down(): void
    {
        // To roll back, we would decrypt the keys, restore unique constraint and reset length
        $vendors = DB::table('vendors')->get();
        foreach ($vendors as $vendor) {
            if ($vendor->api_key) {
                try {
                    $rawKey = Crypt::decryptString($vendor->api_key);
                    DB::table('vendors')->where('id', $vendor->id)->update([
                        'api_key' => $rawKey,
                    ]);
                } catch (\Exception $e) {
                    // Keep as is
                }
            }
        }

        Schema::table('vendors', function (Blueprint $table) {
            if (Schema::hasColumn('vendors', 'api_key_hash')) {
                $table->dropColumn('api_key_hash');
            }
            $table->string('api_key', 255)->change();
            $table->unique('api_key', 'vendors_api_key_unique');
        });
    }
};
