@extends('layouts.admin')

@section('title', 'Chi tiết Xét nghiệm #' . $xetNghiem->id)

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-dark">
                <i class="fas fa-microscope me-2 text-primary"></i>
                Chi tiết Xét nghiệm #{{ $xetNghiem->id }}
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.xetnghiem.index') }}">Xét nghiệm</a></li>
                    <li class="breadcrumb-item active">#{{ $xetNghiem->id }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.xetnghiem.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    <div class="row g-4">
        {{-- Thông tin chính --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-info-circle text-primary me-2"></i>Thông tin xét nghiệm
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small d-block mb-1">Mã xét nghiệm</label>
                            <strong class="text-primary fs-5">#{{ $xetNghiem->id }}</strong>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block mb-1">Ngày chỉ định</label>
                            <strong>{{ $xetNghiem->created_at->format('d/m/Y H:i') }}</strong>
                        </div>
                        <div class="col-md-12">
                            <label class="text-muted small d-block mb-1">Loại xét nghiệm</label>
                            <h5 class="mb-0">
                                <span class="badge bg-primary bg-opacity-10 text-primary p-2">
                                    <i class="fas fa-flask me-2"></i>{{ $xetNghiem->loai }}
                                </span>
                            </h5>
                        </div>
                        <div class="col-md-12">
                            <label class="text-muted small d-block mb-1">Trạng thái</label>
                            @if($xetNghiem->trang_thai === 'pending')
                                <span class="badge bg-warning p-2">
                                    <i class="fas fa-hourglass-start me-2"></i>Chờ thực hiện
                                </span>
                            @elseif($xetNghiem->trang_thai === 'processing')
                                <span class="badge bg-info p-2">
                                    <i class="fas fa-spinner me-2"></i>Đang xử lý
                                </span>
                            @else
                                <span class="badge bg-success p-2">
                                    <i class="fas fa-check-circle me-2"></i>Đã hoàn thành
                                </span>
                            @endif
                        </div>
                        @if($xetNghiem->mo_ta)
                        <div class="col-md-12">
                            <label class="text-muted small d-block mb-1">Mô tả</label>
                            <p class="mb-0">{{ $xetNghiem->mo_ta }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Kết quả --}}
            @if($xetNghiem->trang_thai === 'completed')
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-file-medical text-success me-2"></i>Kết quả xét nghiệm
                    </h5>
                </div>
                <div class="card-body">
                    @if($xetNghiem->file_path)
                    <div class="alert alert-success mb-3">
                        <i class="fas fa-check-circle me-2"></i>Đã có file kết quả
                    </div>
                    <a href="{{ route('admin.benhan.xetnghiem.download', $xetNghiem) }}" class="btn btn-primary">
                        <i class="fas fa-download me-2"></i>Tải file kết quả
                    </a>
                    @endif

                    @if($xetNghiem->ket_qua)
                    <div class="mt-3">
                        <label class="text-muted small d-block mb-2">Kết quả dạng text</label>
                        <div class="bg-light p-3 rounded">
                            {{ $xetNghiem->ket_qua }}
                        </div>
                    </div>
                    @endif

                    @if($xetNghiem->nhan_xet)
                    <div class="mt-3">
                        <label class="text-muted small d-block mb-2">Nhận xét của bác sĩ</label>
                        <div class="bg-light p-3 rounded">
                            {{ $xetNghiem->nhan_xet }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Thông tin bệnh nhân --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-user text-info me-2"></i>Bệnh nhân
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="avatar-lg bg-light rounded-circle mx-auto d-flex align-items-center justify-content-center mb-2">
                            <i class="fas fa-user fa-2x text-muted"></i>
                        </div>
                        <h5 class="mb-1">{{ $xetNghiem->benhAn->user->name ?? 'N/A' }}</h5>
                        <p class="text-muted small mb-0">{{ $xetNghiem->benhAn->user->email ?? '' }}</p>
                    </div>
                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Số điện thoại:</span>
                            <strong class="small">{{ $xetNghiem->benhAn->user->so_dien_thoai ?? 'N/A' }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">Mã bệnh án:</span>
                            <strong class="small text-primary">#{{ $xetNghiem->benh_an_id }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Thông tin bác sĩ --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-user-md text-primary me-2"></i>Bác sĩ chỉ định
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md bg-primary bg-opacity-10 rounded-circle me-3 d-flex align-items-center justify-content-center">
                            <i class="fas fa-user-md text-primary fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $xetNghiem->bacSi->user->name ?? 'N/A' }}</h6>
                            <small class="text-muted">{{ $xetNghiem->bacSi->chuyen_khoa ?? 'Bác sĩ' }}</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Timeline --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-history text-secondary me-2"></i>Lịch sử
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <small class="text-muted">{{ $xetNghiem->created_at->format('d/m/Y H:i') }}</small>
                                <p class="mb-0 small">Bác sĩ chỉ định xét nghiệm</p>
                            </div>
                        </div>
                        @if($xetNghiem->trang_thai === 'completed')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <small class="text-muted">{{ $xetNghiem->updated_at->format('d/m/Y H:i') }}</small>
                                <p class="mb-0 small">Hoàn thành xét nghiệm</p>
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
.avatar-lg {
    width: 80px;
    height: 80px;
}
.avatar-md {
    width: 50px;
    height: 50px;
}
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
    background: #e9ecef;
}
.timeline-item:last-child::before {
    display: none;
}
.timeline-marker {
    position: absolute;
    left: -28px;
    top: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #e9ecef;
}
</style>
@endpush
