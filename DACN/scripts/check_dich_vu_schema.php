<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CẤU TRÚC BẢNG dich_vus ===\n";
$columns = DB::select("SHOW COLUMNS FROM dich_vus");

foreach ($columns as $col) {
    echo "{$col->Field} ({$col->Type}) - NULL: {$col->Null} - Key: {$col->Key}\n";
}
