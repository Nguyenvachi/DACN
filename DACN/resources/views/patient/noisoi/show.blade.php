@extends('layouts.patient-modern')

@section('title', 'Chi tiết Nội soi')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-dark">Chi tiết Nội soi</h4>
            <div class="text-muted">#{{ $noiSoi->id }} - {{ $noiSoi->loaiNoiSoi?->ten ?? $noiSoi->loai }}</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('patient.noisoi.index') }}" class="btn btn-outline-secondary">Quay lại</a>
            @if($noiSoi->hasResult())
                <a href="{{ $noiSoi->getDownloadUrl() }}" class="btn btn-success" target="_blank">
                    <i class="fas fa-download me-2"></i>Tải file
                </a>
            @endif
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6"><strong>Bệnh án:</strong> #BA{{ $noiSoi->benh_an_id }}</div>
                <div class="col-md-6"><strong>Trạng thái:</strong> <span class="badge bg-secondary">{{ $noiSoi->trang_thai_text }}</span></div>
                <div class="col-md-6"><strong>Ngày tạo:</strong> {{ $noiSoi->created_at?->format('d/m/Y H:i') }}</div>
                <div class="col-md-6"><strong>Giá:</strong> {{ number_format((float) $noiSoi->gia, 0, ',', '.') }} đ</div>
            </div>

            @if($noiSoi->mo_ta)
                <hr>
                <h6 class="fw-bold">Mô tả</h6>
                <div style="white-space: pre-line;">{{ $noiSoi->mo_ta }}</div>
            @endif

            <hr>
            <h6 class="fw-bold">Kết quả</h6>
            @if($noiSoi->hasResult())
                @if($noiSoi->ket_qua)
                    <div class="mb-3" style="white-space: pre-line;">{{ $noiSoi->ket_qua }}</div>
                @endif
                @if($noiSoi->nhan_xet)
                    <div>
                        <strong>Nhận xét:</strong>
                        <div class="mt-2" style="white-space: pre-line;">{{ $noiSoi->nhan_xet }}</div>
                    </div>
                @endif
            @else
                <div class="text-muted">Chưa có kết quả.</div>
            @endif
        </div>
    </div>
</div>
@endsection
