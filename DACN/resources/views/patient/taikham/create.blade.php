{{-- Parent file: resources/views/layouts/patient-modern.blade.php --}}
@extends('layouts.patient-modern')

@section('title', 'Gửi yêu cầu tái khám')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0 fw-bold">Gửi yêu cầu tái khám</h4>
            <div class="text-muted">Chọn bệnh án và thời gian mong muốn</div>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('patient.taikham.index') }}">Quay lại</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('patient.taikham.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Bệnh án</label>
                    <select name="benh_an_id" class="form-select" required>
                        <option value="">-- Chọn bệnh án --</option>
                        @foreach($benhAns as $ba)
                            <option value="{{ $ba->id }}" {{ (int) old('benh_an_id', $selectedBenhAn?->id) === (int) $ba->id ? 'selected' : '' }}>
                                #{{ $ba->id }} - {{ $ba->tieu_de ?? 'Bệnh án' }}
                            </option>
                        @endforeach
                    </select>
                </div>

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
                    <button class="btn btn-primary" type="submit">Gửi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
