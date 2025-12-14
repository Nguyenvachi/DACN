<?php

// Script to assign room numbers to doctors
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$doctors = App\Models\BacSi::all();

foreach ($doctors as $doc) {
    $doc->so_phong = 'P' . $doc->id;
    $doc->save();
}

echo "Done! Updated " . $doctors->count() . " doctors with room numbers.\n";
