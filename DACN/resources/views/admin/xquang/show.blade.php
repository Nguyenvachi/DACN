@extends('layouts.admin')

@section('title', 'Chi tiết X-Quang #' . $xQuang->id)

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-dark">
                <i class="fas fa-x-ray me-2 text-primary"></i>
                Chi tiết X-Quang #{{ $xQuang->id }}
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.xquang.index') }}">X-Quang</a></li>
                    <li class="breadcrumb-item active">#{{ $xQuang->id }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            @if($xQuang->trang_thai === 'completed' && $xQuang->file_path)
                <a href="{{ $xQuang->getDownloadUrl() }}" class="btn btn-success" target="_blank">
                    <i class="fas fa-download me-2"></i>Tải kết quả
                </a>
            @endif
            <a href="{{ route('admin.xquang.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-info-circle me-2"></i>Thông tin</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Loại:</strong> {{ $xQuang->loai }}</p>
                            <p class="mb-2"><strong>Ngày chỉ định:</strong> {{ optional($xQuang->ngay_chi_dinh ?? $xQuang->created_at)->format('d/m/Y H:i') }}</p>
                            <p class="mb-2"><strong>Giá:</strong> {{ number_format((float) $xQuang->gia, 0, ',', '.') }} đ</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Trạng thái:</strong>
                                @if($xQuang->trang_thai === 'pending')
                                    <span class="badge bg-warning"><i class="fas fa-hourglass-start me-1"></i>Chờ thực hiện</span>
                                @elseif($xQuang->trang_thai === 'processing')
                                    <span class="badge bg-info"><i class="fas fa-spinner me-1"></i>Đang xử lý</span>
                                @else
                                    <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Đã hoàn thành</span>
                                @endif
                            </p>
                            <p class="mb-2"><strong>Bệnh án:</strong> #BA{{ $xQuang->benh_an_id }}</p>
                            <p class="mb-2"><strong>Bác sĩ:</strong> {{ $xQuang->bacSi->user->name ?? 'N/A' }}</p>
                        </div>
                    </div>

                    @if($xQuang->mo_ta)
                        <hr>
                        <strong>Mô tả:</strong>
                        <div class="mt-2" style="white-space: pre-line;">{{ $xQuang->mo_ta }}</div>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-file-medical me-2"></i>Kết quả</h5>
                </div>
                <div class="card-body">
                    @if($xQuang->trang_thai === 'completed' && $xQuang->file_path)
                        @if($xQuang->ket_qua)
                            <div class="mb-3">
                                <strong>Nội dung kết quả:</strong>
                                <div class="mt-2" style="white-space: pre-line;">{{ $xQuang->ket_qua }}</div>
                            </div>
                        @endif
                        @if($xQuang->nhan_xet)
                            <div class="mb-3">
                                <strong>Nhận xét:</strong>
                                <div class="mt-2" style="white-space: pre-line;">{{ $xQuang->nhan_xet }}</div>
                            </div>
                        @endif

                        <div class="text-center">
                            <a href="{{ $xQuang->getDownloadUrl() }}" class="btn btn-success" target="_blank">
                                <i class="fas fa-download me-2"></i>Tải file kết quả
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-hourglass-half fa-2x text-warning mb-2"></i>
                            <p class="text-muted mb-0">Chưa có kết quả</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-user me-2"></i>Bệnh nhân</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2"><strong>Họ tên:</strong> {{ $xQuang->benhAn->user->name ?? 'N/A' }}</div>
                    <div class="mb-2"><strong>Email:</strong> {{ $xQuang->benhAn->user->email ?? 'N/A' }}</div>
                    <div class="mb-2"><strong>SĐT:</strong> {{ $xQuang->benhAn->user->so_dien_thoai ?? 'N/A' }}</div>

                    @if($xQuang->benhAn->lichHen)
                        <hr>
                        <div class="mb-2"><strong>Lịch hẹn:</strong> {{ optional($xQuang->benhAn->lichHen->ngay_hen)->format('d/m/Y') }}</div>
                        <div class="mb-2"><strong>Dịch vụ:</strong> {{ $xQuang->benhAn->lichHen->dichVu->ten_dich_vu ?? 'N/A' }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
