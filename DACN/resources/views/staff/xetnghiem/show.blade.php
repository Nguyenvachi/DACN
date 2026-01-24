{{-- Parent file: resources/views/layouts/staff.blade.php --}}
@extends('layouts.staff')

@section('title', 'Chi Tiết Xét Nghiệm')

@section('content')
<div class="container-fluid py-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                @if($xetNghiem->trang_thai === 'completed')
                    <a href="{{ route('staff.xetnghiem.completed') }}">Đã hoàn thành</a>
                @else
                    <a href="{{ route('staff.xetnghiem.pending') }}">Chờ xử lý</a>
                @endif
            </li>
            <li class="breadcrumb-item active">Chi tiết #{{ $xetNghiem->id }}</li>
        </ol>
    </nav>

    <div class="row">
        {{-- Main Content --}}
        <div class="col-lg-8">
            {{-- Test Info Card --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-vial text-primary"></i>
                            Thông Tin Xét Nghiệm #{{ $xetNghiem->id }}
                        </h5>
                        <span class="badge {{ $xetNghiem->getStatusBadgeColor() }} fs-6">
                            <i class="{{ $xetNghiem->getStatusIcon() }}"></i>
                            {{ $xetNghiem->getTrangThaiTextAttribute() }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Loại xét nghiệm</label>
                            <div class="fw-semibold">{{ $xetNghiem->loai_xet_nghiem }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Ngày chỉ định</label>
                            <div class="fw-semibold">
                                {{ $xetNghiem->ngay_chi_dinh->format('d/m/Y H:i') }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Ngày thực hiện</label>
                            <div class="fw-semibold">
                                @if($xetNghiem->ngay_thuc_hien)
                                    {{ $xetNghiem->ngay_thuc_hien->format('d/m/Y H:i') }}
                                @else
                                    <span class="text-muted">Chưa thực hiện</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Mức độ ưu tiên</label>
                            <div>
                                @if($xetNghiem->muc_do_uu_tien === 'urgent')
                                    <span class="badge bg-danger">
                                        <i class="fas fa-exclamation-triangle"></i> Khẩn cấp
                                    </span>
                                @elseif($xetNghiem->muc_do_uu_tien === 'high')
                                    <span class="badge bg-warning">
                                        <i class="fas fa-exclamation-circle"></i> Cao
                                    </span>
                                @else
                                    <span class="badge bg-info">
                                        <i class="fas fa-info-circle"></i> Bình thường
                                    </span>
                                @endif
                            </div>
                        </div>
                        @if($xetNghiem->chi_tiet_chi_dinh)
                        <div class="col-12">
                            <label class="text-muted small mb-1">Chi tiết chỉ định</label>
                            <div class="p-3 bg-light rounded">{{ $xetNghiem->chi_tiet_chi_dinh }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Result Card --}}
            @if($xetNghiem->hasResult())
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-file-medical text-success"></i>
                        Kết Quả Xét Nghiệm
                    </h5>
                </div>
                <div class="card-body">
                    @if($xetNghiem->file_path)
                    <div class="d-flex align-items-center p-3 bg-light rounded mb-3">
                        <i class="fas fa-file-pdf text-danger fa-2x me-3"></i>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">File kết quả</div>
                            <small class="text-muted">{{ basename($xetNghiem->file_path) }}</small>
                        </div>
                        <a href="{{ $xetNghiem->getDownloadUrl() }}"
                           class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-download"></i> Tải xuống
                        </a>
                    </div>
                    @endif

                    @if($xetNghiem->ket_qua)
                    <div class="mb-3">
                        <label class="text-muted small mb-1">Kết quả</label>
                        <div class="p-3 bg-light rounded">{{ $xetNghiem->ket_qua }}</div>
                    </div>
                    @endif

                    @if($xetNghiem->nhan_xet)
                    <div class="mb-3">
                        <label class="text-muted small mb-1">Nhận xét kỹ thuật</label>
                        <div class="p-3 bg-light rounded">{{ $xetNghiem->nhan_xet }}</div>
                    </div>
                    @endif

                    @if($xetNghiem->nhan_xet_bac_si)
                    <div>
                        <label class="text-muted small mb-1">Nhận xét bác sĩ</label>
                        <div class="p-3 bg-info bg-opacity-10 rounded border border-info">
                            <i class="fas fa-user-md text-primary me-2"></i>
                            {{ $xetNghiem->nhan_xet_bac_si }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @else
            {{-- No result yet - show upload button --}}
            @if($xetNghiem->canUploadResult())
            <div class="card border-0 shadow-sm mb-4 border-warning">
                <div class="card-body text-center py-5">
                    <i class="fas fa-upload fa-3x text-warning mb-3"></i>
                    <h5>Chưa có kết quả</h5>
                    <p class="text-muted mb-3">Xét nghiệm này chưa có file kết quả</p>
                    <a href="{{ route('staff.xetnghiem.upload.form', $xetNghiem) }}"
                       class="btn btn-primary">
                        <i class="fas fa-upload"></i> Upload kết quả
                    </a>
                </div>
            </div>
            @endif
            @endif

            {{-- Medical Record Info --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-file-medical-alt text-info"></i>
                        Thông Tin Bệnh Án
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Mã bệnh án</label>
                            <div class="fw-semibold">#{{ $xetNghiem->benh_an_id }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Ngày khám</label>
                            <div class="fw-semibold">
                                {{ $xetNghiem->benhAn->ngay_kham->format('d/m/Y H:i') }}
                            </div>
                        </div>
                        @if($xetNghiem->benhAn->chan_doan)
                        <div class="col-12">
                            <label class="text-muted small mb-1">Chẩn đoán</label>
                            <div class="p-3 bg-light rounded">{{ $xetNghiem->benhAn->chan_doan }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Patient Info --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0">
                        <i class="fas fa-user text-primary"></i>
                        Thông Tin Bệnh Nhân
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($xetNghiem->benhAn->user->avatar)
                            <img src="{{ $xetNghiem->benhAn->user->avatar }}"
                                 class="rounded-circle mb-2"
                                 style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-primary text-white mx-auto mb-2 d-flex align-items-center justify-content-center"
                                 style="width: 80px; height: 80px;">
                                <i class="fas fa-user fa-2x"></i>
                            </div>
                        @endif
                        <h6 class="mb-0">{{ $xetNghiem->benhAn->user->ho_ten }}</h6>
                    </div>
                    <div class="small">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Mã BN:</span>
                            <span class="fw-semibold">#{{ $xetNghiem->benhAn->user->id }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Giới tính:</span>
                            <span>{{ $xetNghiem->benhAn->user->gioi_tinh === 'nam' ? 'Nam' : 'Nữ' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Ngày sinh:</span>
                            <span>{{ $xetNghiem->benhAn->user->ngay_sinh ? $xetNghiem->benhAn->user->ngay_sinh->format('d/m/Y') : '--' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Điện thoại:</span>
                            <span>{{ $xetNghiem->benhAn->user->so_dien_thoai }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Email:</span>
                            <span class="text-truncate" style="max-width: 150px;">
                                {{ $xetNghiem->benhAn->user->email }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Doctor Info --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0">
                        <i class="fas fa-user-md text-success"></i>
                        Bác Sĩ Chỉ Định
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($xetNghiem->bacSi->user->avatar)
                            <img src="{{ $xetNghiem->bacSi->user->avatar }}"
                                 class="rounded-circle mb-2"
                                 style="width: 60px; height: 60px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-success text-white mx-auto mb-2 d-flex align-items-center justify-content-center"
                                 style="width: 60px; height: 60px;">
                                <i class="fas fa-user-md"></i>
                            </div>
                        @endif
                        <h6 class="mb-0">{{ $xetNghiem->bacSi->user->ho_ten }}</h6>
                        <small class="text-muted">{{ $xetNghiem->bacSi->chuyenKhoa->ten_chuyen_khoa }}</small>
                    </div>
                    <div class="small">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Mã BS:</span>
                            <span class="fw-semibold">#{{ $xetNghiem->bacSi->id }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Điện thoại:</span>
                            <span>{{ $xetNghiem->bacSi->user->so_dien_thoai }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Email:</span>
                            <span class="text-truncate" style="max-width: 150px;">
                                {{ $xetNghiem->bacSi->user->email }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            @if($xetNghiem->canUploadResult())
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="mb-3">Thao tác</h6>

                    @if($xetNghiem->trang_thai === 'pending')
                    <form action="{{ route('staff.xetnghiem.mark-processing', $xetNghiem) }}"
                          method="POST" class="mb-2">
                        @csrf
                        <button type="submit" class="btn btn-outline-warning w-100">
                            <i class="fas fa-spinner"></i> Bắt đầu xử lý
                        </button>
                    </form>
                    @endif

                    <a href="{{ route('staff.xetnghiem.upload.form', $xetNghiem) }}"
                       class="btn btn-primary w-100">
                        <i class="fas fa-upload"></i> Upload kết quả
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
