<?php

namespace Database\Factories;

use App\Models\DichVu;
use Illuminate\Database\Eloquent\Factories\Factory;

class DichVuFactory extends Factory
{
    protected $model = DichVu::class;

    public function definition()
    {
        return [
            'ten_dich_vu' => fake()->sentence(2),
            'mo_ta' => fake()->paragraph(),
            'gia' => fake()->numberBetween(150000, 500000),
            'thoi_gian_uoc_tinh' => 30,
        ];
    }
}
