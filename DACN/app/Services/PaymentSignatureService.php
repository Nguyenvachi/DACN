<?php

namespace App\Services;

/**
 * Parent: app/Services/
 * Child: PaymentSignatureService.php
 * Purpose: Service để tính toán và verify HMAC signature cho VNPay và MoMo
 */
class PaymentSignatureService
{
    /**
     * Tạo chữ ký VNPay từ input data
     */
    public static function createVnpaySignature(array $inputData): string
    {
        $vnp_HashSecret = config('vnpay.hash_secret');
        
        // Loại bỏ vnp_SecureHash nếu có
        unset($inputData['vnp_SecureHash']);
        
        // Sort theo key
        ksort($inputData);
        
        // Tạo hash data
        $hashData = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }
        
        return hash_hmac('sha512', $hashData, $vnp_HashSecret);
    }

    /**
     * Verify chữ ký VNPay
     */
    public static function verifyVnpaySignature(array $inputData, string $providedSignature): bool
    {
        $calculatedSignature = self::createVnpaySignature($inputData);
        return $calculatedSignature === $providedSignature;
    }

    /**
     * Tạo query string cho VNPay payment URL
     */
    public static function buildVnpayUrl(array $inputData): string
    {
        ksort($inputData);
        $query = "";
        foreach ($inputData as $key => $value) {
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        
        $vnp_Url = config('vnpay.url') . "?" . $query;
        $signature = self::createVnpaySignature($inputData);
        $vnp_Url .= 'vnp_SecureHash=' . $signature;
        
        return $vnp_Url;
    }

    /**
     * Tạo chữ ký MoMo từ raw hash string
     */
    public static function createMomoSignature(string $rawHash): string
    {
        $secretKey = config('momo.secret_key');
        return hash_hmac("sha256", $rawHash, $secretKey);
    }

    /**
     * Tạo raw hash string cho MoMo request
     */
    public static function buildMomoRequestHash(array $data): string
    {
        return "accessKey=" . config('momo.access_key') .
            "&amount=" . $data['amount'] .
            "&extraData=" . $data['extraData'] .
            "&ipnUrl=" . $data['ipnUrl'] .
            "&orderId=" . $data['orderId'] .
            "&orderInfo=" . $data['orderInfo'] .
            "&partnerCode=" . $data['partnerCode'] .
            "&redirectUrl=" . $data['redirectUrl'] .
            "&requestId=" . $data['requestId'] .
            "&requestType=" . $data['requestType'];
    }

    /**
     * Tạo raw hash string cho MoMo IPN/Return verification
     */
    public static function buildMomoResponseHash(array $data): string
    {
        return "accessKey=" . config('momo.access_key') .
            "&amount=" . ($data['amount'] ?? '') .
            "&extraData=" . ($data['extraData'] ?? '') .
            "&message=" . ($data['message'] ?? '') .
            "&orderId=" . ($data['orderId'] ?? '') .
            "&orderInfo=" . ($data['orderInfo'] ?? '') .
            "&orderType=" . ($data['orderType'] ?? '') .
            "&partnerCode=" . ($data['partnerCode'] ?? '') .
            "&payType=" . ($data['payType'] ?? '') .
            "&requestId=" . ($data['requestId'] ?? '') .
            "&responseTime=" . ($data['responseTime'] ?? '') .
            "&resultCode=" . ($data['resultCode'] ?? '') .
            "&transId=" . ($data['transId'] ?? '');
    }

    /**
     * Verify chữ ký MoMo
     */
    public static function verifyMomoSignature(array $data, string $providedSignature): bool
    {
        $rawHash = self::buildMomoResponseHash($data);
        $calculatedSignature = self::createMomoSignature($rawHash);
        return $calculatedSignature === $providedSignature;
    }
}
