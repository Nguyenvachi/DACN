<?php

namespace App\Observers;

use App\Models\BenhAn;
use App\Models\TheoDoiThaiKy;

class TheoDoiThaiKyObserver
{
    public function created(TheoDoiThaiKy $record): void
    {
        $benhAn = $this->resolveBenhAn($record);
        if (! $benhAn) {
            return;
        }

        $newValues = $this->buildAuditValues($record);
        BenhAnObserver::logCustomActionWithValues(
            $benhAn,
            'pregnancy_tracking_created',
            null,
            $newValues
        );
    }

    public function updated(TheoDoiThaiKy $record): void
    {
        $benhAn = $this->resolveBenhAn($record);
        if (! $benhAn) {
            return;
        }

        $oldValues = $record->getOriginal();
        $newValues = $record->getAttributes();

        $changes = array_diff_assoc($newValues, $oldValues);
        if (empty($changes)) {
            return;
        }

        $auditOld = [];
        $auditNew = [];

        foreach (array_keys($changes) as $key) {
            $auditOld[$key] = $oldValues[$key] ?? null;
            $auditNew[$key] = $newValues[$key] ?? null;
        }

        $auditOld = array_merge(['theo_doi_thai_ky_id' => $record->id], $auditOld);
        $auditNew = array_merge(['theo_doi_thai_ky_id' => $record->id], $auditNew);

        $action = array_key_exists('trang_thai', $changes)
            ? 'pregnancy_tracking_status_changed'
            : 'pregnancy_tracking_updated';

        BenhAnObserver::logCustomActionWithValues($benhAn, $action, $auditOld, $auditNew);
    }

    private function resolveBenhAn(TheoDoiThaiKy $record): ?BenhAn
    {
        if ($record->relationLoaded('benhAn') && $record->benhAn) {
            return $record->benhAn;
        }

        if (! $record->benh_an_id) {
            return null;
        }

        return BenhAn::find($record->benh_an_id);
    }

    private function buildAuditValues(TheoDoiThaiKy $record): array
    {
        return [
            'theo_doi_thai_ky_id' => $record->id,
            'benh_an_id' => $record->benh_an_id,
            'user_id' => $record->user_id,
            'bac_si_id' => $record->bac_si_id,
            'ngay_theo_doi' => $record->ngay_theo_doi?->format('Y-m-d') ?? $record->ngay_theo_doi,
            'tuan_thai' => $record->tuan_thai,
            'can_nang_kg' => $record->can_nang_kg,
            'huyet_ap_tam_thu' => $record->huyet_ap_tam_thu,
            'huyet_ap_tam_truong' => $record->huyet_ap_tam_truong,
            'nhip_tim_thai' => $record->nhip_tim_thai,
            'duong_huyet' => $record->duong_huyet,
            'huyet_sac_to' => $record->huyet_sac_to,
            'trieu_chung' => $record->trieu_chung,
            'ghi_chu' => $record->ghi_chu,
            'nhan_xet' => $record->nhan_xet,
            'file_path' => $record->file_path,
            'disk' => $record->disk,
            'trang_thai' => $record->trang_thai,
        ];
    }
}
