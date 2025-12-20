@extends('layouts.admin')

@section('title', 'Thống kê X-Quang')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-dark">
                <i class="fas fa-chart-line me-2 text-primary"></i>
                Thống kê & Báo cáo X-Quang
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.xquang.index') }}">X-Quang</a></li>
                    <li class="breadcrumb-item active">Thống kê</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.xquang.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    {{-- Thống kê theo trạng thái --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-warning bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-warning fw-bold mb-1 small">CHỜ THỰC HIỆN</p>
                            <h2 class="mb-0 fw-bold">{{ number_format($statusStats['pending']) }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-hourglass-start fa-3x text-warning opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-info bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-info fw-bold mb-1 small">ĐANG XỬ LÝ</p>
                            <h2 class="mb-0 fw-bold">{{ number_format($statusStats['processing']) }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-spinner fa-3x text-info opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-success bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-success fw-bold mb-1 small">ĐÃ HOÀN THÀNH</p>
                            <h2 class="mb-0 fw-bold">{{ number_format($statusStats['completed']) }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-check-circle fa-3x text-success opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Biểu đồ theo tháng --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-chart-bar text-primary me-2"></i>Xu hướng 12 tháng gần nhất
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" height="80"></canvas>
                </div>
            </div>
        </div>

        {{-- Thống kê bổ sung --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-clock text-info me-2"></i>Thời gian xử lý
                    </h6>
                </div>
                <div class="card-body text-center">
                    <h2 class="mb-1 fw-bold text-primary">{{ number_format($avgProcessingTime ?? 0, 1) }}</h2>
                    <p class="text-muted mb-0">Giờ trung bình</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-percentage text-success me-2"></i>Tỷ lệ hoàn thành
                    </h6>
                </div>
                <div class="card-body text-center">
                    @php
                        $total = $statusStats['pending'] + $statusStats['processing'] + $statusStats['completed'];
                        $completionRate = $total > 0 ? ($statusStats['completed'] / $total * 100) : 0;
                    @endphp
                    <h2 class="mb-1 fw-bold text-success">{{ number_format($completionRate, 1) }}%</h2>
                    <p class="text-muted mb-0">X-Quang đã hoàn thành</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-2">
        {{-- Top bác sĩ --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-user-md text-primary me-2"></i>Top 10 Bác sĩ chỉ định nhiều nhất
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="xQuangTopDoctorsTable" class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Bác sĩ</th>
                                    <th class="text-end">Số lượng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topDoctors as $index => $doctor)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $doctor->name }}</td>
                                    <td class="text-end">
                                        <span class="badge bg-primary">{{ number_format($doctor->total) }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Top loại --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-x-ray text-success me-2"></i>Top 10 Loại X-Quang phổ biến
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="xQuangTopTypesTable" class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Loại</th>
                                    <th class="text-end">Số lượng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topTypes as $index => $type)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $type->loai }}</td>
                                    <td class="text-end">
                                        <span class="badge bg-success">{{ number_format($type->total) }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<x-datatable-script
    tableId="xQuangTopDoctorsTable"
    config='{"paging": false, "info": false, "searching": false, "lengthChange": false, "order": [[2, "desc"]], "columnDefs": [{"orderable": false, "targets": 0}]}'
/>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const monthlyData = @json($monthlyStats);

    const ctx = document.getElementById('monthlyChart');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthlyData.map(item => item.month),
            datasets: [
                {
                    label: 'Tổng X-Quang',
                    data: monthlyData.map(item => item.total),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Đã hoàn thành',
                    data: monthlyData.map(item => item.completed),
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'top' },
                tooltip: { mode: 'index', intersect: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 10 }
                }
            }
        }
    });

    // DataTables: Top loại X-Quang
    if (window.jQuery && $.fn.DataTable && !$.fn.DataTable.isDataTable('#xQuangTopTypesTable')) {
        $('#xQuangTopTypesTable').DataTable({
            paging: false,
            info: false,
            searching: false,
            lengthChange: false,
            responsive: true,
            order: [[2, 'desc']],
            columnDefs: [{ orderable: false, targets: 0 }]
        });
    }
});
</script>
@endpush
