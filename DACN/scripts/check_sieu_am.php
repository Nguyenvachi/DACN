<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DỊCH VỤ SIÊU ÂM ===\n";
$dichVuSieuAm = DB::table('dich_vus')
    ->where('ten_dich_vu', 'like', '%siêu âm%')
    ->orWhere('ten_dich_vu', 'like', '%Siêu âm%')
    ->get(['id', 'ten_dich_vu', 'loai', 'hoat_dong']);

if ($dichVuSieuAm->isEmpty()) {
    echo "Không tìm thấy dịch vụ siêu âm nào!\n";
} else {
    foreach ($dichVuSieuAm as $dv) {
        $loai = $dv->loai ?? 'NULL';
        $hoatDong = $dv->hoat_dong ? 'Có' : 'Không';
        echo "ID: {$dv->id} - Tên: {$dv->ten_dich_vu}\n";
        echo "   Loại: {$loai} | Hoạt động: {$hoatDong}\n\n";
    }
}

echo "\n=== TẤT CẢ DỊCH VỤ (LOẠI) ===\n";
$loaiStats = DB::table('dich_vus')
    ->select('loai', DB::raw('count(*) as total'))
    ->groupBy('loai')
    ->get();

foreach ($loaiStats as $stat) {
    $loai = $stat->loai ?? 'NULL';
    echo "Loại '{$loai}': {$stat->total} dịch vụ\n";
}
