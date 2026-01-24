{{-- Parent file: resources/views/layouts/staff.blade.php --}}
@extends('layouts.staff')

@section('title', 'Chi tiết tái khám')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold mb-0">Chi tiết tái khám</h4>
            <div class="text-muted">Yêu cầu #{{ $record->id }}</div>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('staff.taikham.index') }}">Quay lại</a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3"><strong>Ngày:</strong> {{ $record->ngay_tai_kham?->format('d/m/Y') ?? '---' }}</div>
                <div class="col-md-3"><strong>Giờ:</strong> {{ $record->thoi_gian_tai_kham ? \Carbon\Carbon::parse($record->thoi_gian_tai_kham)->format('H:i') : '---' }}</div>
                <div class="col-md-3"><strong>Số ngày dự kiến:</strong> {{ $record->so_ngay_du_kien ?? '---' }}</div>
                <div class="col-md-3"><strong>Trạng thái:</strong> <span class="badge {{ $record->trang_thai_badge_class }}">{{ $record->trang_thai_text }}</span></div>
            </div>
            <hr>
            <div><strong>Lý do:</strong> <span class="text-muted">{{ $record->ly_do ?? '---' }}</span></div>
            <div class="mt-1"><strong>Ghi chú:</strong> <span class="text-muted">{{ $record->ghi_chu ?? '---' }}</span></div>
            <div class="mt-1"><strong>Lịch hẹn liên kết:</strong> <span class="text-muted">{{ $record->lich_hen_id ?? '---' }}</span></div>
        </div>
    </div>

    @can('update', $record)
        @if(!$record->lich_hen_id && !$record->is_locked && $record->trang_thai !== 'Đã hủy')
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="fw-bold">Đặt lịch tái khám</h6>
                    <form method="POST" action="{{ route('staff.taikham.book', $record) }}">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Ngày hẹn</label>
                                <input type="date" name="ngay_hen" class="form-control" value="{{ old('ngay_hen', $record->ngay_tai_kham?->format('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Giờ hẹn</label>
                                <input type="time" name="thoi_gian_hen" class="form-control" value="{{ old('thoi_gian_hen', $record->thoi_gian_tai_kham ? \Carbon\Carbon::parse($record->thoi_gian_tai_kham)->format('H:i') : '') }}" required>
                            </div>

                            @if(empty($suggestedDichVuId))
                                <div class="col-md-4">
                                    <label class="form-label">Dịch vụ</label>
                                    <select name="dich_vu_id" class="form-select" required>
                                        <option value="">-- Chọn dịch vụ --</option>
                                        @foreach(($dichVus ?? []) as $dv)
                                            <option value="{{ $dv->id }}" {{ (string)old('dich_vu_id')===(string)$dv->id ? 'selected' : '' }}>
                                                {{ $dv->ten_dich_vu }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="col-12">
                                <label class="form-label">Ghi chú (tuỳ chọn)</label>
                                <textarea name="ghi_chu" rows="2" class="form-control">{{ old('ghi_chu') }}</textarea>
                            </div>
                        </div>

                        <div class="mt-3 d-flex justify-content-end">
                            <button class="btn btn-primary" type="submit">Tạo lịch hẹn & liên kết</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endcan

    @can('update', $record)
        <div class="card">
            <div class="card-body">
                <h6 class="fw-bold">Cập nhật trạng thái</h6>
                <form method="POST" action="{{ route('staff.taikham.update', $record) }}">
                    @csrf
                    @method('PATCH')

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Trạng thái</label>
                            <select name="trang_thai" class="form-select" required>
                                <option value="Chờ xác nhận" {{ $record->trang_thai=='Chờ xác nhận' ? 'selected' : '' }}>Chờ xác nhận</option>
                                <option value="Đã xác nhận" {{ $record->trang_thai=='Đã xác nhận' ? 'selected' : '' }}>Đã xác nhận</option>
                                <option value="Đã đặt lịch" {{ $record->trang_thai=='Đã đặt lịch' ? 'selected' : '' }}>Đã đặt lịch</option>
                                <option value="Hoàn thành" {{ $record->trang_thai=='Hoàn thành' ? 'selected' : '' }}>Hoàn thành</option>
                                <option value="Đã hủy" {{ $record->trang_thai=='Đã hủy' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Lịch hẹn liên kết</label>
                            <input type="text" class="form-control" value="{{ $record->lich_hen_id ?? '---' }}" disabled>
                        </div>
                        <div class="col-md-4">
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
            Yêu cầu đã <strong>{{ $record->trang_thai_text }}</strong> nên được khóa chỉnh sửa.
        </div>
    @endcan
</div>
@endsection
