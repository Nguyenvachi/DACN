@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">

        {{-- ============================
         🔥 HEADER
    ============================= --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="fas fa-edit me-2"></i>Sửa dịch vụ: {{ $dichVu->ten_dich_vu }}
            </h2>

            <a href="{{ route('admin.dich-vu.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>


        {{-- ============================
         🔥 FORM SỬA DỊCH VỤ
    ============================= --}}
        <div class="card shadow-sm border-0">
            <div class="card-body">

                {{-- Hiển thị lỗi --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.dich-vu.update', $dichVu) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">

                        {{-- Tên dịch vụ --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tên dịch vụ <span class="text-danger">*</span></label>
                            <input type="text" name="ten_dich_vu"
                                class="form-control form-control-lg @error('ten_dich_vu') is-invalid @enderror"
                                value="{{ old('ten_dich_vu', $dichVu->ten_dich_vu) }}" required>
                            @error('ten_dich_vu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Giá --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Giá (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" name="gia"
                                class="form-control form-control-lg @error('gia') is-invalid @enderror"
                                value="{{ old('gia', $dichVu->gia) }}" min="0" required>
                            @error('gia')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Thời gian --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Thời gian ước tính (phút) <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="thoi_gian_uoc_tinh"
                                class="form-control form-control-lg @error('thoi_gian_uoc_tinh') is-invalid @enderror"
                                value="{{ old('thoi_gian_uoc_tinh', $dichVu->thoi_gian_uoc_tinh) }}" min="1"
                                required>
                            @error('thoi_gian_uoc_tinh')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Mô tả --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Mô tả</label>
                            <textarea name="mo_ta" class="form-control form-control-lg @error('mo_ta') is-invalid @enderror" rows="4"
                                style="resize:none">{{ old('mo_ta', $dichVu->mo_ta) }}</textarea>
                            @error('mo_ta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Chuyên khoa liên quan --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Chuyên khoa liên kết</label>
                            <select name="chuyen_khoa_ids[]" class="form-select" multiple>
                                @foreach($chuyenKhoas as $ck)
                                    <option value="{{ $ck->id }}" {{ in_array($ck->id, $selectedChuyenKhoa ?? []) ? 'selected' : '' }}>{{ $ck->ten }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">(Giữ Ctrl/Cmd để chọn nhiều chuyên khoa)</small>
                        </div>

                    </div>

                    {{-- Nút hành động --}}
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('admin.dich-vu.index') }}" class="btn btn-light me-2">
                            <i class="fas fa-times"></i> Hủy
                        </a>

                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-1"></i> Cập nhật
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
            .form-control-lg {
                border-radius: 10px;
            }
        </style>
    @endpush

@endsection
