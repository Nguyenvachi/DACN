<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\BacSi;
use App\Models\DichVu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class BookingFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_slots_and_lock_release_flow()
    {
        $patient = User::factory()->create(['role' => 'patient']);
        $doctor = BacSi::factory()->create();
        $service = DichVu::factory()->create();

        $ngay = now()->addDay()->format('Y-m-d');
        \App\Models\LichLamViec::create([
            'bac_si_id' => $doctor->id,
            'ngay_trong_tuan' => Carbon::parse($ngay)->dayOfWeek,
            'thoi_gian_bat_dau' => '08:00:00',
            'thoi_gian_ket_thuc' => '17:00:00',
        ]);

        // fetch slots using existing LichHenController endpoint
        $r = $this->actingAs($patient)->getJson("/api/bac-si/{$doctor->id}/thoi-gian-trong/{$ngay}?dich_vu_id={$service->id}");
        $r->assertStatus(200)->assertJsonStructure(['slots']);

        // lock a slot
        $slot = $r->json('slots')[0]['time'];
        $this->actingAs($patient)->postJson('/api/slot-lock', [
            'bac_si_id' => $doctor->id,
            'ngay' => $ngay,
            'gio' => $slot,
        ])->assertStatus(200)->assertJson(['success' => true]);

        // release
        $this->actingAs($patient)->postJson('/api/slot-unlock', [
            'bac_si_id' => $doctor->id,
            'ngay' => $ngay,
            'gio' => $slot,
        ])->assertStatus(200)->assertJson(['success' => true]);
    }
}
