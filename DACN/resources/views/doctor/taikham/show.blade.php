{{-- Parent file: resources/views/layouts/doctor.blade.php --}}
@extends('layouts.doctor')

@section('title', 'Chi tiết tái khám')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: #1f2937;">
                <i class="fas fa-calendar-alt me-2" style="color: #10b981;"></i>
                Chi tiết tái khám
            </h4>
            <div class="text-muted">Yêu cầu #{{ $record->id }}</div>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('doctor.taikham.index') }}">Quay lại</a>
    </div>

    <div class="card vc-card mb-3">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3"><strong>Ngày:</strong> {{ $record->ngay_tai_kham?->format('d/m/Y') ?? '---' }}</div>
                <div class="col-md-3"><strong>Giờ:</strong> {{ $record->thoi_gian_tai_kham ? \Carbon\Carbon::parse($record->thoi_gian_tai_kham)->format('H:i') : '---' }}</div>
                <div class="col-md-3"><strong>Số ngày dự kiến:</strong> {{ $record->so_ngay_du_kien ?? '---' }}</div>
                <div class="col-md-3"><strong>Trạng thái:</strong> <span class="badge {{ $record->trang_thai_badge_class }}">{{ $record->trang_thai_text }}</span></div>
            </div>

            <hr>

            <div><strong>Bệnh nhân:</strong> {{ $record->benhAn?->user?->name ?? $record->benhAn?->user?->ho_ten ?? '---' }}</div>
            <div class="mt-1"><strong>Lịch hẹn liên kết:</strong>
                @if($record->lichHen)
                    <span class="text-muted">#{{ $record->lichHen->id }}</span>
                @else
                    <span class="text-muted">---</span>
                @endif
            </div>

            <hr>

            <div class="mb-2"><strong>Lý do:</strong> <span class="text-muted">{{ $record->ly_do ?? '---' }}</span></div>
            <div class="mb-2"><strong>Ghi chú:</strong> <span class="text-muted">{{ $record->ghi_chu ?? '---' }}</span></div>
        </div>
    </div>

    @can('update', $record)
        <div class="card vc-card">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Cập nhật</h6>
                <form method="POST" action="{{ route('doctor.taikham.update', $record) }}">
                    @csrf
                    @method('PATCH')

                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Ngày tái khám</label>
                            <input type="date" name="ngay_tai_kham" class="form-control" value="{{ old('ngay_tai_kham', optional($record->ngay_tai_kham)->toDateString()) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Giờ tái khám</label>
                            <input type="time" name="thoi_gian_tai_kham" class="form-control" value="{{ old('thoi_gian_tai_kham', $record->thoi_gian_tai_kham ? \Carbon\Carbon::parse($record->thoi_gian_tai_kham)->format('H:i') : '') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Số ngày dự kiến</label>
                            <input type="number" name="so_ngay_du_kien" class="form-control" value="{{ old('so_ngay_du_kien', $record->so_ngay_du_kien) }}" min="1" max="365">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Trạng thái</label>
                            <select name="trang_thai" class="form-select">
                                <option value="">-- Giữ nguyên --</option>
                                <option value="Chờ xác nhận">Chờ xác nhận</option>
                                <option value="Đã xác nhận">Đã xác nhận</option>
                                <option value="Đã đặt lịch">Đã đặt lịch</option>
                                <option value="Hoàn thành">Hoàn thành</option>
                                <option value="Đã hủy">Đã hủy</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="ghi_chu" rows="3" class="form-control">{{ old('ghi_chu', $record->ghi_chu) }}</textarea>
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
