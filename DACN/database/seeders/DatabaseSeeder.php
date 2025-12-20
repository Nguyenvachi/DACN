<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // Historically we seeded services with DichVuSeeder, now we use DichVuReplaceSeeder

        // Ensure permissions & roles exist
        $this->call(\Database\Seeders\PermissionSeeder::class);
        $this->call(\Database\Seeders\RoleSeeder::class);

        // Sync role names stored in users.role column to Spatie roles (non-destructive)
        //$this->call(\Database\Seeders\SynchronizeUserRolesSeeder::class);

        // Populate core entities (ChuyenKhoa, Phong, BacSi, DichVu relations)
        $this->call(\Database\Seeders\ChuyenKhoaSeeder::class);

        // Use consolidated service & doctor seeders
        $this->call(\Database\Seeders\DichVuSeeder::class);

        $this->call(\Database\Seeders\BacSiSeeder::class);

        // Seed rooms (Phong) after doctors exist so we can attach them
        $this->call(\Database\Seeders\PhongKhamSeeder::class);

        // Seed sample "Loại xét nghiệm" catalog (depends on ChuyenKhoa + Phong)
        $this->call(\Database\Seeders\LoaiXetNghiemSeeder::class);

        // THÊM: Seed Loại Siêu âm (File con: database/seeders/LoaiSieuAmSeeder.php)
        $this->call(\Database\Seeders\LoaiSieuAmSeeder::class);

        // THÊM: Seed Loại X-Quang (File con: database/seeders/LoaiXQuangSeeder.php)
        $this->call(\Database\Seeders\LoaiXQuangSeeder::class);

        // Seed staff via dedicated seeder to avoid duplication
        $this->call(\Database\Seeders\NhanVienSeeder::class);

        // Seed Ca lam viec for staff
        $this->call(\Database\Seeders\CaLamViecNhanVienSeeder::class);

        // Seed Kho/Inventory (Suppliers, Medicines, Stock, PN/PX)
        $this->call(\Database\Seeders\KhoSeeder::class);

        // Seed Lịch làm việc (Weekly schedules), Lịch nghỉ & Ca điều chỉnh
        $this->call(\Database\Seeders\LichLamViecSeeder::class);

        // Seed CMS / Posts & content
        $this->call(\Database\Seeders\BaiVietSeeder::class);

    }
}
