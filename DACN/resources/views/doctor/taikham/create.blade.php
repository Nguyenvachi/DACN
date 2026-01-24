{{-- Parent file: resources/views/layouts/doctor.blade.php --}}
@extends('layouts.doctor')

@section('title', 'Tạo tái khám')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: #1f2937;">
                <i class="fas fa-calendar-alt me-2" style="color: #10b981;"></i>
                Tạo yêu cầu tái khám
            </h4>
            <div class="text-muted">Bệnh án #{{ $benhAn->id }}</div>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('doctor.benhan.show', $benhAn->id) }}">Quay lại bệnh án</a>
    </div>

    <div class="card vc-card">
        <div class="card-body">
            <form method="POST" action="{{ route('doctor.taikham.store', $benhAn) }}">
                @csrf

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Ngày tái khám</label>
                        <input type="date" name="ngay_tai_kham" class="form-control" value="{{ old('ngay_tai_kham') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Giờ tái khám</label>
                        <input type="time" name="thoi_gian_tai_kham" class="form-control" value="{{ old('thoi_gian_tai_kham') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Số ngày dự kiến</label>
                        <input type="number" name="so_ngay_du_kien" class="form-control" value="{{ old('so_ngay_du_kien') }}" min="1" max="365" placeholder="VD: 7">
                    </div>
                </div>

                <div class="mt-3">
                    <label class="form-label">Lý do</label>
                    <textarea name="ly_do" rows="3" class="form-control">{{ old('ly_do') }}</textarea>
                </div>

                <div class="mt-3">
                    <label class="form-label">Ghi chú</label>
                    <textarea name="ghi_chu" rows="3" class="form-control">{{ old('ghi_chu') }}</textarea>
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    <button class="btn btn-primary" type="submit">Tạo</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
