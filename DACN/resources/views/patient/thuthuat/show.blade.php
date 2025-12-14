@extends('layouts.patient-modern')

@section('title', 'Chi Tiết Kết Quả Thủ Thuật')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="mb-4">
        <a href="{{ route('patient.thuthuat.index') }}" class="btn btn-sm btn-outline-secondary mb-3">
            <i class="fas fa-arrow-left me-1"></i>Quay lại
        </a>
        <h2 class="fw-bold mb-1">
            <i class="fas fa-procedures text-primary me-2"></i>Chi tiết kết quả thủ thuật
        </h2>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Thông tin thủ thuật</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Loại thủ thuật</label>
                            <p class="fw-semibold">{{ $thuThuat->loaiThuThuat->ten ?? $thuThuat->ten_thu_thuat ?? 'Thủ thuật' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Ngày thực hiện</label>
                            <p class="fw-semibold">{{ \Carbon\Carbon::parse($thuThuat->ngay_thuc_hien)->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Bác sĩ thực hiện</label>
                            <p class="fw-semibold">{{ $thuThuat->bacSiThucHien->ho_ten ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Trạng thái</label>
                            <p>
                                <span class="badge bg-{{ $thuThuat->trang_thai === 'Đã hoàn thành' ? 'success' : 'warning' }}">
                                    {{ $thuThuat->trang_thai }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @if($thuThuat->ket_qua)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">Kết quả</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0 text-break">{{ $thuThuat->ket_qua }}</p>
                    </div>
                </div>
            @endif

            @if($thuThuat->ket_luan)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">Kết luận</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0 text-break">{{ $thuThuat->ket_luan }}</p>
                    </div>
                </div>
            @endif

            @if($thuThuat->tai_bien)
                <div class="card border-0 shadow-sm mb-4 border-danger">
                    <div class="card-header bg-danger text-white py-3">
                        <h5 class="mb-0">Tai biến</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0 text-break text-danger">{{ $thuThuat->tai_bien }}</p>
                    </div>
                </div>
            @endif

            @if($thuThuat->ghi_chu)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">Ghi chú</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0 text-break">{{ $thuThuat->ghi_chu }}</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Thông tin bệnh án</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>Mã bệnh án:</strong><br>
                        <span class="text-muted">#{{ $thuThuat->benhAn->id }}</span>
                    </p>
                    <p class="mb-2">
                        <strong>Ngày khám:</strong><br>
                        <span class="text-muted">{{ \Carbon\Carbon::parse($thuThuat->benhAn->created_at)->format('d/m/Y') }}</span>
                    </p>
                    <p class="mb-0">
                        <strong>Chẩn đoán:</strong><br>
                        <span class="text-muted">{{ $thuThuat->benhAn->chuan_doan ?? 'Chưa có' }}</span>
                    </p>
                    <hr>
                    <a href="{{ route('patient.benhan.show', $thuThuat->benhAn) }}" class="btn btn-sm btn-outline-primary w-100">
                        <i class="fas fa-file-medical me-1"></i>Xem bệnh án
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
