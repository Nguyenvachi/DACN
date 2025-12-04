<?php
// Lightweight script to render the hoa_don blade view for HoaDon id=1
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\HoaDon;

$id = $argv[1] ?? 1;
$hoaDon = HoaDon::find($id);
if (! $hoaDon) {
    echo "HoaDon with id={$id} not found.\n";
    exit(1);
}

$html = view('pdf.hoa_don', ['hoaDon' => $hoaDon])->render();

$outDir = storage_path('app');
if (! is_dir($outDir)) mkdir($outDir, 0755, true);
$outFile = $outDir . DIRECTORY_SEPARATOR . "rendered_hoa_don_{$id}.html";
file_put_contents($outFile, $html);
echo "Rendered to: {$outFile}\n";
