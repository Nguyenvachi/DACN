@extends('layouts.doctor')

@section('content')
<div class="container-fluid py-4">
    {{-- HEADER --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-1" style="color: var(--vc-dark);">
                        <i class="fas fa-clock me-2" style="color: #f59e0b;"></i>
                        Lịch Hẹn Chờ Xác Nhận
                    </h2>
                    <p class="text-muted mb-0">Danh sách lịch hẹn cần xác nhận từ bệnh nhân</p>
                </div>
                <div>
                    <a href="{{ route('doctor.dashboard') }}" class="vc-btn-outline btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Quay lại Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- STATS CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="vc-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Tổng chờ xác nhận</small>
                            <h3 class="fw-bold mb-0 mt-1" style="color: #f59e0b;">{{ $stats['total_pending'] }}</h3>
                        </div>
                        <div class="rounded p-3" style="background: #fef3c7;">
                            <i class="fas fa-clock fa-2x" style="color: #f59e0b;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="vc-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Hôm nay</small>
                            <h3 class="fw-bold mb-0 mt-1" style="color: #10b981;">{{ $stats['today_pending'] }}</h3>
                        </div>
                        <div class="rounded p-3" style="background: #d1fae5;">
                            <i class="fas fa-calendar-day fa-2x" style="color: #10b981;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="vc-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Tuần này</small>
                            <h3 class="fw-bold mb-0 mt-1" style="color: #3b82f6;">{{ $stats['this_week'] }}</h3>
                        </div>
                        <div class="rounded p-3" style="background: #dbeafe;">
                            <i class="fas fa-calendar-week fa-2x" style="color: #3b82f6;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTERS --}}
    <div class="vc-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('doctor.lichhen.pending') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Từ ngày</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Đến ngày</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Dịch vụ</label>
                    <select name="dich_vu_id" class="form-select">
                        <option value="">Tất cả dịch vụ</option>
                        @foreach($dichVus as $dv)
                            <option value="{{ $dv->id }}" {{ request('dich_vu_id') == $dv->id ? 'selected' : '' }}>
                                {{ $dv->ten_dich_vu }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn vc-btn-primary w-100">
                        <i class="fas fa-filter me-1"></i>Lọc
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- APPOINTMENT LIST --}}
    <div class="vc-card">
        <div class="card-header bg-white border-0 pt-3 pb-0">
            <h5 class="fw-bold mb-0">
                <i class="fas fa-list me-2" style="color: #10b981;"></i>
                Danh sách lịch hẹn ({{ $appointments->total() }})
            </h5>
        </div>
        <div class="card-body p-0">
            @if($appointments->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-check-circle fa-3x mb-3" style="color: #10b981; opacity: 0.3;"></i>
                    <p class="text-muted mb-0 fw-semibold">Tuyệt vời! Không có lịch hẹn nào chờ xác nhận.</p>
                    <small class="text-muted">Tất cả lịch hẹn đã được xử lý</small>
                </div>
            @else
                <div class="table-responsive">
                    <table class="appointment-today-table">
                        <thead>
                            <tr>
                                <th>Ngày & Giờ</th>
                                <th>Bệnh nhân</th>
                                <th>Dịch vụ</th>
                                <th>Ghi chú</th>
                                <th>Đặt lúc</th>
                                <th class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $appt)
                            <tr id="appointment-row-{{ $appt->id }}">
                                <td>
                                    <div class="fw-bold text-success-vc">
                                        <i class="far fa-calendar me-1"></i>
                                        {{ \Carbon\Carbon::parse($appt->ngay_hen)->format('d/m/Y') }}
                                    </div>
                                    <small class="text-muted">
                                        <i class="far fa-clock me-1"></i>
                                        {{ \Carbon\Carbon::parse($appt->thoi_gian_hen)->format('H:i') }}
                                    </small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-2" style="width: 38px; height: 38px; border-radius: 50%; background: linear-gradient(135deg, #10b981, #059669); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                                            {{ strtoupper(substr($appt->user->name ?? 'N', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $appt->user->name ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $appt->user->email ?? '' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $appt->dichVu->ten_dich_vu ?? 'N/A' }}
                                    </span>
                                    @if($appt->tong_tien)
                                        <div class="small text-success-vc mt-1">
                                            {{ number_format($appt->tong_tien) }}đ
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $appt->ghi_chu ? Str::limit($appt->ghi_chu, 50) : 'Không có ghi chú' }}
                                    </small>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $appt->created_at->diffForHumans() }}
                                    </small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button type="button"
                                                class="btn btn-sm vc-btn-primary confirm-btn"
                                                data-id="{{ $appt->id }}"
                                                title="Xác nhận">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger reject-btn"
                                                data-id="{{ $appt->id }}"
                                                data-patient="{{ $appt->user->name }}"
                                                title="Từ chối">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <a href="{{ route('doctor.lichhen.show', $appt->id) }}"
                                           class="btn btn-sm vc-btn-outline"
                                           title="Chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                <div class="card-footer bg-white border-0">
                    {{ $appointments->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- REJECT MODAL --}}
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-times-circle text-danger me-2"></i>
                    Từ chối lịch hẹn
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="mb-3">Bạn có chắc muốn từ chối lịch hẹn của <strong id="patientName"></strong>?</p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Lý do từ chối (không bắt buộc)</label>
                        <textarea name="reason" class="form-control" rows="3" placeholder="Nhập lý do từ chối..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-1"></i>Từ chối
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'));

    // Xác nhận
    document.querySelectorAll('.confirm-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const appointmentId = this.dataset.id;

            if (!confirm('Xác nhận lịch hẹn này?')) return;

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch(`/doctor/lich-hen/${appointmentId}/confirm`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById(`appointment-row-${appointmentId}`).remove();
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message || 'Có lỗi xảy ra');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-check"></i>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi xác nhận');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-check"></i>';
            });
        });
    });

    // Từ chối
    document.querySelectorAll('.reject-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const appointmentId = this.dataset.id;
            const patientName = this.dataset.patient;

            document.getElementById('patientName').textContent = patientName;
            document.getElementById('rejectForm').action = `/doctor/lich-hen/${appointmentId}/reject`;

            rejectModal.show();
        });
    });

    // Submit reject form
    document.getElementById('rejectForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const submitBtn = form.querySelector('[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Đang xử lý...';

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: new FormData(form)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message || 'Có lỗi xảy ra');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-times me-1"></i>Từ chối';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-times me-1"></i>Từ chối';
        });
    });
});
</script>
@endpush

@endsection
