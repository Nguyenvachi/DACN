{{-- Parent file: resources/views/layouts/patient-modern.blade.php --}}
@extends('layouts.patient-modern')

@section('title', 'Chi tiết X-Quang')
@section('page-title', 'Chi tiết X-Quang')
@section('page-subtitle', 'Xem thông tin và tải kết quả X-Quang')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 fw-bold"><i class="fas fa-x-ray me-2 text-primary"></i>{{ $xQuang->loai ?? 'X-Quang' }}</h5>
                    <small class="text-muted"><i class="far fa-clock me-1"></i>{{ $xQuang->created_at?->format('d/m/Y H:i') }}</small>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('patient.xquang.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Quay lại
                    </a>
                    @if($xQuang->trang_thai === 'completed' && $xQuang->file_path)
                        <a href="{{ $xQuang->getDownloadUrl() }}" class="btn btn-success" target="_blank">
                            <i class="fas fa-download me-1"></i>Tải file
                        </a>
                    @endif
                </div>
            </div>

            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold">Thông tin</h6>
                        <div class="mb-2"><strong>Mã:</strong> #{{ $xQuang->id }}</div>
                        <div class="mb-2"><strong>Trạng thái:</strong> <span class="badge bg-secondary">{{ $xQuang->trang_thai_text }}</span></div>
                        <div class="mb-2"><strong>Bệnh án:</strong> #BA{{ $xQuang->benh_an_id }}</div>
                        <div class="mb-2"><strong>Giá:</strong> {{ number_format((float) $xQuang->gia, 0, ',', '.') }} đ</div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold">Bác sĩ chỉ định</h6>
                        <div class="mb-2"><strong>Họ tên:</strong> {{ $xQuang->benhAn->bacSi->ho_ten ?? ($xQuang->benhAn->bacSi->user->name ?? 'N/A') }}</div>
                        <div class="mb-2"><strong>Chuyên khoa:</strong> {{ $xQuang->benhAn->bacSi->chuyenKhoa->ten ?? 'N/A' }}</div>
                    </div>
                </div>

                @if($xQuang->mo_ta)
                    <hr>
                    <h6 class="fw-bold">Mô tả / Yêu cầu</h6>
                    <div style="white-space: pre-line;">{{ $xQuang->mo_ta }}</div>
                @endif

                <hr>
                <h6 class="fw-bold">Kết quả</h6>
                @if($xQuang->trang_thai === 'completed' && $xQuang->file_path)
                    @if($xQuang->ket_qua)
                        <div class="mb-3">
                            <strong>Nội dung:</strong>
                            <div class="mt-2" style="white-space: pre-line;">{{ $xQuang->ket_qua }}</div>
                        </div>
                    @endif
                    @if($xQuang->nhan_xet)
                        <div class="mb-3">
                            <strong>Nhận xét:</strong>
                            <div class="mt-2" style="white-space: pre-line;">{{ $xQuang->nhan_xet }}</div>
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-hourglass-half fa-2x text-warning mb-2"></i>
                        <p class="text-muted mb-0">Chưa có kết quả</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
