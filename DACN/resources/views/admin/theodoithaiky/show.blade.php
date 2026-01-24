{{-- Parent file: resources/views/layouts/admin.blade.php --}}
@extends('layouts.admin')

@section('title', 'Chi tiết theo dõi thai kỳ')

@section('content')
<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold mb-0">Chi tiết theo dõi thai kỳ</h4>
            <div class="text-muted">Bản ghi #{{ $record->id }}</div>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('admin.theodoithaiky.index') }}">Quay lại</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3"><strong>Ngày:</strong> {{ $record->ngay_theo_doi?->format('d/m/Y') ?? '---' }}</div>
                <div class="col-md-3"><strong>Tuần thai:</strong> {{ $record->tuan_thai ?? '---' }}</div>
                <div class="col-md-3"><strong>Cân nặng:</strong> {{ $record->can_nang_kg ? $record->can_nang_kg.' kg' : '---' }}</div>
                <div class="col-md-3"><strong>Trạng thái:</strong> <span class="badge {{ $record->trang_thai_badge_class }}">{{ $record->trang_thai_text }}</span></div>
            </div>

            <hr>

            <div><strong>Bệnh nhân:</strong> {{ $record->benhAn?->user?->name ?? $record->benhAn?->user?->ho_ten ?? '---' }}</div>
            <div class="mt-1"><strong>Bác sĩ:</strong> {{ $record->benhAn?->bacSi?->user?->ho_ten ?? $record->benhAn?->bacSi?->user?->name ?? '---' }}</div>

            <hr>

            <div class="mb-2"><strong>Triệu chứng:</strong> <span class="text-muted">{{ $record->trieu_chung ?? '---' }}</span></div>
            <div class="mb-2"><strong>Ghi chú:</strong> <span class="text-muted">{{ $record->ghi_chu ?? '---' }}</span></div>
            <div class="mb-2"><strong>Nhận xét:</strong> <span class="text-muted">{{ $record->nhan_xet ?? '---' }}</span></div>

            <hr>

            @if($record->has_canh_bao)
                <div class="alert alert-warning mb-0">
                    <div class="fw-bold mb-1">Cảnh báo chỉ số bất thường</div>
                    <ul class="mb-0">
                        @foreach($record->canh_bao_items as $item)
                            <li>{{ $item['message'] }}</li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="alert alert-success mb-0">
                    Chưa phát hiện chỉ số bất thường.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
