<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Coupon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CouponTest extends TestCase
{
    use RefreshDatabase;

    public function test_coupon_validate_api()
    {
        $patient = User::factory()->create(['role' => 'patient']);

        $coupon = Coupon::create([
            'ma_giam_gia' => 'VIETCARE10',
            'ten' => '10% off',
            'loai' => 'phan_tram',
            'gia_tri' => 10,
            'kich_hoat' => true,
            'ngay_bat_dau' => now()->subDay(),
            'ngay_ket_thuc' => now()->addDay(),
        ]);

        $r = $this->actingAs($patient)->postJson('/api/coupons/validate', [
            'ma' => 'VIETCARE10',
            'tong_tien' => 100000,
        ]);

        $r->assertStatus(200)->assertJson(['success' => true]);
    }
}
