@extends('layouts.doctor')

@section('title', 'Chi tiết xét nghiệm #XN' . $xetNghiem->id)

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: #1f2937;">
                <i class="fas fa-flask me-2" style="color: #8b5cf6;"></i>
                Xét nghiệm #XN{{ $xetNghiem->id }}
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('doctor.xet-nghiem.index') }}">Xét nghiệm</a></li>
                    <li class="breadcrumb-item active">Chi tiết #XN{{ $xetNghiem->id }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('doctor.xet-nghiem.edit', $xetNghiem) }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Nhập kết quả
            </a>
            @if($xetNghiem->trang_thai === 'completed' && $xetNghiem->file_path)
            <a href="{{ route('doctor.xet-nghiem.download', $xetNghiem->id) }}"
               class="btn btn-success"
               target="_blank">
                <i class="fas fa-download me-2"></i>Tải kết quả
            </a>
            @endif
            <a href="{{ route('doctor.xet-nghiem.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        {{-- Thông tin xét nghiệm --}}
        <div class="col-lg-8">
            <div class="card vc-card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2" style="color: #8b5cf6;"></i>
                        Thông tin xét nghiệm
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Mã xét nghiệm:</strong> #XN{{ $xetNghiem->id }}</p>
                            <p class="mb-2"><strong>Loại xét nghiệm:</strong> {{ $xetNghiem->loai }}</p>
                            <p class="mb-2"><strong>Ngày chỉ định:</strong> {{ $xetNghiem->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Mã hồ sơ:</strong>
                                <a href="{{ route('doctor.benhan.edit', $xetNghiem->benh_an_id) }}" class="text-primary">
                                    HS-{{ str_pad($xetNghiem->benh_an_id, 4, '0', STR_PAD_LEFT) }}
                                </a>
                            </p>
                        </div>
                    </div>

                    @if($xetNghiem->mo_ta)
                    <div class="alert alert-info">
                        <strong><i class="fas fa-notes-medical me-2"></i>Chỉ định chi tiết:</strong>
                        <p class="mb-0 mt-2">{{ $xetNghiem->mo_ta }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Kết quả xét nghiệm --}}
            @if($xetNghiem->trang_thai === 'completed' && $xetNghiem->file_path)
            <div class="card vc-card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-file-medical me-2" style="color: #10b981;"></i>
                        Kết quả xét nghiệm
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center py-4">
                        <i class="fas fa-file-pdf fa-4x text-danger mb-3"></i>
                        <p class="mb-3">File kết quả đã sẵn sàng</p>
                        <a href="{{ route('doctor.xetnghiem.download', $xetNghiem->id) }}"
                           class="btn btn-lg btn-success"
                           target="_blank">
                            <i class="fas fa-download me-2"></i>Tải kết quả
                        </a>
                    </div>
                </div>
            </div>
            @else
            <div class="card vc-card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-hourglass-half fa-3x text-warning mb-3"></i>
                    <p class="text-muted">Đang chờ kỹ thuật viên thực hiện và upload kết quả</p>
                </div>
            </div>
            @endif
        </div>

        {{-- Thông tin bệnh nhân --}}
        <div class="col-lg-4">
            <div class="card vc-card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-user-injured me-2" style="color: #3b82f6;"></i>
                        Thông tin bệnh nhân
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Họ tên:</strong>
                        <p class="mb-0">{{ $xetNghiem->benhAn->user->name ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Mã BN:</strong>
                        <p class="mb-0">#{{ $xetNghiem->user_id }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Giới tính:</strong>
                        <p class="mb-0">{{ $xetNghiem->benhAn->user->gioi_tinh ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Ngày sinh:</strong>
                        <p class="mb-0">
                            @if($xetNghiem->benhAn->user->ngay_sinh)
                                {{ \Carbon\Carbon::parse($xetNghiem->benhAn->user->ngay_sinh)->format('d/m/Y') }}
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                    <div class="mb-3">
                        <strong>SĐT:</strong>
                        <p class="mb-0">{{ $xetNghiem->benhAn->user->so_dien_thoai ?? 'N/A' }}</p>
                    </div>

                    @if($xetNghiem->benhAn->lichHen)
                    <hr>
                    <div class="mb-3">
                        <strong>Lịch hẹn:</strong>
                        <p class="mb-0">{{ $xetNghiem->benhAn->lichHen->ngay_hen->format('d/m/Y') }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Dịch vụ:</strong>
                        <p class="mb-0">{{ $xetNghiem->benhAn->lichHen->dichVu->ten_dich_vu ?? 'N/A' }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .alert-info {
        background-color: #e0f2fe;
        border-color: #0ea5e9;
        color: #0c4a6e;
    }
</style>
@endpush
