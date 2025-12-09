@extends('layouts.doctor')

@section('title', 'Chi tiết lịch hẹn')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: #1f2937;">
                <i class="fas fa-calendar-alt me-2" style="color: #3b82f6;"></i>
                Chi tiết lịch hẹn #{{ $lichHen->id }}
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('doctor.lichhen.confirmed') }}">Lịch đã xác nhận</a></li>
                    <li class="breadcrumb-item active">Chi tiết</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('doctor.lichhen.confirmed') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    <div class="row g-4">
        {{-- Thông tin lịch hẹn --}}
        <div class="col-lg-8">
            {{-- Trạng thái & Thời gian --}}
            <div class="card vc-card mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-info-circle me-2 text-primary"></i>
                        Thông tin lịch hẹn
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Trạng thái</label>
                            <div>
                                @if($lichHen->trang_thai === \App\Models\LichHen::STATUS_PENDING_VN)
                                    <span class="badge bg-warning fs-6">
                                        <i class="fas fa-clock me-1"></i>{{ $lichHen->trang_thai }}
                                    </span>
                                @elseif($lichHen->trang_thai === \App\Models\LichHen::STATUS_CONFIRMED_VN)
                                    <span class="badge bg-success fs-6">
                                        <i class="fas fa-check-circle me-1"></i>{{ $lichHen->trang_thai }}
                                    </span>
                                @elseif($lichHen->trang_thai === \App\Models\LichHen::STATUS_CHECKED_IN_VN)
                                    <span class="badge bg-info fs-6">
                                        <i class="fas fa-user-check me-1"></i>{{ $lichHen->trang_thai }}
                                    </span>
                                @elseif($lichHen->trang_thai === \App\Models\LichHen::STATUS_IN_PROGRESS_VN)
                                    <span class="badge bg-primary fs-6">
                                        <i class="fas fa-stethoscope me-1"></i>{{ $lichHen->trang_thai }}
                                    </span>
                                @elseif($lichHen->trang_thai === \App\Models\LichHen::STATUS_COMPLETED_VN)
                                    <span class="badge bg-success fs-6">
                                        <i class="fas fa-check-double me-1"></i>{{ $lichHen->trang_thai }}
                                    </span>
                                @elseif($lichHen->trang_thai === \App\Models\LichHen::STATUS_CANCELLED_VN)
                                    <span class="badge bg-danger fs-6">
                                        <i class="fas fa-times-circle me-1"></i>{{ $lichHen->trang_thai }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary fs-6">{{ $lichHen->trang_thai }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Thanh toán</label>
                            <div>
                                @if($lichHen->payment_status === 'paid')
                                    <span class="badge bg-success fs-6">
                                        <i class="fas fa-check-circle me-1"></i>Đã thanh toán
                                    </span>
                                @else
                                    <span class="badge bg-warning fs-6">
                                        <i class="fas fa-clock me-1"></i>Chưa thanh toán
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Ngày hẹn</label>
                            <div class="fw-bold" style="color: #1f2937;">
                                <i class="fas fa-calendar text-success me-2"></i>
                                {{ \Carbon\Carbon::parse($lichHen->ngay_hen)->format('d/m/Y') }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Giờ hẹn</label>
                            <div class="fw-bold" style="color: #1f2937;">
                                <i class="fas fa-clock text-primary me-2"></i>
                                {{ \Carbon\Carbon::parse($lichHen->thoi_gian_hen)->format('H:i') }}
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="text-muted small mb-1">Dịch vụ</label>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-light text-dark border px-3 py-2 fs-6">
                                    {{ $lichHen->dichVu->ten_dich_vu ?? 'N/A' }}
                                </span>
                                <span class="ms-3 fw-bold text-success">
                                    {{ number_format($lichHen->dichVu->gia ?? 0, 0, ',', '.') }} đ
                                </span>
                            </div>
                        </div>
                        @if($lichHen->ly_do_kham)
                        <div class="col-12">
                            <label class="text-muted small mb-1">Lý do khám</label>
                            <div class="p-3 bg-light rounded">
                                {{ $lichHen->ly_do_kham }}
                            </div>
                        </div>
                        @endif
                        @if($lichHen->ghi_chu)
                        <div class="col-12">
                            <label class="text-muted small mb-1">Ghi chú</label>
                            <div class="p-3 bg-light rounded">
                                {{ $lichHen->ghi_chu }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Thông tin bệnh nhân --}}
            <div class="card vc-card mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-user me-2 text-success"></i>
                        Thông tin bệnh nhân
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                             style="width: 60px; height: 60px; background: linear-gradient(135deg, #10b981, #059669); color: white; font-size: 24px; font-weight: 700;">
                            {{ strtoupper(substr($lichHen->user->name ?? 'N', 0, 1)) }}
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">{{ $lichHen->user->name ?? 'N/A' }}</h5>
                            <small class="text-muted">Mã BN: #{{ $lichHen->user->id }}</small>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Email</label>
                            <div>
                                <i class="fas fa-envelope text-muted me-2"></i>
                                {{ $lichHen->user->email ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Số điện thoại</label>
                            <div>
                                <i class="fas fa-phone text-muted me-2"></i>
                                {{ $lichHen->user->so_dien_thoai ?? 'N/A' }}
                            </div>
                        </div>
                        @if($lichHen->user->ngay_sinh)
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Ngày sinh</label>
                            <div>
                                <i class="fas fa-birthday-cake text-muted me-2"></i>
                                {{ \Carbon\Carbon::parse($lichHen->user->ngay_sinh)->format('d/m/Y') }}
                                <small class="text-muted">({{ \Carbon\Carbon::parse($lichHen->user->ngay_sinh)->age }} tuổi)</small>
                            </div>
                        </div>
                        @endif
                        @if($lichHen->user->gioi_tinh)
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Giới tính</label>
                            <div>
                                <i class="fas fa-{{ $lichHen->user->gioi_tinh === 'Nam' ? 'mars' : 'venus' }} text-muted me-2"></i>
                                {{ $lichHen->user->gioi_tinh }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Bệnh án (nếu có) --}}
            @if($lichHen->benhAn)
            <div class="card vc-card">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-file-medical me-2 text-danger"></i>
                        Bệnh án
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Bệnh án #{{ $lichHen->benhAn->id }}</h6>
                            <small class="text-muted">
                                Ngày tạo: {{ $lichHen->benhAn->created_at->format('d/m/Y H:i') }}
                            </small>
                        </div>
                        <a href="{{ route('benh_an.edit', $lichHen->benhAn->id) }}" class="btn btn-primary">
                            <i class="fas fa-eye me-2"></i>Xem bệnh án
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar actions --}}
        <div class="col-lg-4">
            {{-- Quick Actions --}}
            <div class="card vc-card mb-4">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-bolt me-2 text-warning"></i>
                        Hành động nhanh
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($lichHen->trang_thai === \App\Models\LichHen::STATUS_PENDING_VN)
                        <form action="{{ route('doctor.lichhen.confirm', $lichHen->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-check me-2"></i>Xác nhận lịch hẹn
                            </button>
                        </form>
                        <form action="{{ route('doctor.lichhen.reject', $lichHen->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-times me-2"></i>Từ chối
                            </button>
                        </form>
                        @endif

                        @if($lichHen->trang_thai === \App\Models\LichHen::STATUS_CONFIRMED_VN)
                        <a href="{{ route('doctor.benhan.create', ['lich_hen_id' => $lichHen->id, 'user_id' => $lichHen->user_id]) }}"
                           class="btn btn-primary w-100">
                            <i class="fas fa-file-medical me-2"></i>Tạo bệnh án
                        </a>
                        @endif

                        @if($lichHen->conversation)
                        <a href="{{ route('doctor.chat.show', $lichHen->conversation->id) }}"
                           class="btn btn-outline-success w-100">
                            <i class="fas fa-comments me-2"></i>Nhắn tin với bệnh nhân
                        </a>
                        @endif

                        @if($lichHen->benhAn)
                        <a href="{{ route('benh_an.edit', $lichHen->benhAn->id) }}"
                           class="btn btn-outline-primary w-100">
                            <i class="fas fa-edit me-2"></i>Chỉnh sửa bệnh án
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Timeline --}}
            <div class="card vc-card">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-history me-2 text-info"></i>
                        Lịch sử
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <small class="text-muted">{{ $lichHen->created_at->format('d/m/Y H:i') }}</small>
                                <p class="mb-0 small">Tạo lịch hẹn</p>
                            </div>
                        </div>
                        @if($lichHen->updated_at != $lichHen->created_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <small class="text-muted">{{ $lichHen->updated_at->format('d/m/Y H:i') }}</small>
                                <p class="mb-0 small">Cập nhật gần nhất</p>
                            </div>
                        </div>
                        @endif
                        @if($lichHen->benhAn)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-danger"></div>
                            <div class="timeline-content">
                                <small class="text-muted">{{ $lichHen->benhAn->created_at->format('d/m/Y H:i') }}</small>
                                <p class="mb-0 small">Tạo bệnh án #{{ $lichHen->benhAn->id }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    .timeline-item {
        position: relative;
        padding-bottom: 20px;
    }
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -23px;
        top: 8px;
        bottom: -12px;
        width: 2px;
        background: #e5e7eb;
    }
    .timeline-item:last-child::before {
        display: none;
    }
    .timeline-marker {
        position: absolute;
        left: -28px;
        top: 4px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid white;
        box-shadow: 0 0 0 2px currentColor;
    }
    .timeline-content {
        padding-left: 10px;
    }
</style>
@endpush
