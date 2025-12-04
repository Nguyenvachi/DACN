<?php
// Generate a PDF from the hoa_don view for a given HoaDon id
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\HoaDon;

$id = $argv[1] ?? 7;
$hoaDon = HoaDon::find($id);
if (! $hoaDon) {
    echo "HoaDon with id={$id} not found.\n";
    exit(1);
}

$outDir = storage_path('app');
if (! is_dir($outDir)) mkdir($outDir, 0755, true);
$outFile = $outDir . DIRECTORY_SEPARATOR . "hoa_don_{$id}.pdf";

try {
    $pdf = app('dompdf.wrapper');
    $pdf->loadView('pdf.hoa_don', ['hoaDon' => $hoaDon]);
    $pdf->setPaper('a4', 'portrait');
    $pdf->save($outFile);
    echo "PDF generated: {$outFile}\n";
} catch (Throwable $e) {
    echo "PDF generation failed: " . $e->getMessage() . "\n";
    exit(1);
}
