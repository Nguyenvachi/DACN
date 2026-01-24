<?php

namespace Tests\Feature\Workflow;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\LichHen;
use App\Models\User;
use Database\Factories\LichHenFactory;

class BulkCheckinTest extends TestCase
{
    use RefreshDatabase;

    public function test_bulk_checkin_sets_checked_in_by()
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $appts = LichHen::factory()->count(3)->create([
            'trang_thai' => LichHen::STATUS_CONFIRMED_VN,
            'ngay_hen' => now()->toDateString(),
        ]);

        $ids = $appts->pluck('id')->toArray();

        $this->actingAs($staff)
            ->post(route('staff.checkin.bulk'), ['appointment_ids' => $ids])
            ->assertStatus(302);

        foreach ($ids as $id) {
            $apt = LichHen::find($id);
            $this->assertNotNull($apt->checked_in_at);
            $this->assertEquals($staff->id, $apt->checked_in_by);
            $this->assertEquals(LichHen::STATUS_CHECKED_IN_VN, $apt->trang_thai);
        }
    }
}
