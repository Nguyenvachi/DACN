{{-- Parent file: resources/views/layouts/patient-modern.blade.php --}}
@extends('layouts.patient-modern')

@section('title', 'Chi tiết tái khám')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0 fw-bold">Chi tiết tái khám</h4>
            <div class="text-muted">Yêu cầu #{{ $record->id }}</div>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('patient.taikham.index') }}">Quay lại</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3"><strong>Ngày:</strong> {{ $record->ngay_tai_kham?->format('d/m/Y') ?? '---' }}</div>
                <div class="col-md-3"><strong>Giờ:</strong> {{ $record->thoi_gian_tai_kham ? \Carbon\Carbon::parse($record->thoi_gian_tai_kham)->format('H:i') : '---' }}</div>
                <div class="col-md-3"><strong>Số ngày dự kiến:</strong> {{ $record->so_ngay_du_kien ?? '---' }}</div>
                <div class="col-md-3"><strong>Trạng thái:</strong> <span class="badge {{ $record->trang_thai_badge_class }}">{{ $record->trang_thai_text }}</span></div>
            </div>

            <hr>

            <div class="mb-3"><strong>Lý do:</strong><div class="text-muted">{{ $record->ly_do ?? '---' }}</div></div>
            <div class="mb-3"><strong>Ghi chú:</strong><div class="text-muted">{{ $record->ghi_chu ?? '---' }}</div></div>

            <div class="d-flex gap-2 justify-content-end">
                @if($record->trang_thai === \App\Models\TaiKham::STATUS_PENDING_VN)
                    <form method="POST" action="{{ route('patient.taikham.confirm', $record) }}">
                        @csrf
                        <button class="btn btn-success" type="submit">Xác nhận</button>
                    </form>
                @endif

                @if(in_array($record->trang_thai, [\App\Models\TaiKham::STATUS_PENDING_VN, \App\Models\TaiKham::STATUS_CONFIRMED_VN], true))
                    <form method="POST" action="{{ route('patient.taikham.cancel', $record) }}" onsubmit="return confirm('Bạn chắc chắn muốn hủy?')">
                        @csrf
                        <button class="btn btn-outline-danger" type="submit">Hủy</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
