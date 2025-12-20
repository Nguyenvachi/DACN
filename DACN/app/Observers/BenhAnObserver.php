<?php

namespace App\Observers;

use App\Models\BenhAn;
use App\Models\BenhAnAudit;

class BenhAnObserver
{
    private const ACTION_MAX_LEN = 100;

    /**
     * Ghi log khi tạo mới bệnh án
     */
    public function created(BenhAn $benhAn): void
    {
        $this->logAudit($benhAn, 'created', null, $benhAn->getAttributes());
    }

    /**
     * Ghi log khi cập nhật bệnh án
     */
    public function updated(BenhAn $benhAn): void
    {
        $oldValues = $benhAn->getOriginal();
        $newValues = $benhAn->getAttributes();

        // Chỉ log các trường thay đổi
        $changes = array_diff_assoc($newValues, $oldValues);

        if (!empty($changes)) {
            $this->logAudit($benhAn, 'updated', $oldValues, $newValues);
        }
    }

    /**
     * Ghi log khi xóa bệnh án
     */
    public function deleted(BenhAn $benhAn): void
    {
        $this->logAudit($benhAn, 'deleted', $benhAn->getAttributes(), null);
    }

    /**
     * Helper: ghi audit log
     */
    private function logAudit(BenhAn $benhAn, string $action, ?array $oldValues, ?array $newValues): void
    {
        $user = auth()->user();
        $request = request();

        $safeAction = mb_substr(trim((string) $action), 0, self::ACTION_MAX_LEN);

        BenhAnAudit::create([
            'benh_an_id' => $benhAn->id,
            'user_id'    => $user?->id,
            'action'     => $safeAction,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
        ]);
    }

    /**
     * BỔ SUNG: Static method để ghi log từ ngoài Observer (cho các action đặc biệt)
     */
    public static function logCustomAction(BenhAn $benhAn, string $action, string $description): void
    {
        $user = auth()->user();
        $request = request();

        $safeAction = mb_substr(trim((string) $action), 0, self::ACTION_MAX_LEN);

        BenhAnAudit::create([
            'benh_an_id' => $benhAn->id,
            'user_id'    => $user?->id,
            'action'     => $safeAction,
            'old_values' => null,
            'new_values' => ['description' => $description],
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
        ]);
    }

    /**
     * BỔ SUNG: Ghi log action đặc biệt kèm old/new values (phục vụ các module con như Theo dõi thai kỳ)
     */
    public static function logCustomActionWithValues(BenhAn $benhAn, string $action, ?array $oldValues, ?array $newValues): void
    {
        $user = auth()->user();
        $request = request();

        $safeAction = mb_substr(trim((string) $action), 0, self::ACTION_MAX_LEN);

        BenhAnAudit::create([
            'benh_an_id' => $benhAn->id,
            'user_id'    => $user?->id,
            'action'     => $safeAction,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
        ]);
    }
}
