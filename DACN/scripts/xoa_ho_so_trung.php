<?php

// Script xóa hồ sơ theo dõi thai kỳ trùng lặp
require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\TheoDoiThaiKy;

// Lấy 5 hồ sơ mới nhất
echo "=== DANH SÁCH HỒ SƠ MỚI NHẤT ===\n";
$hoSos = TheoDoiThaiKy::with('user')->orderBy('id', 'desc')->take(5)->get();

foreach ($hoSos as $hs) {
    echo "ID: {$hs->id} | Bệnh nhân: {$hs->user->name} | Trạng thái: {$hs->trang_thai} | Ngày tạo: {$hs->created_at}\n";
}

echo "\n=== XÓA HỒ SƠ MỚI NHẤT ===\n";
$hoSoMoiNhat = TheoDoiThaiKy::orderBy('id', 'desc')->first();

if ($hoSoMoiNhat) {
    echo "Đang xóa hồ sơ ID: {$hoSoMoiNhat->id}...\n";
    $hoSoMoiNhat->delete();
    echo "✅ Đã xóa hồ sơ thành công!\n";
} else {
    echo "Không có hồ sơ nào để xóa.\n";
}
