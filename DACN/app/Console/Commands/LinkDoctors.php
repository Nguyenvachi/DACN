<?php

namespace App\Console\Commands;

use App\Models\BacSi;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LinkDoctors extends Command
{
    protected $signature = 'doctors:link {--create-missing : Tạo user cho bác sĩ chưa có tài khoản} {--dry : Chạy thử, không ghi DB}';
    protected $description = 'Liên kết user (role=doctor) với hồ sơ BacSi theo ho_ten';

    public function handle(): int
    {
        $dry = (bool)$this->option('dry');
        $create = (bool)$this->option('create-missing');

        $this->info('Bắt đầu liên kết bác sĩ <-> user...');
        $linked = 0;
        $created = 0;
        $skipped = 0;

        // Map theo tên: users.name == bac_sis.ho_ten
        $doctors = BacSi::query()->get();
        /** @var \App\Models\BacSi $bs */
        foreach ($doctors as $bs) {
            if ($bs->user_id) {
                $skipped++;
                continue;
            }

            $user = User::where('role', 'doctor')
                ->whereRaw('LOWER(name) = ?', [mb_strtolower($bs->ho_ten ?? '')])
                ->first();

            if (!$user && $create) {
                $email = Str::slug($bs->ho_ten ?: 'bac-si') . ".{$bs->id}@example.local";
                $password = 'Password!123';
                $this->line(" - Tạo user cho '{$bs->ho_ten}' -> {$email}");

                if (!$dry) {
                    $user = User::create([
                        'name' => $bs->ho_ten ?: "Bác sĩ {$bs->id}",
                        'email' => $email,
                        'password' => Hash::make($password),
                        'role' => 'doctor',
                    ]);
                }
                $created++;
            }

            if ($user) {
                $this->line(" - Gắn user #{$user->id} cho Bác sĩ #{$bs->id} ({$bs->ho_ten})");
                if (!$dry) {
                    $bs->user_id = $user->id;
                    $bs->save();
                }
                $linked++;
            } else {
                $this->warn(" ! Không tìm thấy user cho Bác sĩ #{$bs->id} ({$bs->ho_ten})");
            }
        }

        $this->info("Hoàn tất. Linked: {$linked}, Created users: {$created}, Skipped: {$skipped}");
        if ($create) {
            $this->warn('Mật khẩu mặc định cho tài khoản vừa tạo: Password!123 (hãy đổi sau khi đăng nhập).');
        }
        return Command::SUCCESS;
    }
}
