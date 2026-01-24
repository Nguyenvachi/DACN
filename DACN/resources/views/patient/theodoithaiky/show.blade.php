{{-- Parent file: resources/views/layouts/patient-modern.blade.php --}}
@extends('layouts.patient-modern')

@section('title', 'Chi tiết theo dõi thai kỳ')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0 fw-bold">Chi tiết theo dõi thai kỳ</h4>
            <div class="text-muted">Bản ghi #{{ $record->id }}</div>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('patient.theodoithaiky.index') }}">Quay lại</a>
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

            <div class="row g-3">
                <div class="col-md-4"><strong>Huyết áp:</strong>
                    @if($record->huyet_ap_tam_thu && $record->huyet_ap_tam_truong)
                        {{ $record->huyet_ap_tam_thu }}/{{ $record->huyet_ap_tam_truong }}
                    @else
                        ---
                    @endif
                </div>
                <div class="col-md-4"><strong>Nhịp tim thai:</strong> {{ $record->nhip_tim_thai ?? '---' }}</div>
                <div class="col-md-4"><strong>Đường huyết:</strong> {{ $record->duong_huyet ?? '---' }}</div>
                <div class="col-md-4"><strong>Huyết sắc tố:</strong> {{ $record->huyet_sac_to ?? '---' }}</div>
            </div>

            <div class="mt-3">
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
                    <div class="alert alert-success mb-0">Chưa phát hiện chỉ số bất thường.</div>
                @endif
            </div>

            <hr>

            <div class="mb-3"><strong>Triệu chứng:</strong><div class="text-muted">{{ $record->trieu_chung ?? '---' }}</div></div>
            <div class="mb-3"><strong>Ghi chú:</strong><div class="text-muted">{{ $record->ghi_chu ?? '---' }}</div></div>
            <div class="mb-3"><strong>Nhận xét bác sĩ:</strong><div class="text-muted">{{ $record->nhan_xet ?? '---' }}</div></div>

            @if($record->file_path)
                <a class="btn btn-outline-primary" href="{{ route('patient.theodoithaiky.download', $record) }}">Tải tệp đính kèm</a>
            @endif
        </div>
    </div>
</div>
@endsection
