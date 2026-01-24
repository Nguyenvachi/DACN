<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\NhanVien;

class NhanVienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $staffs = [
                [
                    'ho_ten' => 'Lê Minh Nhật',
                    'chuc_vu' => 'Lễ tân / Tiếp đón',
                    'so_dien_thoai' => '0909222001',
                    'email_cong_viec' => 'nhat.le@vietcare.com',
                    'ngay_sinh' => '1998-05-12',
                    'gioi_tinh' => 'Nam',
                    'trang_thai' => 'Đang làm',
                ],
                [
                    'ho_ten' => 'Nguyễn Thị Hồng Hạnh',
                    'chuc_vu' => 'Nhân viên CSKH',
                    'so_dien_thoai' => '0909222002',
                    'email_cong_viec' => 'hanh.nguyen@vietcare.com',
                    'ngay_sinh' => '2000-10-20',
                    'gioi_tinh' => 'Nữ',
                    'trang_thai' => 'Đang làm',
                ],
                [
                    'ho_ten' => 'Trần Thị Kim Dung',
                    'chuc_vu' => 'Lễ tân',
                    'so_dien_thoai' => '0909222003',
                    'email_cong_viec' => 'dung.tran@vietcare.com',
                    'ngay_sinh' => '1999-08-15',
                    'gioi_tinh' => 'Nữ',
                    'trang_thai' => 'Đang làm',
                ],
                [
                    'ho_ten' => 'Phạm Thị Thu Thảo',
                    'chuc_vu' => 'Điều dưỡng trưởng',
                    'so_dien_thoai' => '0909222004',
                    'email_cong_viec' => 'thao.pham@vietcare.com',
                    'ngay_sinh' => '1985-02-05',
                    'gioi_tinh' => 'Nữ',
                    'trang_thai' => 'Đang làm',
                ],
                [
                    'ho_ten' => 'Lê Thị Thanh Trúc',
                    'chuc_vu' => 'Nữ hộ sinh',
                    'so_dien_thoai' => '0909222005',
                    'email_cong_viec' => 'truc.le@vietcare.com',
                    'ngay_sinh' => '1995-11-10',
                    'gioi_tinh' => 'Nữ',
                    'trang_thai' => 'Đang làm',
                ],
                [
                    'ho_ten' => 'Nguyễn Ngọc Huyền',
                    'chuc_vu' => 'Điều dưỡng đa khoa',
                    'so_dien_thoai' => '0909222006',
                    'email_cong_viec' => 'huyen.nguyen@vietcare.com',
                    'ngay_sinh' => '1997-07-22',
                    'gioi_tinh' => 'Nữ',
                    'trang_thai' => 'Đang làm',
                ],
                [
                    'ho_ten' => 'Vũ Thị Minh Anh',
                    'chuc_vu' => 'Thu ngân',
                    'so_dien_thoai' => '0909222007',
                    'email_cong_viec' => 'anh.vu@vietcare.com',
                    'ngay_sinh' => '1996-04-30',
                    'gioi_tinh' => 'Nữ',
                    'trang_thai' => 'Đang làm',
                ],
                [
                    'ho_ten' => 'Hoàng Văn Nam',
                    'chuc_vu' => 'Dược sĩ',
                    'so_dien_thoai' => '0909222008',
                    'email_cong_viec' => 'nam.hoang@vietcare.com',
                    'ngay_sinh' => '1990-09-14',
                    'gioi_tinh' => 'Nam',
                    'trang_thai' => 'Đang làm',
                ],
                [
                    'ho_ten' => 'Nguyễn Thu Phương',
                    'chuc_vu' => 'Quản lý phòng khám',
                    'so_dien_thoai' => '0909222009',
                    'email_cong_viec' => 'phuong.nguyen@vietcare.com',
                    'ngay_sinh' => '1980-01-01',
                    'gioi_tinh' => 'Nữ',
                    'trang_thai' => 'Đang làm',
                ],
                [
                    'ho_ten' => 'Trần Quốc Bảo',
                    'chuc_vu' => 'Nhân viên IT / Kỹ thuật',
                    'so_dien_thoai' => '0909222010',
                    'email_cong_viec' => 'bao.tran@vietcare.com',
                    'ngay_sinh' => '1995-12-18',
                    'gioi_tinh' => 'Nam',
                    'trang_thai' => 'Đang làm',
                ],
            ];

            foreach ($staffs as $s) {
                $user = User::updateOrCreate(
                    ['email' => $s['email_cong_viec']],
                    [
                        'name' => $s['ho_ten'],
                        'password' => Hash::make('password'),
                        'so_dien_thoai' => $s['so_dien_thoai'],
                        'ngay_sinh' => $s['ngay_sinh'],
                        'gioi_tinh' => $s['gioi_tinh'],
                        'role' => 'staff',
                        'email_verified_at' => Carbon::now(),
                    ]
                );

                // Assign spatie role if available
                if (method_exists($user, 'assignRole')) {
                    try {
                        $user->assignRole('staff');
                    } catch (\Throwable $e) {
                        // noop - role may not exist yet
                    }
                }

                NhanVien::updateOrCreate([
                    'email_cong_viec' => $s['email_cong_viec'],
                ], [
                    'user_id' => $user->id,
                    'ho_ten' => $s['ho_ten'],
                    'chuc_vu' => $s['chuc_vu'],
                    'so_dien_thoai' => $s['so_dien_thoai'],
                    'email_cong_viec' => $s['email_cong_viec'],
                    'ngay_sinh' => $s['ngay_sinh'],
                    'gioi_tinh' => $s['gioi_tinh'],
                    'trang_thai' => $s['trang_thai'],
                ]);
            }
        });

        $this->command->info('✅ NhanVienSeeder: Seeded 10 staff records.');
    }
}
