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
        'payable_type',
        'payable_id',
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
     * Quan hệ polymorphic với payable (HoaDon hoặc DonHang)
     */
    public function payable()
    {
        return $this->morphTo();
    }

    /**
     * Helper: Tạo log cho request thanh toán
     */
    public static function logRequest(string $provider, string|int $payableId, array $payload, ?string $payableType = null): self
    {
        $data = [
            'provider' => strtoupper($provider),
            'event_type' => 'request',
            'payload' => $payload,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ];

        // Xử lý payable
        if ($payableType) {
            $data['payable_type'] = $payableType;
            $data['payable_id'] = $payableId;
        } else {
            // Backward compatibility: nếu không có payable_type, coi như hoa_don_id
            $data['hoa_don_id'] = is_int($payableId) ? $payableId : null;
        }

        return self::create($data);
    }

    /**
     * Helper: Tạo log cho return URL (user quay về)
     */
    public static function logReturn(string $provider, array $payload, string|int|null $payableId = null, ?string $payableType = null): self
    {
        $data = [
            'provider' => strtoupper($provider),
            'event_type' => 'return',
            'transaction_ref' => $payload['vnp_BankTranNo'] ?? $payload['transId'] ?? null,
            'result_code' => $payload['vnp_ResponseCode'] ?? $payload['resultCode'] ?? null,
            'result_message' => $payload['message'] ?? null,
            'payload' => $payload,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ];

        // Xử lý payable
        if ($payableType && $payableId) {
            $data['payable_type'] = $payableType;
            $data['payable_id'] = $payableId;
        } elseif (is_int($payableId)) {
            $data['hoa_don_id'] = $payableId;
        }

        return self::create($data);
    }

    /**
     * Helper: Tạo log cho IPN (webhook từ gateway)
     */
    public static function logIPN(string $provider, array $payload, string|int|null $payableId = null, ?string $payableType = null): self
    {
        $data = [
            'provider' => strtoupper($provider),
            'event_type' => 'ipn',
            'idempotency_key' => $payload['vnp_TxnRef'] ?? $payload['orderId'] ?? null,
            'transaction_ref' => $payload['vnp_BankTranNo'] ?? $payload['transId'] ?? null,
            'result_code' => $payload['vnp_ResponseCode'] ?? $payload['resultCode'] ?? null,
            'result_message' => $payload['message'] ?? null,
            'payload' => $payload,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ];

        // Xử lý payable
        if ($payableType && $payableId) {
            $data['payable_type'] = $payableType;
            $data['payable_id'] = $payableId;
        } elseif (is_int($payableId)) {
            $data['hoa_don_id'] = $payableId;
        }

        return self::create($data);
    }
}
