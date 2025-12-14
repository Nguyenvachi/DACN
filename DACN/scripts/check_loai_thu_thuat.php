<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== LOẠI THỦ THUẬT ===\n";
$loaiThuThuats = DB::table('loai_thu_thuats')->get(['id', 'ten', 'gia_tien', 'thoi_gian']);
foreach ($loaiThuThuats as $ltt) {
    echo "ID: {$ltt->id} - {$ltt->ten}\n";
    echo "   Giá: " . number_format($ltt->gia_tien, 0) . " đ | Thời gian: {$ltt->thoi_gian} phút\n\n";
}

echo "Tổng: " . $loaiThuThuats->count() . " loại thủ thuật\n";
