<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\BacSi;
use App\Models\DichVu;
use App\Models\CaDieuChinhBacSi;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookingOverrideAddTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_allows_override_add_shift_without_base_schedule()
    {
        $patient = User::factory()->create(['role' => 'patient']);
        $doctor = BacSi::factory()->create();
        $service = DichVu::factory()->create(['gia' => 150000, 'thoi_gian_uoc_tinh' => 30]);

        $ngay = now()->addDay()->format('Y-m-d');

        // Create an 'add' override shift for doctor (no base LichLamViec)
        CaDieuChinhBacSi::create([
            'bac_si_id' => $doctor->id,
            'ngay' => $ngay,
            'gio_bat_dau' => '08:00:00',
            'gio_ket_thuc' => '12:00:00',
            'hanh_dong' => 'add',
            'ly_do' => 'Khung làm thêm',
        ]);

        // fetch available slots
        $r = $this->actingAs($patient)->getJson("/api/bac-si/{$doctor->id}/thoi-gian-trong/{$ngay}?dich_vu_id={$service->id}");
        $r->assertStatus(200)->assertJsonStructure(['slots']);
        $slot = data_get($r->json(), 'slots.0.time');

        // submit booking
        $postData = [
            'bac_si_id' => $doctor->id,
            'dich_vu_id' => $service->id,
            'ngay_hen' => $ngay,
            'thoi_gian_hen' => $slot,
            'ten_benh_nhan' => 'Override Add Test',
            'sdt_benh_nhan' => '0123456789',
            'email_benh_nhan' => 'override@example.com',
        ];

        $this->actingAs($patient)->post('/luu-lich-hen', $postData)
            ->assertRedirect();

        $this->assertDatabaseHas('lich_hens', ['bac_si_id' => $doctor->id, 'ngay_hen' => $ngay, 'thoi_gian_hen' => $slot]);
    }
}
