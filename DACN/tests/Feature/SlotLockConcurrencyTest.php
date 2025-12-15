<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\BacSi;
use App\Models\DichVu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class SlotLockConcurrencyTest extends TestCase
{
    use RefreshDatabase;

    public function test_concurrent_lock_user_conflict()
    {
        $patient1 = User::factory()->create(['role' => 'patient']);
        $patient2 = User::factory()->create(['role' => 'patient']);

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
        $r = $this->actingAs($patient1)->getJson("/api/bac-si/{$doctor->id}/thoi-gian-trong/{$ngay}?dich_vu_id={$service->id}");
        $r->assertStatus(200);
        $slot = data_get($r->json(), 'slots.0.time');

        // patient1 locks the slot
        $this->actingAs($patient1)->postJson('/api/slot-lock', ['bac_si_id' => $doctor->id, 'ngay' => $ngay, 'gio' => $slot])->assertStatus(200)->assertJson(['success' => true]);

        // patient2 tries to lock the same slot and should fail
        $this->actingAs($patient2)->postJson('/api/slot-lock', ['bac_si_id' => $doctor->id, 'ngay' => $ngay, 'gio' => $slot])->assertStatus(409)->assertJson(['success' => false]);
    }
}
