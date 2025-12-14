<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Mapping phòng với loại phòng dựa vào tên
$mappings = [
    // Phòng ID => Loại Phòng ID
    1 => 1, // P.101 - Khám thai => Phòng Khám Thai
    2 => 2, // P.Xét nghiệm máu => Phòng Xét Nghiệm  
    3 => 3, // P.Siêu âm 4D => Phòng Siêu Âm
    4 => 4, // Phòng Khám Tổng Quát 1 => Phòng Khám Tổng Quát
];

foreach ($mappings as $phongId => $loaiPhongId) {
    $updated = DB::table('phongs')
        ->where('id', $phongId)
        ->update(['loai_phong_id' => $loaiPhongId]);

    if ($updated) {
        $phong = DB::table('phongs')->where('id', $phongId)->first();
        $loai = DB::table('loai_phongs')->where('id', $loaiPhongId)->first();
        echo "✓ Updated: {$phong->ten} => {$loai->ten}\n";
    }
}

echo "\nDone!\n";
