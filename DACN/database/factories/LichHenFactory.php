<?php

namespace Database\Factories;

use App\Models\LichHen;
use App\Models\User;
use App\Models\BacSi;
use App\Models\DichVu;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class LichHenFactory extends Factory
{
    protected $model = LichHen::class;

    public function definition()
    {
        $user = User::factory()->create(['role' => 'patient']);
        $bacSi = BacSi::factory()->create();
        $dichVu = DichVu::factory()->create();

        return [
            'user_id' => $user->id,
            'bac_si_id' => $bacSi->id,
            'dich_vu_id' => $dichVu->id,
            'ten_benh_nhan' => $user->name,
            'sdt_benh_nhan' => $user->so_dien_thoai ?? fake()->phoneNumber(),
            'email_benh_nhan' => $user->email,
            'ngay_sinh_benh_nhan' => Carbon::now()->subYears(30),
            'tong_tien' => $dichVu->gia,
            'ngay_hen' => Carbon::today(),
            'thoi_gian_hen' => Carbon::now()->format('H:i'),
            'ghi_chu' => null,
            'trang_thai' => LichHen::STATUS_CONFIRMED_VN,
        ];
    }
}
