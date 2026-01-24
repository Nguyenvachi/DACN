{{-- Parent file: resources/views/layouts/patient-modern.blade.php --}}
@extends('layouts.patient-modern')

@section('title', 'Tạo bản ghi theo dõi thai kỳ')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0 fw-bold">Tạo bản ghi theo dõi</h4>
            <div class="text-muted">Nhập chỉ số sức khỏe và đính kèm (nếu có)</div>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('patient.theodoithaiky.index') }}">Quay lại</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('patient.theodoithaiky.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Chọn bệnh án</label>
                    <select name="benh_an_id" class="form-select" required>
                        <option value="">-- Chọn bệnh án --</option>
                        @foreach($benhAns as $ba)
                            <option value="{{ $ba->id }}" {{ (old('benh_an_id') == $ba->id) || ($selectedBenhAn && $selectedBenhAn->id == $ba->id) ? 'selected' : '' }}>
                                #{{ $ba->id }} - {{ $ba->created_at?->format('d/m/Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>

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
                    <label class="form-label">Tệp đính kèm (PDF/JPG/PNG)</label>
                    <input type="file" name="file" class="form-control">
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a class="btn btn-outline-secondary" href="{{ route('patient.theodoithaiky.index') }}">Hủy</a>
                    <button class="btn btn-primary" type="submit">Gửi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
