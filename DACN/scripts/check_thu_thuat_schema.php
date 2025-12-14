<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CẤU TRÚC BẢNG thu_thuats ===\n";
$columns = DB::select("SHOW COLUMNS FROM thu_thuats");

foreach ($columns as $col) {
    echo "{$col->Field} ({$col->Type}) - NULL: {$col->Null}\n";
}

echo "\n=== DỮ LIỆU HIỆN CÓ ===\n";
$thuThuats = DB::table('thu_thuats')->get();
echo "Tổng: " . $thuThuats->count() . " thủ thuật\n";
