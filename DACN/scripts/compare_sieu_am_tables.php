<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== BẢNG sieu_ams (Chỉ định siêu âm cho bệnh nhân) ===\n";
$sieuAms = DB::table('sieu_ams')->get(['id', 'loai_sieu_am', 'gia_tien', 'benh_an_id']);
echo "Tổng: " . $sieuAms->count() . " chỉ định\n\n";
foreach ($sieuAms as $sa) {
    echo "ID: {$sa->id} - Loại: {$sa->loai_sieu_am} - Giá: " . number_format($sa->gia_tien, 0) . " - Bệnh án: {$sa->benh_an_id}\n";
}

echo "\n=== BẢNG dich_vus (Danh mục dịch vụ) - Siêu âm ===\n";
$dichVuSieuAm = DB::table('dich_vus')
    ->where('ten_dich_vu', 'like', '%siêu âm%')
    ->orWhere('ten_dich_vu', 'like', '%Siêu âm%')
    ->get(['id', 'ten_dich_vu', 'gia_tien', 'loai']);
echo "Tổng: " . $dichVuSieuAm->count() . " dịch vụ\n\n";
foreach ($dichVuSieuAm as $dv) {
    echo "ID: {$dv->id} - Tên: {$dv->ten_dich_vu} - Giá: " . number_format($dv->gia_tien, 0) . " - Loại: {$dv->loai}\n";
}
