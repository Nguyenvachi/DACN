<?php

namespace Database\Factories;

use App\Models\BacSi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BacSiFactory extends Factory
{
    protected $model = BacSi::class;

    public function definition()
    {
        $user = User::factory()->create(['role' => 'doctor']);

        $data = [
            'user_id' => $user->id,
            'ho_ten' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'chuyen_khoa' => 'Khác',
            'so_dien_thoai' => fake()->phoneNumber(),
            'dia_chi' => fake()->address(),
            'kinh_nghiem' => fake()->randomNumber(1),
            'mo_ta' => fake()->sentence(),
            'trang_thai' => 'Đang hoạt động',
        ];

        return $data;
    }
}
