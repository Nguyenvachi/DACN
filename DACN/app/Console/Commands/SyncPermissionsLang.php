<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;

class SyncPermissionsLang extends Command
{
    protected $signature = 'permissions:sync-lang {--apply : Actually write changes to resources/lang/vi/permissions.php}';
    protected $description = 'Scan DB permissions and append missing keys to resources/lang/vi/permissions.php (dry-run by default)';

    public function handle()
    {
        $this->info('Scanning permissions from DB...');

        $permissions = Permission::pluck('name')->toArray();
        $langFile = base_path('resources/lang/vi/permissions.php');

        if (!file_exists($langFile)) {
            $this->error('Language file not found: resources/lang/vi/permissions.php');
            return 1;
        }

        $current = include $langFile;
        if (!is_array($current)) {
            $this->error('Language file did not return an array');
            return 1;
        }

        $added = [];

        foreach ($permissions as $p) {
            // try candidates to see if translation already exists
            $candidates = [
                $p,
                strtolower($p),
                str_replace(' ', '-', strtolower($p)),
                str_replace([' ', '-'], '_', strtolower($p)),
            ];

            $found = false;
            foreach ($candidates as $c) {
                if (array_key_exists($c, $current) || array_key_exists(ucfirst($c), $current) || array_key_exists($c, array_change_key_case($current, CASE_LOWER))) {
                    $found = true;
                    break;
                }
            }

            if ($found) continue;

            // build a heuristic Vietnamese label
            $label = $this->guessVietnameseLabel($p);

            // default to humanized p if guess failed
            if (!$label) {
                $label = ucwords(str_replace(['-', '_'], ' ', $p));
            }

            $added[$p] = $label;
        }

        if (empty($added)) {
            $this->info('No missing permissions found.');
            return 0;
        }

        $this->info('Missing translations detected:');
        foreach ($added as $k => $v) {
            $this->line("  $k => $v");
        }

        if ($this->option('apply')) {
            // merge and write file
            $merged = $current;
            foreach ($added as $k => $v) {
                $merged[$k] = $v;
            }

            // export PHP array
            $export = var_export($merged, true);
            $contents = "<?php\n\nreturn " . $export . ";\n";

            if (file_put_contents($langFile, $contents) === false) {
                $this->error('Failed to write language file.');
                return 1;
            }

            $this->info('Updated resources/lang/vi/permissions.php with missing entries.');
            $this->info('Run artisan cache clear commands to see changes.');
        } else {
            $this->info('Dry-run only. Re-run with --apply to write changes to file.');
        }

        return 0;
    }

    protected function guessVietnameseLabel(string $p): ?string
    {
        // common prefix mapping
        $map = [
            'view' => 'Xem',
            'create' => 'Tạo',
            'edit' => 'Chỉnh sửa',
            'delete' => 'Xóa',
            'process' => 'Xử lý',
            'manage' => 'Quản lý',
            'assign' => 'Gán',
            'refund' => 'Hoàn tiền',
            'export' => 'Xuất',
            'import' => 'Nhập',
        ];

        // normalize
        $raw = $p;
        $lower = strtolower($raw);

        // If contains spaces and looks English humanized like 'View Appointment Reports'
        if (preg_match('/^[A-Za-z ]+$/', $raw)) {
            // split first word
            $parts = preg_split('/\s+/', $raw, 2);
            $first = strtolower($parts[0]);
            $rest = isset($parts[1]) ? $parts[1] : '';
            if (isset($map[$first])) {
                return $map[$first] . ($rest ? ' ' . $this->translateCommonTerms($rest) : '');
            }
            return $this->translateCommonTerms($raw);
        }

        // try slug/underscore forms
        $tokens = preg_split('/[-_ ]+/', $lower);
        if (count($tokens) >= 1) {
            $first = $tokens[0];
            $rest = array_slice($tokens, 1);
            if (isset($map[$first])) {
                $restLabel = $this->translateCommonTerms(implode(' ', $rest));
                return $map[$first] . ($restLabel ? ' ' . $restLabel : '');
            }
        }

        return null;
    }

    protected function translateCommonTerms(string $text): string
    {
        // small heuristic dictionary for common terms
        $dict = [
            'appointment' => 'lịch hẹn',
            'appointments' => 'lịch hẹn',
            'report' => 'báo cáo',
            'reports' => 'báo cáo',
            'invoice' => 'hóa đơn',
            'invoices' => 'hóa đơn',
            'medical' => 'bệnh án',
            'record' => 'bệnh án',
            'records' => 'bệnh án',
            'staff' => 'nhân viên',
            'role' => 'vai trò',
            'roles' => 'vai trò',
            'permission' => 'quyền',
            'permissions' => 'quyền',
            'service' => 'dịch vụ',
            'services' => 'dịch vụ',
            'medicine' => 'thuốc',
            'medicines' => 'thuốc',
            'inventory' => 'kho',
            'payment' => 'thanh toán',
            'payments' => 'thanh toán',
            'dashboard' => 'trang tổng quan',
            'post' => 'bài viết',
            'posts' => 'bài viết',
        ];

        $tokens = preg_split('/\s+/', $text);
        $out = [];
        foreach ($tokens as $t) {
            $k = strtolower(trim($t, "-_ "));
            $out[] = $dict[$k] ?? ucfirst($k);
        }
        return implode(' ', $out);
    }
}
