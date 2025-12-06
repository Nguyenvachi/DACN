@extends('layouts.patient-modern')

@section('title', 'Quản Lý Sức Khỏe')
@section('page-title', 'Dashboard Bệnh Nhân')
@section('page-subtitle', 'Theo dõi sức khỏe và quản lý lịch hẹn của bạn')

@section('content')
<div class="container-fluid py-4">
    {{-- Welcome Banner --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <div class="card-body p-4 text-white">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="fw-bold mb-2">
                                <i class="fas fa-hand-sparkles me-2"></i>Chào mừng trở lại, {{ auth()->user()->name }}!
                            </h2>
                            <p class="mb-0 opacity-90 fs-5">
                                Hôm nay là {{ now()->locale('vi')->isoFormat('dddd, D THÁNG M, YYYY') }}
                            </p>
                            @if($statistics['upcoming_appointments'] > 0)
                            <div class="mt-3 p-3 bg-white bg-opacity-25 rounded d-inline-block">
                                <i class="fas fa-calendar-check me-2"></i>
                                Bạn có <strong>{{ $statistics['upcoming_appointments'] }}</strong> lịch hẹn sắp tới
                            </div>
                            @endif
                        </div>
                        <div class="col-md-4 text-center d-none d-md-block">
                            <i class="fas fa-hospital-user" style="font-size: 100px; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg rounded-circle bg-primary bg-opacity-10">
                                <i class="fas fa-calendar-check fa-2x text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1 small">Lịch Hẹn Sắp Tới</p>
                            <h3 class="mb-0 fw-bold">{{ $statistics['upcoming_appointments'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="{{ route('patient.lichhen.index') }}" class="btn btn-sm btn-outline-primary w-100">
                        <i class="fas fa-arrow-right me-1"></i>Xem tất cả
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg rounded-circle bg-success bg-opacity-10">
                                <i class="fas fa-file-medical fa-2x text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1 small">Hồ Sơ Bệnh Án</p>
                            <h3 class="mb-0 fw-bold">{{ $statistics['total_medical_records'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="{{ route('patient.benhan.index') }}" class="btn btn-sm btn-outline-success w-100">
                        <i class="fas fa-arrow-right me-1"></i>Xem chi tiết
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg rounded-circle bg-warning bg-opacity-10">
                                <i class="fas fa-file-invoice-dollar fa-2x text-warning"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1 small">Hóa Đơn Chưa Thanh Toán</p>
                            <h3 class="mb-0 fw-bold">{{ $statistics['unpaid_invoices'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="{{ route('patient.hoadon.index') }}" class="btn btn-sm btn-outline-warning w-100">
                        <i class="fas fa-arrow-right me-1"></i>Thanh toán
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg rounded-circle bg-info bg-opacity-10">
                                <i class="fas fa-flask fa-2x text-info"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1 small">Xét Nghiệm</p>
                            <h3 class="mb-0 fw-bold">{{ $statistics['total_tests'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="{{ route('patient.xetnghiem.index') }}" class="btn btn-sm btn-outline-info w-100">
                        <i class="fas fa-arrow-right me-1"></i>Xem kết quả
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Left Column --}}
        <div class="col-xl-8">
            {{-- Lịch Hẹn Sắp Tới --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-calendar-alt me-2 text-primary"></i>Lịch Hẹn Sắp Tới
                        </h5>
                        <a href="{{ route('public.bacsi.index') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i>Đặt Lịch Mới
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($upcomingAppointments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Ngày & Giờ</th>
                                    <th>Bác Sĩ</th>
                                    <th>Dịch Vụ</th>
                                    <th>Trạng Thái</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upcomingAppointments as $appointment)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ \Carbon\Carbon::parse($appointment->ngay_hen)->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $appointment->thoi_gian_hen }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $appointment->bacSi->avatar_url ?? asset('images/default-avatar.svg') }}"
                                                 alt="Doctor" class="rounded-circle me-2" width="40" height="40">
                                            <div>
                                                <div class="fw-semibold">{{ $appointment->bacSi->ho_ten }}</div>
                                                <small class="text-muted">{{ $appointment->bacSi->chuyen_khoa }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $appointment->dichVu->ten_dich_vu ?? 'N/A' }}</td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'confirmed' => 'success',
                                                'completed' => 'info',
                                                'cancelled' => 'danger',
                                            ];
                                            $color = $statusColors[$appointment->trang_thai] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $color }}">{{ ucfirst($appointment->trang_thai) }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('patient.lichhen.show', $appointment->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="p-5 text-center text-muted">
                        <i class="fas fa-calendar-times fa-3x mb-3 opacity-50"></i>
                        <p class="mb-3">Bạn chưa có lịch hẹn nào sắp tới</p>
                        <a href="{{ route('public.bacsi.index') }}" class="btn btn-primary">
                            <i class="fas fa-calendar-plus me-2"></i>Đặt Lịch Ngay
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Biểu Đồ Lịch Hẹn --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-chart-line me-2 text-success"></i>Thống Kê Lịch Hẹn 6 Tháng
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="appointmentChart" height="80"></canvas>
                </div>
            </div>

            {{-- Bệnh Án Gần Đây --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-notes-medical me-2 text-success"></i>Bệnh Án Gần Đây
                        </h5>
                        <a href="{{ route('patient.benhan.index') }}" class="text-primary text-decoration-none">
                            Xem tất cả <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($recentMedicalRecords->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentMedicalRecords as $record)
                        <a href="{{ route('patient.benhan.show', $record->id) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <div class="avatar rounded-circle bg-success bg-opacity-10">
                                        <i class="fas fa-stethoscope text-success"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-1">{{ $record->dichVu->ten_dich_vu ?? 'Khám bệnh' }}</h6>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($record->ngay_kham)->format('d/m/Y') }}</small>
                                    </div>
                                    <p class="mb-1 text-muted small">BS. {{ $record->bacSi->ho_ten }}</p>
                                    <p class="mb-0 small text-truncate">{{ Str::limit($record->chan_doan, 100) }}</p>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    @else
                    <div class="p-4 text-center text-muted">
                        <i class="fas fa-notes-medical fa-2x mb-2 opacity-50"></i>
                        <p class="mb-0">Chưa có bệnh án nào</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="col-xl-4">
            {{-- Chỉ Số Sức Khỏe --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-heartbeat me-2 text-danger"></i>Chỉ Số Sức Khỏe
                    </h5>
                </div>
                <div class="card-body">
                    @if($profile)
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="p-3 bg-light rounded text-center">
                                <i class="fas fa-weight fa-2x text-primary mb-2"></i>
                                <div class="fw-bold">{{ $healthStats['weight'] ?? '--' }} kg</div>
                                <small class="text-muted">Cân nặng</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light rounded text-center">
                                <i class="fas fa-ruler-vertical fa-2x text-success mb-2"></i>
                                <div class="fw-bold">{{ $healthStats['height'] ?? '--' }} cm</div>
                                <small class="text-muted">Chiều cao</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light rounded text-center">
                                <i class="fas fa-calculator fa-2x text-warning mb-2"></i>
                                <div class="fw-bold">{{ number_format($healthStats['bmi'] ?? 0, 1) }}</div>
                                <small class="text-muted">BMI</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light rounded text-center">
                                <i class="fas fa-tint fa-2x text-danger mb-2"></i>
                                <div class="fw-bold">{{ $healthStats['blood_type'] ?? '--' }}</div>
                                <small class="text-muted">Nhóm máu</small>
                            </div>
                        </div>
                    </div>

                    @if($healthStats['bmi'])
                    <div class="mt-3 p-3 bg-info bg-opacity-10 rounded">
                        <h6 class="mb-2"><i class="fas fa-info-circle me-2"></i>Phân Loại BMI</h6>
                        <p class="mb-0 fw-semibold">{{ $healthStats['bmi_category'] }}</p>
                    </div>
                    @endif

                    @if($healthStats['allergies_count'] > 0)
                    <div class="alert alert-warning mt-3 mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Lưu ý:</strong> Bạn có {{ $healthStats['allergies_count'] }} dị ứng đã ghi nhận
                    </div>
                    @endif
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-user-plus fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-3">Hồ sơ sức khỏe chưa được cập nhật</p>
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Cập Nhật Hồ Sơ
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Hóa Đơn Chưa Thanh Toán --}}
            @if($unpaidInvoices->count() > 0)
            <div class="card border-0 shadow-sm mb-4 border-start border-warning border-3">
                <div class="card-header bg-warning bg-opacity-10 border-0 pt-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-exclamation-circle me-2 text-warning"></i>Hóa Đơn Cần Thanh Toán
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($unpaidInvoices as $invoice)
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <div class="fw-semibold">#{{ $invoice->id }}</div>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($invoice->created_at)->format('d/m/Y') }}</small>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-warning">{{ number_format($invoice->tong_tien) }}đ</div>
                            <a href="{{ route('patient.hoadon.show', $invoice->id) }}" class="btn btn-sm btn-warning mt-1">
                                Thanh toán
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Quick Actions --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-bolt me-2 text-primary"></i>Thao Tác Nhanh
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('public.bacsi.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-calendar-plus me-2"></i>Đặt Lịch Khám
                        </a>
                        <a href="{{ route('patient.donthuoc.index') }}" class="btn btn-outline-success">
                            <i class="fas fa-prescription me-2"></i>Đơn Thuốc
                        </a>
                        <a href="{{ route('patient.xetnghiem.index') }}" class="btn btn-outline-info">
                            <i class="fas fa-vial me-2"></i>Xét Nghiệm
                        </a>
                        <a href="{{ route('patient.chat.index') }}" class="btn btn-outline-warning">
                            <i class="fas fa-comments me-2"></i>Tư Vấn Online
                        </a>
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-user-cog me-2"></i>Cài Đặt
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.hover-lift {
    transition: transform 0.3s, box-shadow 0.3s;
}

.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}

.avatar {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-lg {
    width: 60px;
    height: 60px;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Appointment Chart
    const ctx = document.getElementById('appointmentChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($appointmentChartData['labels']),
                datasets: [
                    {
                        label: 'Hoàn thành',
                        data: @json($appointmentChartData['completed']),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Đã hủy',
                        data: @json($appointmentChartData['cancelled']),
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
@endsection
