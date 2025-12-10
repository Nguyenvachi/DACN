@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">

        {{-- ============================
         🔥 TIÊU ĐỀ TRANG
    ============================= --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="fas fa-plus-circle me-2"></i>Thêm dịch vụ mới
            </h2>

            <a href="{{ route('admin.dich-vu.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>


        {{-- ============================
         🔥 FORM THÊM DỊCH VỤ
    ============================= --}}
        <div class="card shadow-sm border-0">
            <div class="card-body">

                <form action="{{ route('admin.dich-vu.store') }}" method="POST">
                    @csrf

                    <div class="row g-4">

                        {{-- Tên dịch vụ --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tên dịch vụ <span class="text-danger">*</span></label>
                            <input type="text" name="ten_dich_vu" class="form-control form-control-lg"
                                value="{{ old('ten_dich_vu') }}" required>
                            @error('ten_dich_vu')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Loại dịch vụ --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Loại dịch vụ <span class="text-danger">*</span></label>
                            <select name="loai" class="form-select form-control-lg" required>
                                <option value="">-- Chọn loại dịch vụ --</option>
                                <option value="Cơ bản" {{ old('loai') == 'Cơ bản' ? 'selected' : '' }}>Cơ bản</option>
                                <option value="Nâng cao" {{ old('loai') == 'Nâng cao' ? 'selected' : '' }}>Nâng cao (Chuyên sâu)</option>
                            </select>
                            @error('loai')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Dịch vụ <strong>Cơ bản</strong>: Hiển thị khi bệnh nhân đặt lịch.<br>
                                Dịch vụ <strong>Nâng cao</strong>: Chỉ bác sĩ mới chỉ định sau khám.
                            </small>
                        </div>

                        {{-- Giá --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Giá (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" name="gia" class="form-control form-control-lg"
                                value="{{ old('gia') }}" required min="0" step="1000">
                            @error('gia')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Thời gian --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Thời gian ước tính (phút) <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="thoi_gian_uoc_tinh" class="form-control form-control-lg"
                                value="{{ old('thoi_gian_uoc_tinh', 30) }}" required min="1">
                            @error('thoi_gian_uoc_tinh')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Mô tả --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Mô tả</label>
                            <textarea name="mo_ta" rows="4" class="form-control form-control-lg" style="resize:none">{{ old('mo_ta') }}</textarea>
                        </div>

                        {{-- Trạng thái hoạt động --}}
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="hoat_dong" id="hoat_dong" 
                                       {{ old('hoat_dong', true) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="hoat_dong">
                                    Đang hoạt động
                                </label>
                                <small class="text-muted d-block">
                                    Chỉ dịch vụ đang hoạt động mới được sử dụng
                                </small>
                            </div>
                        </div>

                    </div>

                    {{-- NÚT --}}
                    <div class="mt-4 d-flex justify-content-end">
                        <a href="{{ route('admin.dich-vu.index') }}" class="btn btn-light me-2">
                            <i class="fas fa-times"></i> Hủy
                        </a>

                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-1"></i> Lưu
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>

    {{-- ============================
     🔥 CSS NHẸ
============================ --}}
    @push('styles')
        <style>
            .form-label {
                font-size: 15px;
            }

            .form-control-lg {
                border-radius: 10px;
            }
        </style>
    @endpush
@endsection
