@extends('layouts.admin')

@section('content')

<style>
    .stat-card {
        border-radius: 12px;
        border: 0;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
        transition: transform 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-5px);
    }
    .stat-icon {
        font-size: 2.5rem;
        opacity: 0.8;
    }
    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        margin: 10px 0;
    }
    .stat-label {
        color: #6c757d;
        font-size: 0.9rem;
    }
    .history-item {
        border-left: 3px solid #0d6efd;
        padding-left: 15px;
        margin-bottom: 15px;
    }
    .utilization-bar {
        height: 30px;
        border-radius: 8px;
        background: linear-gradient(90deg, #28a745 0%, #ffc107 70%, #dc3545 100%);
        position: relative;
        overflow: hidden;
    }
    .utilization-marker {
        position: absolute;
        width: 3px;
        height: 100%;
        background: #000;
        box-shadow: 0 0 5px rgba(0,0,0,0.5);
    }
</style>

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">
                <i class="bi bi-bar-chart-fill text-primary"></i>
                Thống kê phòng: <strong>{{ $phong->ten }}</strong>
            </h1>
            <small class="text-muted">
                <i class="bi bi-tag"></i> {{ $phong->loai }}
                @if($phong->vi_tri)
                    | <i class="bi bi-geo-alt"></i> {{ $phong->vi_tri }}
                @endif
            </small>
        </div>
        <div>
            <a href="{{ route('admin.phong.diagram') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Sơ đồ phòng
            </a>
            <a href="{{ route('admin.phong.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-list"></i> Danh sách
            </a>
        </div>
    </div>

    {{-- THỐNG KÊ CARDS --}}
    <div class="row g-3 mb-4">
        {{-- Tổng giờ làm việc --}}
        <div class="col-md-3">
            <div class="card stat-card bg-primary text-white">
                <div class="card-body text-center">
                    <div class="stat-icon">⏰</div>
                    <div class="stat-value">{{ $stats['total_work_hours'] ?? 0 }}</div>
                    <div class="stat-label text-white-50">Tổng giờ làm việc</div>
                </div>
            </div>
        </div>

        {{-- Số lịch hẹn --}}
        <div class="col-md-3">
            <div class="card stat-card bg-success text-white">
                <div class="card-body text-center">
                    <div class="stat-icon">📅</div>
                    <div class="stat-value">{{ $stats['total_appointments'] ?? 0 }}</div>
                    <div class="stat-label text-white-50">Lịch hẹn đã đặt</div>
                </div>
            </div>
        </div>

        {{-- Số bác sĩ --}}
        <div class="col-md-3">
            <div class="card stat-card bg-info text-white">
                <div class="card-body text-center">
                    <div class="stat-icon">👨‍⚕️</div>
                    <div class="stat-value">{{ $stats['total_doctors'] ?? 0 }}</div>
                    <div class="stat-label text-white-50">Bác sĩ sử dụng</div>
                </div>
            </div>
        </div>

        {{-- Tỷ lệ sử dụng --}}
        <div class="col-md-3">
            <div class="card stat-card bg-warning text-dark">
                <div class="card-body text-center">
                    <div class="stat-icon">📊</div>
                    <div class="stat-value">{{ number_format($stats['utilization_percentage'] ?? 0, 1) }}%</div>
                    <div class="stat-label text-dark">Tỷ lệ sử dụng</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- THÔNG TIN CHI TIẾT --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Thông tin phòng</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Tên phòng:</th>
                            <td><strong>{{ $phong->ten }}</strong></td>
                        </tr>
                        <tr>
                            <th>Loại phòng:</th>
                            <td><span class="badge bg-secondary">{{ $phong->loai }}</span></td>
                        </tr>
                        <tr>
                            <th>Trạng thái:</th>
                            <td>{!! $phong->status_badge !!}</td>
                        </tr>
                        <tr>
                            <th>Vị trí:</th>
                            <td>{{ $phong->vi_tri ?? '--' }}</td>
                        </tr>
                        <tr>
                            <th>Diện tích:</th>
                            <td>{{ $phong->dien_tich ?? '--' }} m²</td>
                        </tr>
                        <tr>
                            <th>Sức chứa:</th>
                            <td>{{ $phong->suc_chua ?? '--' }} người</td>
                        </tr>
                    </table>

                    {{-- Thanh tỷ lệ sử dụng --}}
                    <div class="mt-4">
                        <label class="form-label fw-bold">Mức độ sử dụng:</label>
                        <div class="utilization-bar">
                            <div class="utilization-marker" style="left: {{ $stats['utilization_percentage'] ?? 0 }}%;"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <small class="text-success">0% (Rảnh)</small>
                            <small class="text-warning">50% (Bình thường)</small>
                            <small class="text-danger">100% (Quá tải)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- LỊCH SỬ SỬ DỤNG --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Lịch sử tuần này</h5>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @forelse($history as $item)
                        <div class="history-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong>{{ $item['doctor_name'] }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($item['date'])->format('d/m/Y') }}
                                        | <i class="bi bi-clock"></i> {{ $item['time_start'] }} - {{ $item['time_end'] }}
                                    </small>
                                </div>
                                <span class="badge bg-{{ $item['type'] === 'lich_lam_viec' ? 'primary' : 'success' }}">
                                    {{ $item['type'] === 'lich_lam_viec' ? 'Lịch làm' : 'Lịch hẹn' }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            <p>Chưa có lịch sử sử dụng</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- BÁC SĨ THƯỜNG DÙNG --}}
    @if(!empty($stats['doctors']))
    <div class="card mt-4">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="bi bi-people"></i> Bác sĩ thường sử dụng phòng này</h5>
        </div>
        <div class="card-body">
            <div class="row">
                @if(is_iterable($stats['doctors']))
                    @forelse($stats['doctors'] as $doctor)
                        <div class="col-md-4 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                         style="width: 50px; height: 50px;">
                                        <i class="bi bi-person-circle fs-4"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <strong>{{ data_get($doctor, 'name') ?? data_get($doctor, 'ho_ten') ?? data_get($doctor, 'ten') }}</strong>
                                    <br>
                                    <small class="text-muted">{{ data_get($doctor, 'count') ?? data_get($doctor, 'luot') ?? '—' }} lượt sử dụng</small>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">Chưa có dữ liệu</div>
                    @endforelse
                @else
                    <div class="col-12">
                        <p class="text-muted mb-0">Số bác sĩ sử dụng phòng: <strong>{{ $stats['doctors'] }}</strong></p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif

</div>

@endsection
