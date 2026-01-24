<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-semibold">Tiền sử & Khám bệnh</h6>
            @can('update', $benhAn)
                <a href="{{ route(auth()->user()->isAdmin() ? 'admin.benhan.edit' : 'doctor.benhan.edit', $benhAn) }}" class="btn btn-sm btn-outline-primary">Sửa</a>
            @endcan
        </div>

        <div class="mb-3">
            <h6 class="fw-semibold small">Triệu chứng (HPI)</h6>
            <p class="mb-0">{{ $benhAn->trieu_chung ?? '—' }}</p>
        </div>

        <div class="mb-3">
            <h6 class="fw-semibold small">Khám thực thể</h6>
            <p class="mb-0 text-muted">(Thông tin khám thực thể được ghi trong phần Ghi chú hoặc Bác sĩ cập nhật)</p>
        </div>

        <div class="mb-3">
            <h6 class="fw-semibold small">Chẩn đoán</h6>
            <p class="mb-0">{{ $benhAn->chuan_doan ?? '—' }}</p>
        </div>

        <div class="mb-3">
            <h6 class="fw-semibold small">Kế hoạch / Điều trị</h6>
            <p class="mb-0">{{ $benhAn->dieu_tri ?? '—' }}</p>
        </div>

        @if($benhAn->ghi_chu)
            <div class="mb-0">
                <h6 class="fw-semibold small">Ghi chú</h6>
                <p>{{ $benhAn->ghi_chu }}</p>
            </div>
        @endif
    </div>
</div>
