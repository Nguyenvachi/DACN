<?php

namespace Tests\Feature\Workflow;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\LichHen;
use App\Models\BenhAn;
use App\Models\User;
use App\Models\BacSi;

class AppointmentWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_checkin_and_doctor_workflow()
    {
        // Setup: create staff, doctor, and appointment
        $staff = User::factory()->create(['role' => 'staff']);
        $doctor = BacSi::factory()->create();
        $patientLichHen = LichHen::factory()->create([
            'bac_si_id' => $doctor->id,
            'trang_thai' => LichHen::STATUS_CONFIRMED_VN,
            'ngay_hen' => now()->toDateString(),
        ]);

        // Staff check-in
        $this->actingAs($staff)
            ->post(route('staff.checkin.checkin', $patientLichHen))
            ->assertStatus(302);

        $patientLichHen->refresh();
        $this->assertNotNull($patientLichHen->checked_in_at);
        $this->assertEquals($staff->id, $patientLichHen->checked_in_by);
        $this->assertEquals(LichHen::STATUS_CHECKED_IN_VN, $patientLichHen->trang_thai);

        // Staff call next
        $this->actingAs($staff)
            ->post(route('staff.queue.call_next', $patientLichHen))
            ->assertStatus(302);

        $patientLichHen->refresh();
        $this->assertEquals(LichHen::STATUS_IN_PROGRESS_VN, $patientLichHen->trang_thai);
        $this->assertNotNull($patientLichHen->thoi_gian_bat_dau_kham);

        // Doctor start exam
        $doctorUser = User::find($doctor->user_id);
        $this->actingAs($doctorUser)
            ->post(route('doctor.queue.start', $patientLichHen))
            ->assertStatus(302);

        $patientLichHen->refresh();
        $this->assertEquals(LichHen::STATUS_IN_PROGRESS_VN, $patientLichHen->trang_thai);

        $benhAn = BenhAn::where('lich_hen_id', $patientLichHen->id)->first();
        $this->assertNotNull($benhAn);

        // Doctor complete exam
        $this->actingAs($doctorUser)
            ->post(route('doctor.lichhen.complete', $patientLichHen), [
                'chuan_doan' => 'Test chẩn đoán',
                'dieu_tri' => 'Test điều trị'
            ])
            ->assertStatus(302);

        $patientLichHen->refresh();
        $this->assertEquals(LichHen::STATUS_COMPLETED_VN, $patientLichHen->trang_thai);
        $this->assertNotNull($patientLichHen->completed_at);

        $benhAn->refresh();
        $this->assertEquals('Test chẩn đoán', $benhAn->chuan_doan);
    }
}
