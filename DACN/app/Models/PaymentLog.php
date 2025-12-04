<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Parent: app/Models/
 * Child: PaymentLog.php
 * Purpose: Model để ghi audit log cho tất cả request/response thanh toán
 */
class PaymentLog extends Model
{
    protected $fillable = [
        'hoa_don_id',
        'provider',
        'event_type',
        'idempotency_key',
        'transaction_ref',
        'result_code',
        'result_message',
        'payload',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    /**
     * Quan hệ với HoaDon
     */
    public function hoaDon()
    {
        return $this->belongsTo(HoaDon::class);
    }

    /**
     * Helper: Tạo log cho request thanh toán
     */
    public static function logRequest(string $provider, int $hoaDonId, array $payload): self
    {
        return self::create([
            'hoa_don_id' => $hoaDonId,
            'provider' => strtoupper($provider),
            'event_type' => 'request',
            'payload' => $payload,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Helper: Tạo log cho return URL (user quay về)
     */
    public static function logReturn(string $provider, array $payload, ?int $hoaDonId = null): self
    {
        return self::create([
            'hoa_don_id' => $hoaDonId,
            'provider' => strtoupper($provider),
            'event_type' => 'return',
            'transaction_ref' => $payload['vnp_BankTranNo'] ?? $payload['transId'] ?? null,
            'result_code' => $payload['vnp_ResponseCode'] ?? $payload['resultCode'] ?? null,
            'result_message' => $payload['message'] ?? null,
            'payload' => $payload,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Helper: Tạo log cho IPN (webhook từ gateway)
     */
    public static function logIPN(string $provider, array $payload, ?int $hoaDonId = null): self
    {
        return self::create([
            'hoa_don_id' => $hoaDonId,
            'provider' => strtoupper($provider),
            'event_type' => 'ipn',
            'idempotency_key' => $payload['vnp_TxnRef'] ?? $payload['orderId'] ?? null,
            'transaction_ref' => $payload['vnp_BankTranNo'] ?? $payload['transId'] ?? null,
            'result_code' => $payload['vnp_ResponseCode'] ?? $payload['resultCode'] ?? null,
            'result_message' => $payload['message'] ?? null,
            'payload' => $payload,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
