<?php

// Assign doctors to rooms
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$assignments = [
    2 => 1,   // Bác sĩ 2 -> Phòng 1 (P.101 - Khám thai)
    10 => 1,  // Bác sĩ 10 -> Phòng 1 (P.101 - Khám thai)
    11 => 2,  // Bác sĩ 11 -> Phòng 2 (P.Xét nghiệm máu)
    14 => 1,  // Bác sĩ 14 -> Phòng 1 (P.101 - Khám thai)
    15 => 3,  // Bác sĩ 15 -> Phòng 3 (P.Siêu âm 4D)
    16 => 4,  // Bác sĩ 16 -> Phòng 4 (Phòng Khám Tổng Quát)
];

foreach ($assignments as $bacSiId => $phongId) {
    $bacSi = App\Models\BacSi::find($bacSiId);
    if ($bacSi) {
        $bacSi->phong_id = $phongId;
        $bacSi->save();
        echo "Bác sĩ #{$bacSiId} -> Phòng #{$phongId}\n";
    }
}

echo "\nDone! Assigned " . count($assignments) . " doctors to rooms.\n";
