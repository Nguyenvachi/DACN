<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\BacSi;

$bacSis = BacSi::limit(8)->get();
// Try to login a system user for layout (cli rendering has no auth)
try {
	$firstUser = \App\Models\User::first();
	if ($firstUser) {
		\Illuminate\Support\Facades\Auth::loginUsingId($firstUser->id);
	}
} catch (\Throwable $e) {
	// ignore
}

$html = view('public.bacsi.index', ['bacSis' => $bacSis])->render();

$out = storage_path('app/rendered_bacsi_index.html');
file_put_contents($out, $html);
echo "Rendered to: {$out}\n";
