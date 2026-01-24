@extends('layouts.patient-modern')

@section('title', 'Chi tiết xét nghiệm #' . str_pad($xetNghiem->id, 5, '0', STR_PAD_LEFT))
@section('page-title', 'Chi tiết kết quả xét nghiệm')
@section('page-subtitle', 'Xem chi tiết kết quả, chẩn đoán và tải về hồ sơ')

@section('content')
<div class="row g-4">
    {{-- CỘT TRÁI: NỘI DUNG KẾT QUẢ (8 phần) --}}
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4" id="print-section">
            {{-- Header Card --}}
            <div class="card-header bg-white py-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary">
                        <i class="fas fa-microscope me-2"></i>Xét nghiệm #{{ str_pad($xetNghiem->id, 5, '0', STR_PAD_LEFT) }}
                    </h5>
                    {{-- Badge Trạng thái --}}
                    @if($xetNghiem->ket_qua)
                        @if($xetNghiem->trang_thai === 'binh_thuong')
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                <i class="fas fa-check-circle me-1"></i>Bình thường
                            </span>
                        @elseif($xetNghiem->trang_thai === 'bat_thuong')
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">
                                <i class="fas fa-exclamation-triangle me-1"></i>Bất thường
                            </span>
                        @else
                            <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25">
                                Đã có kết quả
                            </span>
                        @endif
                    @else
                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">
                            <i class="fas fa-spinner fa-spin me-1"></i>Đang xử lý
                        </span>
                    @endif
                </div>
            </div>

            <div class="card-body">
                {{-- Thông tin chung --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <small class="text-muted text-uppercase fw-bold">Loại xét nghiệm</small>
                        <div class="fs-5 text-dark fw-bold">
                            {{ $xetNghiem->loai ?? 'Xét nghiệm tổng quát' }}
                        </div>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <small class="text-muted text-uppercase fw-bold">Ngày thực hiện</small>
                        <div class="fs-5 text-dark fw-bold">
                            {{ $xetNghiem->created_at->format('d/m/Y') }}
                            <small class="text-muted fw-normal">({{ $xetNghiem->created_at->format('H:i') }})</small>
                        </div>
                    </div>
                </div>

                {{-- Thông tin Bác sĩ chỉ định --}}
                <div class="bg-light p-3 rounded mb-4 border d-flex align-items-center">
                    <div class="bg-white p-2 rounded-circle shadow-sm me-3 text-primary">
                        <i class="fas fa-user-md fa-lg"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.75rem;">Bác sĩ chỉ định</small>
                        <span class="fw-bold text-dark fs-6">
                            {{ $xetNghiem->bacSi->ho_ten ?? ($xetNghiem->benhAn->bacSi->ho_ten ?? 'N/A') }}
                        </span>
                    </div>
                </div>

                <hr class="text-muted opacity-25">

                {{-- Nội dung chi tiết --}}
                <div class="mb-4">
                    <h6 class="mb-3 fw-bold text-dark text-uppercase small">
                        <i class="fas fa-clipboard-list me-2"></i>Mô tả / Yêu cầu
                    </h6>
                    <p class="text-muted">{{ $xetNghiem->mo_ta ?? 'Không có mô tả chi tiết.' }}</p>
                </div>

                {{-- KẾT QUẢ (Highlight) --}}
                @if($xetNghiem->ket_qua)
                    <div class="mb-4">
                        <h6 class="mb-3 fw-bold text-primary text-uppercase small">
                            <i class="fas fa-flask me-2"></i>Kết quả xét nghiệm
                        </h6>
                        <div class="alert alert-light border shadow-sm p-4">
                            <div class="d-flex">
                                <div class="me-3 text-success">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                                <div>
                                    <pre class="mb-0 fw-bold text-dark" style="font-family: inherit; white-space: pre-wrap;">{{ $xetNghiem->ket_qua }}</pre>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Ghi chú / Nhận xét --}}
                @if($xetNghiem->ghi_chu || $xetNghiem->nhan_xet)
                    <div class="alert alert-warning border-warning bg-warning bg-opacity-10">
                        <h6 class="alert-heading fw-bold mb-2 text-warning-dark">
                            <i class="fas fa-comment-medical me-2"></i>Nhận xét của bác sĩ:
                        </h6>
                        <p class="mb-0 text-dark">
                            {{ $xetNghiem->ghi_chu ?? $xetNghiem->nhan_xet }}
                        </p>
                    </div>
                @endif

                {{-- Link Bệnh án gốc --}}
                @if($xetNghiem->benhAn)
                    <div class="mt-4 pt-3 border-top">
                        <small class="text-muted me-2">Kết quả này thuộc hồ sơ bệnh án:</small>
                        <a href="{{ route('patient.benhan.show', $xetNghiem->benhAn) }}" class="fw-bold text-decoration-none">
                            #{{ $xetNghiem->benh_an_id }} - Xem chi tiết
                        </a>
                    </div>
                @endif
            </div>

            <div class="card-footer bg-light text-center text-muted small py-3 d-none d-print-block">
                In ngày: {{ now()->format('d/m/Y H:i') }} - Hệ thống Healthcare Clinic
            </div>
        </div>
    </div>

    {{-- CỘT PHẢI: THÔNG TIN & TÁC VỤ (4 phần) --}}
    <div class="col-lg-4">
        {{-- Card Thông tin bệnh nhân --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold"><i class="fas fa-user-circle me-2"></i>Thông tin bệnh nhân</h6>
            </div>
            <div class="card-body">
                @php $user = auth()->user(); @endphp
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-3 fw-bold">
                        {{ strtoupper(substr($user->name ?? 'BN', 0, 1)) }}
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">{{ $user->name }}</h6>
                        <small class="text-muted">{{ $user->email }}</small>
                    </div>
                </div>
                <hr class="my-3">
                <p class="mb-2 d-flex justify-content-between">
                    <span class="text-muted">Mã số:</span>
                    <span class="fw-bold">#{{ $user->id }}</span>
                </p>
                <p class="mb-0 d-flex justify-content-between">
                    <span class="text-muted">Ngày sinh:</span>
                    <span class="fw-bold">
                        {{ $user->ngay_sinh ? \Carbon\Carbon::parse($user->ngay_sinh)->format('d/m/Y') : '---' }}
                    </span>
                </p>
            </div>
        </div>

        {{-- Card Tác vụ --}}
        <div class="card shadow-sm sticky-top" style="top: 20px; z-index: 1;">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold"><i class="fas fa-tasks me-2"></i>Tác vụ</h6>
            </div>
            <div class="card-body d-grid gap-2">
                {{-- Download File (Nếu có) --}}
                @if($xetNghiem->file_path)
                    <a href="{{ route('patient.benhan.xetnghiem.download', $xetNghiem) }}" class="btn btn-primary">
                        <i class="fas fa-download me-2"></i>Tải file kết quả (PDF/Ảnh)
                    </a>
                @else
                    <button class="btn btn-secondary" disabled>
                        <i class="fas fa-file-excel me-2"></i>Không có file đính kèm
                    </button>
                @endif

                <button onclick="window.print()" class="btn btn-outline-secondary">
                    <i class="fas fa-print me-2"></i>In phiếu kết quả
                </button>

                <a href="{{ route('patient.xetnghiem.index') }}" class="btn btn-link text-decoration-none text-muted">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
                </a>
            </div>
        </div>

        {{-- Card Hướng dẫn đọc kết quả --}}
        <div class="card shadow-sm mt-4 border-info">
            <div class="card-header bg-info text-white py-2">
                <h6 class="mb-0 small fw-bold text-uppercase"><i class="fas fa-info-circle me-2"></i>Lưu ý quan trọng</h6>
            </div>
            <div class="card-body bg-info bg-opacity-10">
                <ul class="list-unstyled small mb-0">
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Kết quả chỉ có giá trị tại thời điểm lấy mẫu.</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Vui lòng mang theo kết quả khi tái khám.</li>
                    <li><i class="fas fa-phone text-primary me-2"></i>Liên hệ bác sĩ ngay nếu có chỉ số bất thường.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        body * { visibility: hidden; }
        #print-section, #print-section * { visibility: visible; }
        #print-section {
            position: absolute; left: 0; top: 0; width: 100%;
            border: none !important; box-shadow: none !important;
        }
        .btn, .badge, footer, header, .sticky-top { display: none !important; }
    }
</style>
@endpush
@endsection
