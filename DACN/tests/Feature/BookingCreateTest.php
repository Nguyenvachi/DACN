<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\BacSi;
use App\Models\DichVu;
use App\Models\Coupon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class BookingCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_booking_with_coupon_and_lock()
    {
        $patient = User::factory()->create(['role' => 'patient']);
        $doctor = BacSi::factory()->create();
        $service = DichVu::factory()->create(['gia' => 200000, 'thoi_gian_uoc_tinh' => 30]);

        $ngay = now()->addDay()->format('Y-m-d');
        \App\Models\LichLamViec::create([
            'bac_si_id' => $doctor->id,
            'ngay_trong_tuan' => Carbon::parse($ngay)->dayOfWeek,
            'thoi_gian_bat_dau' => '08:00:00',
            'thoi_gian_ket_thuc' => '17:00:00',
        ]);

        // fetch an available slot
        $r = $this->actingAs($patient)->getJson("/api/bac-si/{$doctor->id}/thoi-gian-trong/{$ngay}?dich_vu_id={$service->id}");
        $r->assertStatus(200)->assertJsonStructure(['slots']);
        $slot = data_get($r->json(), 'slots.0.time');

        // create coupon
        $coupon = Coupon::create([
            'ma_giam_gia' => 'TEST50',
            'ten' => '50K discount',
            'loai' => 'tien_mat',
            'gia_tri' => 50000,
            'kich_hoat' => true,
            'ngay_bat_dau' => now()->subDay(),
            'ngay_ket_thuc' => now()->addDay(),
        ]);

        // lock the slot
        $this->actingAs($patient)->postJson('/api/slot-lock', [
            'bac_si_id' => $doctor->id,
            'ngay' => $ngay,
            'gio' => $slot,
        ])->assertStatus(200)->assertJson(['success' => true]);

        // submit booking
        $postData = [
            'bac_si_id' => $doctor->id,
            'dich_vu_id' => $service->id,
            'ngay_hen' => $ngay,
            'thoi_gian_hen' => $slot,
            'ten_benh_nhan' => 'Test User',
            'sdt_benh_nhan' => '0987654321',
            'email_benh_nhan' => 'test@example.com',
            'coupon_code' => 'TEST50',
        ];

        $this->actingAs($patient)->post('/luu-lich-hen', $postData)
            ->assertRedirect();

        // Verify the appointment exists
        $this->assertDatabaseHas('lich_hens', ['bac_si_id' => $doctor->id, 'ngay_hen' => $ngay, 'thoi_gian_hen' => $slot]);

        // Verify invoice has coupon
        $this->assertDatabaseHas('hoa_dons', ['user_id' => $patient->id, 'coupon_id' => $coupon->id, 'giam_gia' => $coupon->tinhGiamGia($service->gia)]);
    }
}
