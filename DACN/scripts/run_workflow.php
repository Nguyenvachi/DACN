<?php
// scripts/run_workflow.php
// Usage: php scripts\run_workflow.php
// This script bootstraps the Laravel app and runs an automated workflow

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\BacSi;
use App\Models\DichVu;
use App\Models\LichHen;
use Carbon\Carbon;

echo "--- Starting automated workflow run ---\n";

// 1) Ensure test patient exists
$patient = User::firstOrCreate(
    ['email' => 'tn822798@gmail.com'],
    ['name' => 'Nguyễn Thích', 'password' => bcrypt('password')]
);

// 2) Ensure a doctor user and BacSi record exist (provide required fields)
$doctorUser = User::firstOrCreate(
    ['email' => 'doctor1@example.test'],
    ['name' => 'BS. Bùi Thanh Phước', 'password' => bcrypt('password')]
);

$doctor = BacSi::firstOrCreate(
    ['user_id' => $doctorUser->id],
    [
        'user_id' => $doctorUser->id,
        'ho_ten' => 'BS. Bùi Thanh Phước',
        'email' => $doctorUser->email,
        'chuyen_khoa' => 'Tổng quát',
        'so_dien_thoai' => '0900000000',
        'trang_thai' => 'Đang hoạt động'
    ]
);

// 3) Ensure a service exists
$service = DichVu::firstOrCreate(
    ['ten_dich_vu' => 'Full Combo'],
    ['gia' => 1800000]
);

// 4) Create appointment for today (confirmed)
$today = Carbon::today();
$time = Carbon::now()->addMinutes(5)->format('H:i');

$appointment = LichHen::firstOrCreate([
    'bac_si_id' => $doctor->id,
    'ngay_hen' => $today->toDateString(),
    'thoi_gian_hen' => $time,
], [
    'user_id' => $patient->id,
    'dich_vu_id' => $service->id,
    'tong_tien' => $service->gia,
    'ghi_chu' => 'Tạo tự động bởi script kiểm thử',
    'trang_thai' => LichHen::STATUS_CONFIRMED_VN,
]);

echo "Created appointment id={$appointment->id} with status={$appointment->trang_thai}\n";

// 5) Simulate staff check-in
$appointment->update([
    'trang_thai' => LichHen::STATUS_CHECKED_IN_VN,
    'checked_in_at' => Carbon::now(),
]);

$appointment->refresh();
echo "After check-in: id={$appointment->id} status={$appointment->trang_thai} checked_in_at={$appointment->checked_in_at}\n";

// 6) Inspect queue query (should pick up checked-in)
$queue = LichHen::whereDate('ngay_hen', $today)
    ->where('trang_thai', LichHen::STATUS_CHECKED_IN_VN)
    ->get();

echo "Queue count (checked-in): " . $queue->count() . "\n";
if ($queue->contains('id', $appointment->id)) {
    echo "Appointment appears in queue as expected.\n";
} else {
    echo "Appointment NOT found in queue — check DB exact stored value for 'trang_thai'.\n";
    echo "Raw DB value: \n";
    $raw = \DB::table('lich_hens')->where('id', $appointment->id)->value('trang_thai');
    var_export($raw);
    echo "\n";
}

// 7) Simulate call-next (staff calls patient into exam)
$appointment->update([
    'trang_thai' => LichHen::STATUS_IN_PROGRESS_VN,
    'thoi_gian_bat_dau_kham' => Carbon::now(),
]);
$appointment->refresh();

echo "After call-next: id={$appointment->id} status={$appointment->trang_thai} started_at={$appointment->thoi_gian_bat_dau_kham}\n";

// 8) Simulate complete
$appointment->update([
    'trang_thai' => LichHen::STATUS_COMPLETED_VN,
    'completed_at' => Carbon::now(),
]);
$appointment->refresh();

echo "After complete: id={$appointment->id} status={$appointment->trang_thai} completed_at={$appointment->completed_at}\n";

echo "--- Workflow finished ---\n";

// Print instructions to inspect UI
echo "Open http://127.0.0.1:8000/staff/queue and refresh to verify.\n";

// ----------------------
// OPTIONAL: create invoice and simulate payment
// ----------------------
// Create HoaDon if none exists
$hoaDon = \App\Models\HoaDon::firstWhere('lich_hen_id', $appointment->id);
if (! $hoaDon) {
    $hoaDon = \App\Models\HoaDon::create([
        'lich_hen_id' => $appointment->id,
        'user_id' => $patient->id,
        'tong_tien' => $appointment->tong_tien ?? ($service->gia ?? 0),
        'so_tien_da_thanh_toan' => 0,
        'so_tien_con_lai' => $appointment->tong_tien ?? ($service->gia ?? 0),
        'trang_thai' => \App\Models\HoaDon::STATUS_UNPAID_VN,
        'phuong_thuc' => null,
        'ghi_chu' => 'Tự động tạo khi hoàn thành bởi script'
    ]);
    echo "Created HoaDon id={$hoaDon->id} with status={$hoaDon->trang_thai}\n";
} else {
    echo "Found existing HoaDon id={$hoaDon->id} status={$hoaDon->trang_thai}\n";
}

// Simulate payment: mark as paid
$hoaDon->so_tien_da_thanh_toan = $hoaDon->tong_tien;
$hoaDon->save();
// mark paid_at on lich_hen as well
$appointment->paid_at = Carbon::now();
$appointment->payment_status = \App\Models\HoaDon::STATUS_PAID_VN;
$appointment->save();
$hoaDon->refresh();

echo "After payment: HoaDon id={$hoaDon->id} status={$hoaDon->trang_thai} paid_amount={$hoaDon->so_tien_da_thanh_toan}\n";

// call realtime endpoint and print JSON (if app is running)
try {
    $url = 'http://127.0.0.1:8000/staff/queue/realtime-data';
    $json = @file_get_contents($url);
    if ($json) {
        echo "Realtime endpoint response:\n" . $json . "\n";
    } else {
        echo "Realtime endpoint not reachable (app server may not be running).\n";
    }
} catch (\Exception $e) {
    echo "Realtime fetch failed: " . $e->getMessage() . "\n";
}

echo "Script finished fully.\n";
