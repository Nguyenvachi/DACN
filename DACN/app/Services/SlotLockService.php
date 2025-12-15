<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class SlotLockService
{
    public function key(int $bacSiId, string $ngay, string $gio)
    {
        $gioClean = trim($gio);
        return sprintf('slot_lock:bacsi:%s:date:%s:time:%s', $bacSiId, $ngay, $gioClean);
    }

    /**
     * Attempt to acquire lock for a slot. Returns array success/detail
     * @return array
     */
    public function attemptLock(int $bacSiId, string $ngay, string $gio, int $userId, int $ttl = 300)
    {
        $k = $this->key($bacSiId, $ngay, $gio);
        $existing = Cache::get($k);

        $now = Carbon::now();
        if ($existing) {
            // If already exists and is same user, extend
            if (($existing['user_id'] ?? null) === $userId) {
                $newPayload = array_merge($existing, [
                    'locked_at' => $now->toDateTimeString(),
                    'locked_until' => $now->copy()->addSeconds($ttl)->toDateTimeString(),
                ]);
                Cache::put($k, $newPayload, $ttl);
                return ['success' => true, 'extend' => true, 'ttl' => $ttl, 'owner' => $existing['user_id'], 'locked_until' => $newPayload['locked_until']];
            }
            return ['success' => false, 'message' => 'Khung giờ đã được giữ bởi người khác', 'owner' => $existing['user_id'] ?? null, 'until' => $existing['locked_until'] ?? null];
        }

        $payload = [
            'user_id' => $userId,
            'locked_at' => $now->toDateTimeString(),
            'locked_until' => $now->copy()->addSeconds($ttl)->toDateTimeString(),
        ];

        Cache::put($k, $payload, $ttl);
        return ['success' => true, 'ttl' => $ttl, 'owner' => $userId, 'locked_until' => $payload['locked_until']];
    }

    public function getLock(int $bacSiId, string $ngay, string $gio)
    {
        $k = $this->key($bacSiId, $ngay, $gio);
        return Cache::get($k) ?: null;
    }

    public function releaseLock(int $bacSiId, string $ngay, string $gio, int $userId = null)
    {
        $k = $this->key($bacSiId, $ngay, $gio);
        $existing = Cache::get($k);
        if (!$existing) return true; // nothing to do
        if ($userId && ($existing['user_id'] ?? null) !== $userId) {
            return false; // cannot release from others
        }
        Cache::forget($k);
        return true;
    }
}
