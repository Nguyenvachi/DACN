@extends('layouts.patient-modern')

@section('title', 'Chi tiết lịch hẹn')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Chi tiết lịch hẹn</h5>
                            <small class="text-muted">Mã: #{{ $lichHen->id }}</small>
                        </div>
                        <div>
                            <span class="badge bg-light text-dark">{{ $lichHen->trang_thai }}</span>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="small text-secondary">Bác sĩ</label>
                                <div class="fw-semibold">{{ $lichHen->bacSi->ho_ten ?? 'N/A' }}</div>
                                <div class="small text-muted">{{ $lichHen->bacSi->chuyen_khoa ?? '' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="small text-secondary">Dịch vụ</label>
                                <div class="fw-semibold">{{ $lichHen->dichVu->ten_dich_vu ?? 'N/A' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="small text-secondary">Ngày hẹn</label>
                                <div class="fw-semibold">{{ optional($lichHen->ngay_hen)->format('d/m/Y') }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="small text-secondary">Giờ hẹn</label>
                                <div class="fw-semibold">{{ $lichHen->thoi_gian_hen }}</div>
                            </div>

                            <div class="col-12">
                                <label class="small text-secondary">Ghi chú</label>
                                <div class="p-3 bg-light rounded">{{ $lichHen->ghi_chu ?? 'Không có' }}</div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            @if(!$lichHen->getIsPaidAttribute())
                                <a href="{{ route('patient.lichhen.payment', $lichHen) }}" class="btn btn-primary">
                                    <i class="bi bi-credit-card me-1"></i> Thanh toán
                                </a>
                            @endif

                            @if($lichHen->bacSi)
                                <a href="{{ route('patient.chat.create', $lichHen->bac_si_id) }}" class="btn btn-outline-info">
                                    <i class="bi bi-chat-dots me-1"></i> Chat với bác sĩ
                                </a>
                            @endif

                            @if($lichHen->benhAn)
                                <a href="{{ route('patient.benhan.show', $lichHen->benhAn) }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-file-medical me-1"></i> Xem bệnh án
                                </a>
                            @endif

                            <a href="{{ route('patient.lichhen.index') }}" class="btn btn-light ms-auto">Quay lại</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
