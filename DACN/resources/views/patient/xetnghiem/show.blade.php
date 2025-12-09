@extends('layouts.patient-modern')

@section('title', 'Chi Tiết Kết Quả Xét Nghiệm')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('patient.xetnghiem.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
                <i class="fas fa-arrow-left me-1"></i>Quay lại
            </a>
            <h4 class="mb-0">
                <i class="fas fa-flask me-2"></i>Kết Quả Xét Nghiệm #{{ str_pad($xetNghiem->id, 5, '0', STR_PAD_LEFT) }}
            </h4>
        </div>
        @if($xetNghiem->file_path)
            <a href="{{ route('patient.benhan.xetnghiem.download', $xetNghiem) }}" class="btn btn-success">
                <i class="fas fa-download me-2"></i>Tải File
            </a>
        @endif
    </div>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông Tin Xét Nghiệm</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <p class="text-muted small mb-1">Loại xét nghiệm</p>
                            <p class="fw-bold mb-0">
                                <i class="fas fa-vial me-2 text-primary"></i>
                                {{ $xetNghiem->loai_xet_nghiem }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="text-muted small mb-1">Ngày xét nghiệm</p>
                            <p class="fw-bold mb-0">
                                <i class="fas fa-calendar me-2 text-success"></i>
                                {{ \Carbon\Carbon::parse($xetNghiem->created_at)->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="text-muted small mb-1">Bác sĩ chỉ định</p>
                            <p class="fw-bold mb-0">
                                <i class="fas fa-user-md me-2 text-info"></i>
                                {{ $xetNghiem->bacSi->user->name ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="text-muted small mb-1">Trạng thái</p>
                            <span class="badge bg-success">Đã có kết quả</span>
                        </div>
                    </div>

                    @if($xetNghiem->mo_ta)
                        <div class="mb-4">
                            <h6 class="mb-2"><i class="fas fa-clipboard-list me-2"></i>Mô tả</h6>
                            <div class="p-3 bg-light rounded">
                                {{ $xetNghiem->mo_ta }}
                            </div>
                        </div>
                    @endif

                    @if($xetNghiem->ket_qua)
                        <div class="mb-4">
                            <h6 class="mb-2"><i class="fas fa-check-circle me-2 text-success"></i>Kết quả</h6>
                            <div class="alert alert-info">
                                {!! nl2br(e($xetNghiem->ket_qua)) !!}
                            </div>
                        </div>
                    @endif

                    @if($xetNghiem->ghi_chu)
                        <div class="mb-4">
                            <h6 class="mb-2"><i class="fas fa-notes-medical me-2"></i>Ghi chú của bác sĩ</h6>
                            <div class="p-3 border-start border-4 border-warning bg-light">
                                {{ $xetNghiem->ghi_chu }}
                            </div>
                        </div>
                    @endif

                    @if($xetNghiem->benhAn)
                        <div class="mt-4 p-3 bg-light rounded">
                            <p class="mb-2"><strong><i class="fas fa-link me-2"></i>Liên quan đến bệnh án:</strong></p>
                            <a href="{{ route('patient.benhan.show', $xetNghiem->benhAn) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-file-medical me-1"></i>Xem bệnh án
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Hướng Dẫn</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            <small>Đọc kỹ kết quả xét nghiệm</small>
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-user-md text-success me-2"></i>
                            <small>Tham khảo ý kiến bác sĩ nếu cần</small>
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-file-download text-info me-2"></i>
                            <small>Tải xuống để lưu trữ</small>
                        </li>
                        <li>
                            <i class="fas fa-phone text-warning me-2"></i>
                            <small>Liên hệ nếu có thắc mắc</small>
                        </li>
                    </ul>
                </div>
            </div>

            @if($xetNghiem->file_path)
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-file me-2"></i>File Đính Kèm</h5>
                    </div>
                    <div class="card-body text-center">
                        <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                        <p class="mb-2">{{ basename($xetNghiem->file_path) }}</p>
                        <a href="{{ route('patient.benhan.xetnghiem.download', $xetNghiem) }}" class="btn btn-primary btn-sm w-100">
                            <i class="fas fa-download me-2"></i>Tải xuống
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
