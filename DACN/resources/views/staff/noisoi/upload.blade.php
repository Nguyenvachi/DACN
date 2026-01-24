@extends('layouts.staff')

@section('title', 'Upload kết quả Nội soi')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-dark">
                <i class="fas fa-upload me-2 text-primary"></i>
                Upload kết quả Nội soi
            </h4>
            <div class="text-muted">#{{ $noiSoi->id }} - {{ $noiSoi->loaiNoiSoi?->ten ?? $noiSoi->loai }}</div>
        </div>
        <a href="{{ route('staff.noisoi.pending') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Vui lòng kiểm tra lại:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0 fw-bold">Upload</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('staff.noisoi.upload', $noiSoi) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">File kết quả (PDF/JPG/PNG)</label>
                            <input type="file" class="form-control" name="file" required accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kết quả</label>
                            <textarea class="form-control" name="ket_qua" rows="4">{{ old('ket_qua') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nhận xét</label>
                            <textarea class="form-control" name="nhan_xet" rows="3">{{ old('nhan_xet') }}</textarea>
                        </div>
                        <button class="btn btn-primary">
                            <i class="fas fa-upload me-2"></i>Upload kết quả
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0 fw-bold">Thông tin bệnh nhân</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2"><strong>Họ tên:</strong> {{ $noiSoi->benhAn->user->name ?? 'N/A' }}</div>
                    <div class="mb-2"><strong>SĐT:</strong> {{ $noiSoi->benhAn->user->so_dien_thoai ?? 'N/A' }}</div>
                    <div class="mb-2"><strong>Bác sĩ chỉ định:</strong> {{ $noiSoi->bacSiChiDinh->user->name ?? 'N/A' }}</div>
                </div>
            </div>

            @if($noiSoi->mo_ta)
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0 fw-bold">Mô tả / Yêu cầu</h6>
                    </div>
                    <div class="card-body" style="white-space: pre-line;">{{ $noiSoi->mo_ta }}</div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
