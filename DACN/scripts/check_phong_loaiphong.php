<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== LOẠI PHÒNG ===\n";
$loaiPhongs = DB::table('loai_phongs')->get(['id', 'ten']);
foreach ($loaiPhongs as $lp) {
    echo "ID: {$lp->id} - Tên: {$lp->ten}\n";
}

echo "\n=== PHÒNG ===\n";
$phongs = DB::table('phongs')->get(['id', 'ten', 'loai_phong_id']);
foreach ($phongs as $p) {
    $loaiName = $p->loai_phong_id ? DB::table('loai_phongs')->where('id', $p->loai_phong_id)->value('ten') : 'NULL';
    echo "ID: {$p->id} - Tên: {$p->ten} - Loại: {$loaiName}\n";
}
