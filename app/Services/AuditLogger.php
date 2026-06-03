<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Log;

class AuditLogger
{
    /**
     * Log a security or system audit event.
     *
     * @param string $action
     * @param array $payload
     * @param int|null $userId
     * @return AuditLog|null
     */
    public static function log(string $action, array $payload = [], ?int $userId = null): ?AuditLog
    {
        try {
            $user = Auth::user();
            $resolvedUserId = $userId ?? ($user ? $user->id : null);
            
            $sanitizedPayload = self::sanitize($payload);

            return AuditLog::create([
                'user_id' => $resolvedUserId,
                'action' => $action,
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'payload' => $sanitizedPayload,
            ]);
        } catch (\Exception $e) {
            // Keep logging failures safe
            Log::error('Audit logging failed: ' . $e->getMessage(), [
                'action' => $action,
                'exception' => $e
            ]);
            return null;
        }
    }

    /**
     * Sanitize sensitive keys in the payload recursively.
     *
     * @param array $data
     * @return array
     */
    protected static function sanitize(array $data): array
    {
        $sensitiveKeys = [
            'password',
            'password_confirmation',
            'token',
            'secret',
            'api_key',
            'bank_account_number',
            'bank_account_name',
            'proof_of_transfer',
            'proof_of_transfer_path'
        ];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = self::sanitize($value);
            } elseif (in_array(strtolower($key), $sensitiveKeys)) {
                $data[$key] = '[REDACTED]';
            }
        }

        return $data;
    }
}
