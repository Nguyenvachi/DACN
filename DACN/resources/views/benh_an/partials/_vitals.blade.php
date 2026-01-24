@php
    $profile = optional($benhAn->benhNhan->patientProfile);
@endphp
<div class="card shadow-sm mb-4" id="vitals">
    <div class="card-body">
        <h6 class="fw-semibold mb-3">Dữ liệu sinh tồn (Vitals)</h6>
        @if($profile->exists)
            <div class="row g-2">
                <div class="col-6"><strong>Cân nặng:</strong> {{ $profile->can_nang ?? '—' }} kg</div>
                <div class="col-6"><strong>Chiều cao:</strong> {{ $profile->chieu_cao ?? '—' }} cm</div>
                <div class="col-6"><strong>BMI:</strong> {{ $profile->bmi ?? '—' }}</div>
                <div class="col-6"><strong>Nhóm máu:</strong> {{ $profile->nhom_mau ?? '—' }}</div>
            </div>
        @else
            <div class="text-muted">Không có dữ liệu vitals.</div>
        @endif
    </div>
</div>
