<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== KIỂM TRA DỮ LIỆU CHECK-IN ===\n\n";

// Tìm lịch hẹn đã xác nhận
$lichHen = \App\Models\LichHen::with('bacSi.phong', 'user')
    ->where('trang_thai', 'Đã xác nhận')
    ->first();

if ($lichHen) {
    echo "✓ Tìm thấy lịch hẹn:\n";
    echo "  ID: {$lichHen->id}\n";
    echo "  Mã: {$lichHen->ma_lich_hen}\n";
    echo "  Bệnh nhân: {$lichHen->user->name}\n";
    echo "  Trạng thái: {$lichHen->trang_thai}\n";
    echo "  Bác sĩ ID: {$lichHen->bac_si_id}\n";
    echo "  Bác sĩ: {$lichHen->bacSi->ho_ten}\n";
    echo "  Phòng ID: " . ($lichHen->bacSi->phong_id ?? 'NULL') . "\n";

    if ($lichHen->bacSi->phong) {
        echo "  Phòng: {$lichHen->bacSi->phong->ten}\n";
    } else {
        echo "  Phòng: Chưa có phòng\n";
    }

    echo "\n--- Thử check-in ---\n";

    try {
        // Tính STT
        if ($lichHen->bacSi->phong_id) {
            $maxStt = \App\Models\LichHen::join('bac_sis', 'lich_hens.bac_si_id', '=', 'bac_sis.id')
                ->where('bac_sis.phong_id', $lichHen->bacSi->phong_id)
                ->whereDate('lich_hens.ngay_hen', $lichHen->ngay_hen)
                ->whereNotNull('lich_hens.stt_kham')
                ->max('lich_hens.stt_kham');
        } else {
            $maxStt = \App\Models\LichHen::where('bac_si_id', $lichHen->bac_si_id)
                ->whereDate('ngay_hen', $lichHen->ngay_hen)
                ->whereNotNull('stt_kham')
                ->max('stt_kham');
        }

        $nextStt = ($maxStt ?? 0) + 1;
        echo "  STT tiếp theo: {$nextStt}\n";

        echo "\n✓ Có thể check-in được!\n";
    } catch (\Exception $e) {
        echo "\n✗ LỖI: {$e->getMessage()}\n";
        echo "  File: {$e->getFile()}:{$e->getLine()}\n";
    }
} else {
    echo "✗ Không tìm thấy lịch hẹn nào có trạng thái 'Đã xác nhận'\n";

    // Kiểm tra các trạng thái khác
    $statuses = DB::table('lich_hens')->select('trang_thai', DB::raw('count(*) as total'))
        ->groupBy('trang_thai')
        ->get();

    echo "\n--- Các trạng thái hiện có ---\n";
    foreach ($statuses as $status) {
        echo "  {$status->trang_thai}: {$status->total}\n";
    }
}
