{{-- Parent file: resources/views/layouts/admin.blade.php --}}
@extends('layouts.admin')

@section('title', 'Chi tiết tái khám')

@section('content')
<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold mb-0">Chi tiết tái khám</h4>
            <div class="text-muted">Yêu cầu #{{ $record->id }}</div>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('admin.taikham.index') }}">Quay lại</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3"><strong>Ngày tái khám:</strong> {{ $record->ngay_tai_kham?->format('d/m/Y') ?? '---' }}</div>
                <div class="col-md-3"><strong>Giờ:</strong> {{ $record->thoi_gian_tai_kham ? \Carbon\Carbon::parse($record->thoi_gian_tai_kham)->format('H:i') : '---' }}</div>
                <div class="col-md-3"><strong>Số ngày dự kiến:</strong> {{ $record->so_ngay_du_kien ?? '---' }}</div>
                <div class="col-md-3"><strong>Trạng thái:</strong> <span class="badge {{ $record->trang_thai_badge_class }}">{{ $record->trang_thai_text }}</span></div>
            </div>

            <hr>

            <div><strong>Bệnh nhân:</strong> {{ $record->benhAn?->user?->name ?? $record->benhAn?->user?->ho_ten ?? '---' }}</div>
            <div class="mt-1"><strong>Bác sĩ:</strong> {{ $record->benhAn?->bacSi?->user?->ho_ten ?? $record->benhAn?->bacSi?->user?->name ?? '---' }}</div>

            <hr>

            <div class="mb-2"><strong>Lý do:</strong> <span class="text-muted">{{ $record->ly_do ?? '---' }}</span></div>
            <div class="mb-2"><strong>Ghi chú:</strong> <span class="text-muted">{{ $record->ghi_chu ?? '---' }}</span></div>

            <hr>

            <div class="mb-0"><strong>Lịch hẹn liên kết:</strong>
                @if($record->lichHen)
                    <span class="text-muted">#{{ $record->lichHen->id }} ({{ $record->lichHen->ngay_hen?->format('d/m/Y') ?? '---' }} {{ $record->lichHen->thoi_gian_hen ?? '' }})</span>
                @else
                    <span class="text-muted">---</span>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
