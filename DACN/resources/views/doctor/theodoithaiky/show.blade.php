{{-- Parent file: resources/views/layouts/doctor.blade.php --}}
@extends('layouts.doctor')

@section('title', 'Chi tiết theo dõi thai kỳ')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: #1f2937;">
                <i class="fas fa-baby me-2" style="color: #10b981;"></i>
                Chi tiết theo dõi thai kỳ
            </h4>
            <div class="text-muted">Bản ghi #{{ $record->id }}</div>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('doctor.theodoithaiky.index') }}">Quay lại</a>
    </div>

    <div class="card vc-card mb-3">
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

            <div class="mb-2"><strong>Triệu chứng:</strong> <span class="text-muted">{{ $record->trieu_chung ?? '---' }}</span></div>
            <div class="mb-2"><strong>Ghi chú:</strong> <span class="text-muted">{{ $record->ghi_chu ?? '---' }}</span></div>
            <div class="mb-2"><strong>Nhận xét:</strong> <span class="text-muted">{{ $record->nhan_xet ?? '---' }}</span></div>

            @if($record->file_path)
                <a class="btn btn-outline-primary" href="{{ route('doctor.theodoithaiky.download', $record) }}">Tải tệp</a>
            @endif
        </div>
    </div>

    @can('update', $record)
        <div class="card vc-card">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Cập nhật</h6>
                <form method="POST" action="{{ route('doctor.theodoithaiky.update', $record) }}">
                    @csrf
                    @method('PATCH')

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Trạng thái</label>
                            <select name="trang_thai" class="form-select">
                                <option value="">-- Giữ nguyên --</option>
                                <option value="submitted">Đã gửi</option>
                                <option value="reviewed">Đã duyệt</option>
                                <option value="recorded">Đã ghi nhận</option>
                                <option value="archived">Lưu trữ</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="ghi_chu" rows="2" class="form-control">{{ old('ghi_chu', $record->ghi_chu) }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nhận xét</label>
                            <textarea name="nhan_xet" rows="2" class="form-control">{{ old('nhan_xet', $record->nhan_xet) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-3 d-flex justify-content-end">
                        <button class="btn btn-primary" type="submit">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    @else
        <div class="alert alert-warning">
            Bản ghi đã <strong>{{ $record->trang_thai_text }}</strong> nên được khóa chỉnh sửa.
        </div>
    @endcan
</div>
@endsection
