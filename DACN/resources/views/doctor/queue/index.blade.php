@extends('layouts.doctor')

@section('content')
<div class="container-fluid py-4">
    {{-- HEADER --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-1" style="color: var(--vc-dark);">
                        <i class="fas fa-users me-2" style="color: #3b82f6;"></i>
                        Hàng Đợi Khám Bệnh
                    </h2>
                    <p class="text-muted mb-0">Danh sách bệnh nhân đã check-in đang chờ khám</p>
                </div>
                <div>
                    <button class="btn vc-btn-outline btn-sm" onclick="location.reload()">
                        <i class="fas fa-sync-alt me-1"></i>Làm mới
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- STATS CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="vc-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted" data-bs-toggle="tooltip" title="Bệnh nhân đã check-in, đang chờ được gọi vào phòng khám">
                                <i class="fas fa-info-circle me-1"></i>Đang chờ
                            </small>
                            <h3 class="fw-bold mb-0 mt-1" style="color: #f59e0b;">{{ $stats['waiting'] }}</h3>
                        </div>
                        <div class="rounded p-3" style="background: #fef3c7;">
                            <i class="fas fa-hourglass-half fa-2x" style="color: #f59e0b;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="vc-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted" data-bs-toggle="tooltip" title="Bệnh nhân đang được khám trong phòng">
                                <i class="fas fa-info-circle me-1"></i>Đang khám
                            </small>
                            <h3 class="fw-bold mb-0 mt-1" style="color: #10b981;">{{ $stats['in_progress'] }}</h3>
                        </div>
                        <div class="rounded p-3" style="background: #d1fae5;">
                            <i class="fas fa-stethoscope fa-2x" style="color: #10b981;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="vc-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted" data-bs-toggle="tooltip" title="Lịch hẹn đã xác nhận nhưng bệnh nhân chưa check-in">
                                <i class="fas fa-info-circle me-1"></i>Đã xác nhận hôm nay
                            </small>
                            <h3 class="fw-bold mb-0 mt-1" style="color: #3b82f6;">{{ $stats['confirmed_today'] }}</h3>
                        </div>
                        <div class="rounded p-3" style="background: #dbeafe;">
                            <i class="fas fa-check-circle fa-2x" style="color: #3b82f6;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="vc-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted" data-bs-toggle="tooltip" title="Thời gian chờ trung bình từ lúc check-in đến lúc bắt đầu khám">
                                <i class="fas fa-info-circle me-1"></i>Thời gian chờ TB
                            </small>
                            <h3 class="fw-bold mb-0 mt-1" style="color: #8b5cf6;">
                                {{ $stats['avg_wait_time'] ?? 0 }} phút
                            </h3>
                        </div>
                        <div class="rounded p-3" style="background: #ede9fe;">
                            <i class="fas fa-clock fa-2x" style="color: #8b5cf6;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- WAITING QUEUE --}}
    <div class="vc-card mb-4">
        <div class="card-header bg-white border-0 pt-3 pb-0">
            <h5 class="fw-bold mb-0">
                <i class="fas fa-hourglass-half me-2" style="color: #f59e0b;"></i>
                Bệnh nhân đang chờ ({{ $waitingQueue->count() }})
            </h5>
        </div>
        <div class="card-body">
            @if($waitingQueue->isEmpty())
                <div class="text-center py-4">
                    <i class="fas fa-check-circle fa-3x mb-3" style="color: #10b981; opacity: 0.3;"></i>
                    <p class="text-muted mb-0">Không có bệnh nhân nào đang chờ</p>
                </div>
            @else
                <div class="row g-3">
                    @foreach($waitingQueue as $index => $appt)
                    <div class="col-md-6 col-lg-4">
                        <div class="card border h-100 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="me-2" style="width: 45px; height: 45px; border-radius: 50%; background: linear-gradient(135deg, #f59e0b, #d97706); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.1rem;">
                                            {{ $index + 1 }}
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $appt->user->name }}</div>
                                            <small class="text-muted">{{ $appt->user->email }}</small>
                                        </div>
                                    </div>
                                    <span class="status-badge status-checked-in">Check-in</span>
                                </div>

                                <div class="mb-2">
                                    <small class="text-muted d-block">Dịch vụ:</small>
                                    <span class="badge bg-light text-dark border">
                                        {{ $appt->dichVu->ten_dich_vu ?? 'N/A' }}
                                    </span>
                                </div>

                                <div class="mb-2">
                                    <small class="text-muted d-block">Thời gian hẹn:</small>
                                    <div class="fw-semibold">
                                        <i class="far fa-clock me-1" style="color: #10b981;"></i>
                                        {{ \Carbon\Carbon::parse($appt->thoi_gian_hen)->format('H:i') }}
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <small class="text-muted d-block">Thời gian chờ:</small>
                                    <div class="fw-semibold" style="color: #f59e0b;">
                                        @php
                                            $waitTime = $appt->checked_in_at ? \Carbon\Carbon::parse($appt->checked_in_at)->diffInMinutes(now()) : 0;
                                        @endphp
                                        {{ $waitTime }} phút
                                    </div>
                                </div>

                                <form action="{{ route('doctor.queue.start', $appt->id) }}" method="POST" class="start-exam-form">
                                    @csrf
                                    <button type="submit" class="btn vc-btn-primary w-100">
                                        <i class="fas fa-stethoscope me-1"></i>
                                        Bắt đầu khám
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- IN PROGRESS --}}
    <div class="vc-card mb-4">
        <div class="card-header bg-white border-0 pt-3 pb-0">
            <h5 class="fw-bold mb-0">
                <i class="fas fa-stethoscope me-2" style="color: #10b981;"></i>
                Đang khám ({{ $inProgressQueue->count() }})
            </h5>
        </div>
        <div class="card-body">
            @if($inProgressQueue->isEmpty())
                <div class="text-center py-4">
                    <i class="fas fa-info-circle fa-3x mb-3" style="color: #6b7280; opacity: 0.3;"></i>
                    <p class="text-muted mb-0">Chưa có bệnh nhân nào đang khám</p>
                </div>
            @else
                <div class="row g-3">
                    @foreach($inProgressQueue as $appt)
                    @php
                        $startTime = $appt->thoi_gian_bat_dau_kham ?? $appt->updated_at;
                        $duration = $startTime ? \Carbon\Carbon::parse($startTime)->diffInMinutes(now()) : 0;
                        $phoneNumber = $appt->user->benh_nhan->so_dien_thoai ?? $appt->user->so_dien_thoai ?? 'N/A';
                    @endphp
                    
                    <div class="col-12">
                        <div class="card border-0 shadow-sm" style="border-left: 4px solid #10b981 !important; background: linear-gradient(to right, #ecfdf5 0%, white 100%);">
                            <div class="card-body p-3">
                                <div class="row align-items-center">
                                    {{-- STT & Avatar --}}
                                    <div class="col-auto">
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="mb-2" style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #10b981, #059669); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.5rem; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);">
                                                {{ $appt->stt_kham ?? '?' }}
                                            </div>
                                            <span class="badge bg-success" style="font-size: 0.7rem;">
                                                <i class="fas fa-spinner fa-spin me-1"></i>Đang khám
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Patient Info --}}
                                    <div class="col-md-3">
                                        <div class="fw-bold text-dark mb-1" style="font-size: 1.1rem;">
                                            {{ $appt->user->name }}
                                        </div>
                                        <div class="d-flex flex-column gap-1">
                                            <small class="text-muted">
                                                <i class="fas fa-id-card me-1"></i>
                                                Mã BN: #{{ $appt->user->id }}
                                            </small>
                                            <small class="text-muted">
                                                <i class="fas fa-phone me-1"></i>
                                                {{ $phoneNumber }}
                                            </small>
                                        </div>
                                    </div>

                                    {{-- Service & Room --}}
                                    <div class="col-md-2">
                                        <small class="text-muted d-block mb-1">Dịch vụ</small>
                                        <span class="badge" style="background: #dcfce7; color: #166534; font-size: 0.85rem; font-weight: 600;">
                                            {{ $appt->dichVu->ten_dich_vu ?? 'N/A' }}
                                        </span>
                                        @if($appt->bacSi && $appt->bacSi->phong)
                                        <div class="mt-2">
                                            <small class="text-muted d-block">Phòng khám</small>
                                            <span class="fw-semibold text-primary">
                                                <i class="fas fa-door-open me-1"></i>
                                                {{ $appt->bacSi->phong->ten_phong }}
                                            </span>
                                        </div>
                                        @endif
                                    </div>

                                    {{-- Timeline --}}
                                    <div class="col-md-2">
                                        <div class="d-flex flex-column gap-2">
                                            <div>
                                                <small class="text-muted d-block">Bắt đầu khám</small>
                                                <div class="fw-semibold text-success">
                                                    <i class="far fa-clock me-1"></i>
                                                    {{ $startTime ? \Carbon\Carbon::parse($startTime)->format('H:i') : 'N/A' }}
                                                </div>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block">Thời gian khám</small>
                                                <div class="fw-bold {{ $duration > 30 ? 'text-warning' : 'text-success' }}">
                                                    <i class="fas fa-stopwatch me-1"></i>
                                                    {{ $duration }} phút
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Progress Bar --}}
                                    <div class="col-md-2">
                                        <small class="text-muted d-block mb-1">Tiến độ</small>
                                        <div class="progress mb-2" style="height: 8px;">
                                            @php
                                                $expectedDuration = $appt->dichVu->thoi_gian ?? 30;
                                                $progress = min(100, ($duration / $expectedDuration) * 100);
                                                $progressColor = $progress > 100 ? '#ef4444' : ($progress > 80 ? '#f59e0b' : '#10b981');
                                            @endphp
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: {{ $progress }}%; background: {{ $progressColor }};"
                                                 aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                        <small class="text-muted">
                                            {{ min(100, round($progress)) }}% / Dự kiến: {{ $expectedDuration }}p
                                        </small>
                                    </div>

                                    {{-- Actions --}}
                                    <div class="col-md-2">
                                        <div class="d-flex flex-column gap-2">
                                            @if($appt->benhAn)
                                                <a href="{{ route('doctor.benhan.edit', $appt->benhAn->id) }}"
                                                   class="btn btn-success btn-sm w-100">
                                                    <i class="fas fa-stethoscope me-1"></i>
                                                    Tiếp tục khám
                                                </a>
                                            @else
                                                <form action="{{ route('doctor.queue.start', $appt->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm w-100">
                                                        <i class="fas fa-file-medical me-1"></i>
                                                        Tạo bệnh án
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <div class="btn-group w-100" role="group">
                                                <a href="tel:{{ $phoneNumber }}" 
                                                   class="btn btn-outline-primary btn-sm"
                                                   title="Gọi điện">
                                                    <i class="fas fa-phone"></i>
                                                </a>
                                                <a href="{{ route('doctor.lichhen.show', $appt->id) }}" 
                                                   class="btn btn-outline-info btn-sm"
                                                   title="Xem chi tiết">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- CONFIRMED TODAY --}}
    <div class="vc-card">
        <div class="card-header bg-white border-0 pt-3 pb-0">
            <h5 class="fw-bold mb-0">
                <i class="fas fa-check-circle me-2" style="color: #3b82f6;"></i>
                Đã xác nhận hôm nay ({{ $confirmedToday->count() }})
            </h5>
        </div>
        <div class="card-body p-0">
            @if($confirmedToday->isEmpty())
                <div class="text-center py-4">
                    <i class="fas fa-calendar-check fa-3x mb-3" style="color: #6b7280; opacity: 0.3;"></i>
                    <p class="text-muted mb-0">Không có lịch hẹn nào được xác nhận hôm nay</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="appointment-today-table">
                        <thead>
                            <tr>
                                <th>Giờ hẹn</th>
                                <th>Bệnh nhân</th>
                                <th>Dịch vụ</th>
                                <th>Trạng thái</th>
                                <th class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($confirmedToday as $appt)
                            <tr>
                                <td>
                                    <div class="fw-bold" style="color: #10b981;">
                                        <i class="far fa-clock me-1"></i>
                                        {{ \Carbon\Carbon::parse($appt->thoi_gian_hen)->format('H:i') }}
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-2" style="width: 35px; height: 35px; border-radius: 50%; background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.9rem;">
                                            {{ strtoupper(substr($appt->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $appt->user->name }}</div>
                                            <small class="text-muted">{{ $appt->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $appt->dichVu->ten_dich_vu ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge status-{{ $appt->trang_thai }}">
                                        {{ ucfirst($appt->trang_thai) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($appt->trang_thai === \App\Models\LichHen::STATUS_CONFIRMED_VN)
                                        <form action="{{ route('doctor.queue.checkin', $appt->id) }}"
                                              method="POST"
                                              class="d-inline checkin-form">
                                            @csrf
                                            <button type="submit" class="btn btn-sm vc-btn-primary" title="Check-in">
                                                <i class="fas fa-sign-in-alt me-1"></i>Check-in
                                            </button>
                                        </form>
                                    @elseif($appt->trang_thai === \App\Models\LichHen::STATUS_CHECKED_IN_VN)
                                        <span class="badge bg-warning">Đang chờ</span>
                                    @elseif($appt->trang_thai === \App\Models\LichHen::STATUS_IN_PROGRESS_VN)
                                        @if($appt->benhAn)
                                            <a href="{{ route('doctor.benhan.edit', $appt->benhAn->id) }}"
                                               class="btn btn-sm btn-outline-success">
                                                <i class="fas fa-edit me-1"></i>Khám
                                            </a>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Start examination confirmation
    document.querySelectorAll('.start-exam-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Bắt đầu khám bệnh nhân này?')) {
                e.preventDefault();
            }
        });
    });

    // Check-in confirmation
    document.querySelectorAll('.checkin-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Check-in bệnh nhân này?')) {
                e.preventDefault();
            }
        });
    });

    // Auto-reload every 60 seconds to update queue
    setInterval(function() {
        // Only reload if not editing
        if (!document.activeElement || document.activeElement.tagName !== 'INPUT') {
            location.reload();
        }
    }, 60000);
});
</script>
@endpush

@endsection
