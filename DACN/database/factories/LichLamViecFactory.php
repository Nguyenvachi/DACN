<?php

namespace Database\Factories;

use App\Models\LichLamViec;
use App\Models\BacSi;
use Illuminate\Database\Eloquent\Factories\Factory;

class LichLamViecFactory extends Factory
{
    protected $model = LichLamViec::class;

    public function definition()
    {
        return [
            'bac_si_id' => BacSi::factory(),
            'phong_id' => null,
            'ngay_trong_tuan' => $this->faker->numberBetween(0,6),
            'thoi_gian_bat_dau' => '08:00:00',
            'thoi_gian_ket_thuc' => '17:00:00',
        ];
    }
}
