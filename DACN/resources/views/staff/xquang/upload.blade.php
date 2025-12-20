@extends('layouts.staff')

@section('title', 'Upload kết quả X-Quang')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-dark">
                <i class="fas fa-upload me-2 text-primary"></i>
                Upload kết quả X-Quang #{{ $xQuang->id }}
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('staff.xquang.pending') }}">X-Quang chờ</a></li>
                    <li class="breadcrumb-item active">Upload</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('staff.xquang.pending') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-file-upload me-2"></i>Thông tin upload</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('staff.xquang.upload', $xQuang) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">File kết quả <span class="text-danger">*</span></label>
                            <input type="file" name="file" class="form-control" required accept=".pdf,.jpg,.jpeg,.png">
                            @error('file')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Chấp nhận: PDF/JPG/PNG, tối đa 10MB</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kết quả (text)</label>
                            <textarea name="ket_qua" class="form-control" rows="4" placeholder="Nhập nội dung kết quả (nếu có)...">{{ old('ket_qua') }}</textarea>
                            @error('ket_qua')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nhận xét</label>
                            <textarea name="nhan_xet" class="form-control" rows="3" placeholder="Nhập nhận xét (nếu có)...">{{ old('nhan_xet') }}</textarea>
                            @error('nhan_xet')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('staff.xquang.pending') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-cloud-upload-alt me-2"></i>Upload
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-info-circle me-2"></i>Thông tin ca</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2"><strong>Bệnh nhân:</strong> {{ $xQuang->benhAn->user->name ?? 'N/A' }}</div>
                    <div class="mb-2"><strong>SĐT:</strong> {{ $xQuang->benhAn->user->so_dien_thoai ?? 'N/A' }}</div>
                    <div class="mb-2"><strong>Bác sĩ:</strong> {{ $xQuang->bacSi->user->name ?? 'N/A' }}</div>
                    <div class="mb-2"><strong>Loại:</strong> {{ $xQuang->loai }}</div>
                    <div class="mb-2"><strong>Trạng thái:</strong> <span class="badge bg-secondary">{{ $xQuang->trang_thai_text }}</span></div>

                    @if($xQuang->mo_ta)
                        <hr>
                        <div class="small text-muted">Mô tả:</div>
                        <div style="white-space: pre-line;">{{ $xQuang->mo_ta }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
