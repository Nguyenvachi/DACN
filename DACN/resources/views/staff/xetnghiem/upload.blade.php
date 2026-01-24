@extends('layouts.staff')

@section('title', 'Upload kết quả xét nghiệm')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-dark">
                <i class="fas fa-upload me-2 text-primary"></i>
                Upload kết quả xét nghiệm
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('staff.xetnghiem.pending') }}">Xét nghiệm chờ</a></li>
                    <li class="breadcrumb-item active">Upload kết quả</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('staff.xetnghiem.pending') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    <div class="row g-4">
        {{-- Form upload --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-file-upload text-primary me-2"></i>Thông tin kết quả
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('staff.xetnghiem.upload', $xetNghiem) }}" enctype="multipart/form-data">
                        @csrf

                        {{-- File upload --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                File kết quả <span class="text-danger">*</span>
                            </label>
                            <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png" required>
                            <small class="text-muted">Chấp nhận: PDF, JPG, PNG. Tối đa 10MB</small>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kết quả text --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Kết quả dạng text (tùy chọn)</label>
                            <textarea name="ket_qua" class="form-control @error('ket_qua') is-invalid @enderror" rows="4" placeholder="Nhập kết quả xét nghiệm...">{{ old('ket_qua') }}</textarea>
                            <small class="text-muted">VD: WBC: 7.5 x10^9/L, RBC: 4.8 x10^12/L...</small>
                            @error('ket_qua')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nhận xét --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Nhận xét kỹ thuật (tùy chọn)</label>
                            <textarea name="nhan_xet" class="form-control @error('nhan_xet') is-invalid @enderror" rows="3" placeholder="Nhập nhận xét...">{{ old('nhan_xet') }}</textarea>
                            <small class="text-muted">Ghi chú về chất lượng mẫu, quy trình thực hiện...</small>
                            @error('nhan_xet')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        {{-- Buttons --}}
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('staff.xetnghiem.pending') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check me-2"></i>Hoàn thành & Gửi kết quả
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Sidebar - Thông tin XN --}}
        <div class="col-lg-4">
            {{-- Thông tin xét nghiệm --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-flask me-2"></i>Thông tin xét nghiệm
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Mã xét nghiệm</small>
                        <strong class="text-primary fs-5">#{{ $xetNghiem->id }}</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Loại xét nghiệm</small>
                        <span class="badge bg-primary p-2">{{ $xetNghiem->loai }}</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Ngày chỉ định</small>
                        <strong>{{ $xetNghiem->created_at->format('d/m/Y H:i') }}</strong>
                    </div>
                    @if($xetNghiem->mo_ta)
                    <div class="mb-0">
                        <small class="text-muted d-block mb-1">Mô tả</small>
                        <p class="mb-0 small">{{ $xetNghiem->mo_ta }}</p>
                    </div>
                    @endif
                </div>
            </div>

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
                        <h6 class="mb-1">{{ $xetNghiem->benhAn->user->name ?? 'N/A' }}</h6>
                        <p class="text-muted small mb-0">{{ $xetNghiem->benhAn->user->so_dien_thoai ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            {{-- Thông tin bác sĩ --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-user-md text-primary me-2"></i>Bác sĩ chỉ định
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md bg-primary bg-opacity-10 rounded-circle me-3 d-flex align-items-center justify-content-center">
                            <i class="fas fa-user-md text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $xetNghiem->bacSi->user->name ?? 'N/A' }}</h6>
                            <small class="text-muted">{{ $xetNghiem->bacSi->chuyen_khoa ?? 'Bác sĩ' }}</small>
                        </div>
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
</style>
@endpush
