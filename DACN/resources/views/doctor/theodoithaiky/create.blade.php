{{-- Parent file: resources/views/layouts/doctor.blade.php --}}
@extends('layouts.doctor')

@section('title', 'Tạo theo dõi thai kỳ')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: #1f2937;">
                <i class="fas fa-baby me-2" style="color: #10b981;"></i>
                Tạo theo dõi thai kỳ
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('doctor.theodoithaiky.index') }}">Theo dõi thai kỳ</a></li>
                    <li class="breadcrumb-item active">Tạo</li>
                </ol>
            </nav>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('doctor.theodoithaiky.index') }}">Quay lại</a>
    </div>

    <div class="card vc-card">
        <div class="card-body">
            <div class="mb-3">
                <div class="text-muted">Bệnh án: #{{ $benhAn->id }} | Bệnh nhân: {{ $benhAn->user?->name ?? $benhAn->user?->ho_ten ?? '---' }}</div>
            </div>

            <form method="POST" action="{{ route('doctor.theodoithaiky.store', $benhAn) }}" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Ngày theo dõi</label>
                        <input type="date" name="ngay_theo_doi" class="form-control" value="{{ old('ngay_theo_doi') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tuần thai</label>
                        <input type="number" name="tuan_thai" class="form-control" value="{{ old('tuan_thai') }}" min="1" max="42">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Cân nặng (kg)</label>
                        <input type="number" step="0.01" name="can_nang_kg" class="form-control" value="{{ old('can_nang_kg') }}">
                    </div>
                </div>

                <div class="row g-3 mt-1">
                    <div class="col-md-4">
                        <label class="form-label">Huyết áp tâm thu</label>
                        <input type="number" name="huyet_ap_tam_thu" class="form-control" value="{{ old('huyet_ap_tam_thu') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Huyết áp tâm trương</label>
                        <input type="number" name="huyet_ap_tam_truong" class="form-control" value="{{ old('huyet_ap_tam_truong') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nhịp tim thai (bpm)</label>
                        <input type="number" name="nhip_tim_thai" class="form-control" value="{{ old('nhip_tim_thai') }}">
                    </div>
                </div>

                <div class="row g-3 mt-1">
                    <div class="col-md-6">
                        <label class="form-label">Đường huyết</label>
                        <input type="number" step="0.01" name="duong_huyet" class="form-control" value="{{ old('duong_huyet') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Huyết sắc tố</label>
                        <input type="number" step="0.01" name="huyet_sac_to" class="form-control" value="{{ old('huyet_sac_to') }}">
                    </div>
                </div>

                <div class="mb-3 mt-3">
                    <label class="form-label">Triệu chứng</label>
                    <textarea name="trieu_chung" rows="3" class="form-control">{{ old('trieu_chung') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Ghi chú</label>
                    <textarea name="ghi_chu" rows="3" class="form-control">{{ old('ghi_chu') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nhận xét</label>
                    <textarea name="nhan_xet" rows="3" class="form-control">{{ old('nhan_xet') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tệp đính kèm (PDF/JPG/PNG)</label>
                    <input type="file" name="file" class="form-control">
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button class="btn btn-primary" type="submit">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
