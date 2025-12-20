{{-- Parent file: resources/views/layouts/staff.blade.php --}}
@extends('layouts.staff')

@section('title', 'Chi tiết theo dõi thai kỳ')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold mb-0">Chi tiết theo dõi thai kỳ</h4>
            <div class="text-muted">Bản ghi #{{ $record->id }}</div>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('staff.theodoithaiky.index') }}">Quay lại</a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3"><strong>Ngày:</strong> {{ $record->ngay_theo_doi?->format('d/m/Y') ?? '---' }}</div>
                <div class="col-md-3"><strong>Tuần thai:</strong> {{ $record->tuan_thai ?? '---' }}</div>
                <div class="col-md-3"><strong>Cân nặng:</strong> {{ $record->can_nang_kg ? $record->can_nang_kg.' kg' : '---' }}</div>
                <div class="col-md-3"><strong>Trạng thái:</strong> <span class="badge {{ $record->trang_thai_badge_class }}">{{ $record->trang_thai_text }}</span></div>
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
            <div><strong>Triệu chứng:</strong> <span class="text-muted">{{ $record->trieu_chung ?? '---' }}</span></div>
            <div class="mt-1"><strong>Ghi chú:</strong> <span class="text-muted">{{ $record->ghi_chu ?? '---' }}</span></div>
            <div class="mt-1"><strong>Nhận xét:</strong> <span class="text-muted">{{ $record->nhan_xet ?? '---' }}</span></div>
        </div>
    </div>

    @can('update', $record)
        <div class="card">
            <div class="card-body">
                <h6 class="fw-bold">Cập nhật trạng thái</h6>
                <form method="POST" action="{{ route('staff.theodoithaiky.update', $record) }}">
                    @csrf
                    @method('PATCH')

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Trạng thái</label>
                            <select name="trang_thai" class="form-select" required>
                                <option value="submitted" {{ $record->trang_thai=='submitted' ? 'selected' : '' }}>Đã gửi</option>
                                <option value="reviewed" {{ $record->trang_thai=='reviewed' ? 'selected' : '' }}>Đã duyệt</option>
                                <option value="recorded" {{ $record->trang_thai=='recorded' ? 'selected' : '' }}>Đã ghi nhận</option>
                                <option value="archived" {{ $record->trang_thai=='archived' ? 'selected' : '' }}>Lưu trữ</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="ghi_chu" rows="2" class="form-control">{{ old('ghi_chu', $record->ghi_chu) }}</textarea>
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
