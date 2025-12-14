<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Xóa dịch vụ siêu âm khỏi bảng dich_vus...\n";
$deleted = DB::table('dich_vus')
    ->where('ten_dich_vu', 'like', '%siêu âm%')
    ->orWhere('ten_dich_vu', 'like', '%Siêu âm%')
    ->delete();

echo "Đã xóa {$deleted} dịch vụ siêu âm khỏi bảng dich_vus\n\n";

echo "=== BẢNG loai_sieu_ams ===\n";
$loaiSieuAms = DB::table('loai_sieu_ams')->get(['id', 'ten', 'gia_tien']);
foreach ($loaiSieuAms as $lsa) {
    echo "ID: {$lsa->id} - {$lsa->ten} - " . number_format($lsa->gia_tien, 0) . " đ\n";
}

echo "\n=== BẢNG dich_vus (còn lại) ===\n";
$dichVus = DB::table('dich_vus')->get(['id', 'ten_dich_vu']);
echo "Tổng: " . $dichVus->count() . " dịch vụ\n";
foreach ($dichVus as $dv) {
    echo "- {$dv->ten_dich_vu}\n";
}
