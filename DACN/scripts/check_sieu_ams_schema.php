<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CẤU TRÚC BẢNG sieu_ams ===\n";
$columns = DB::select("SHOW COLUMNS FROM sieu_ams");

foreach ($columns as $col) {
    echo "{$col->Field} ({$col->Type}) - NULL: {$col->Null} - Key: {$col->Key} - Default: {$col->Default}\n";
}

echo "\n=== DỮ LIỆU HIỆN TẠI ===\n";
$data = DB::table('sieu_ams')->get();
echo "Tổng: " . $data->count() . " bản ghi\n";
