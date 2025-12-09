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
                            <small class="text-muted">Đang chờ</small>
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
                            <small class="text-muted">Đang khám</small>
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
                            <small class="text-muted">Đã xác nhận hôm nay</small>
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
                            <small class="text-muted">Thời gian chờ TB</small>
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
                    <div class="col-md-6 col-lg-4">
                        <div class="card border h-100 shadow-sm" style="border-left: 4px solid #10b981 !important;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="me-2" style="width: 45px; height: 45px; border-radius: 50%; background: linear-gradient(135deg, #10b981, #059669); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                                            {{ strtoupper(substr($appt->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $appt->user->name }}</div>
                                            <small class="text-muted">{{ $appt->user->email }}</small>
                                        </div>
                                    </div>
                                    <span class="status-badge status-in-progress">
                                        <i class="fas fa-spinner fa-spin me-1"></i>Đang khám
                                    </span>
                                </div>

                                <div class="mb-2">
                                    <small class="text-muted d-block">Dịch vụ:</small>
                                    <span class="badge bg-light text-dark border">
                                        {{ $appt->dichVu->ten_dich_vu ?? 'N/A' }}
                                    </span>
                                </div>

                                <div class="mb-3">
                                    <small class="text-muted d-block">Thời gian bắt đầu:</small>
                                    <div class="fw-semibold">
                                        <i class="far fa-clock me-1" style="color: #10b981;"></i>
                                        {{ $appt->updated_at ? \Carbon\Carbon::parse($appt->updated_at)->format('H:i') : 'N/A' }}
                                    </div>
                                </div>

                                @if($appt->benhAn)
                                    <a href="{{ route('doctor.benhan.edit', $appt->benhAn->id) }}"
                                       class="btn btn-outline-success w-100">
                                        <i class="fas fa-edit me-1"></i>
                                        Tiếp tục khám
                                    </a>
                                @else
                                    <form action="{{ route('doctor.queue.start', $appt->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn vc-btn-primary w-100">
                                            <i class="fas fa-stethoscope me-1"></i>
                                            Tạo bệnh án
                                        </button>
                                    </form>
                                @endif
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
