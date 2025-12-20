@extends('layouts.staff')

@section('title', 'Chi tiết siêu âm')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">
                <i class="fas fa-ultrasound me-2 text-primary"></i>
                Chi tiết siêu âm #SA{{ $sieuAm->id }}
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('staff.sieuam.pending') }}">Siêu âm</a></li>
                    <li class="breadcrumb-item active">SA{{ $sieuAm->id }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('staff.sieuam.pending') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            {{-- Thông tin siêu âm --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-file-medical me-2"></i>Thông tin siêu âm</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Mã SA</small>
                            <span class="badge bg-gradient-primary fs-6">SA-{{ $sieuAm->id }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Trạng thái</small>
                            <span class="badge bg-{{ $sieuAm->trang_thai_badge_class }} fs-6">
                                {{ $sieuAm->trang_thai_text }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Loại siêu âm</small>
                            <strong>{{ $sieuAm->loai }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Giá</small>
                            <strong class="text-primary">{{ number_format($sieuAm->gia, 0, ',', '.') }}đ</strong>
                        </div>
                        @if($sieuAm->mo_ta)
                        <div class="col-12">
                            <small class="text-muted d-block mb-1">Yêu cầu từ bác sĩ</small>
                            <p class="mb-0">{{ $sieuAm->mo_ta }}</p>
                        </div>
                        @endif
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Ngày chỉ định</small>
                            {{ $sieuAm->created_at->format('d/m/Y H:i') }}
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Cập nhật lần cuối</small>
                            {{ $sieuAm->updated_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kết quả --}}
            @if($sieuAm->trang_thai === 'completed' && $sieuAm->ket_qua)
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-gradient-success text-white py-3">
                        <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i>Kết quả đã upload</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kết quả</label>
                            <div class="p-3 bg-light rounded">
                                {{ $sieuAm->ket_qua }}
                            </div>
                        </div>
                        @if($sieuAm->file_path)
                            <div>
                                <label class="form-label fw-semibold">File đính kèm</label>
                                <div class="d-flex align-items-center gap-2 p-3 border rounded">
                                    <i class="fas fa-file-pdf fa-2x text-danger"></i>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold">{{ basename($sieuAm->file_path) }}</div>
                                        <small class="text-muted">{{ $sieuAm->updated_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                    <a href="{{ URL::temporarySignedRoute('staff.benhan.sieuam.download', now()->addMinutes(10), ['sieuAm' => $sieuAm->id]) }}"
                                       class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-download me-1"></i>Tải file
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Chưa upload kết quả.
                    @if($sieuAm->trang_thai !== 'completed')
                        <a href="{{ route('staff.sieuam.upload.form', $sieuAm->id) }}" class="alert-link">
                            Click đây để upload
                        </a>
                    @endif
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            {{-- Bệnh nhân --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light py-3">
                    <h6 class="mb-0"><i class="fas fa-user-injured me-2"></i>Bệnh nhân</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Họ tên</small>
                        <strong>{{ $sieuAm->benhAn->user->name ?? 'N/A' }}</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Email</small>
                        {{ $sieuAm->benhAn->user->email ?? 'N/A' }}
                    </div>
                    <div>
                        <small class="text-muted d-block mb-1">Mã bệnh án</small>
                        <span class="badge bg-gradient-primary">BA-{{ $sieuAm->benh_an_id }}</span>
                    </div>
                </div>
            </div>

            {{-- Bác sĩ --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light py-3">
                    <h6 class="mb-0"><i class="fas fa-user-md me-2"></i>Bác sĩ chỉ định</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>{{ $sieuAm->bacSi->ho_ten ?? 'N/A' }}</strong>
                    </div>
                    <div class="small text-muted">
                        {{ $sieuAm->bacSi->chuyen_khoa ?? '' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
