@extends('layouts.admin')

@section('title', 'Sửa Siêu âm')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">
            <i class="bi bi-pencil me-2"></i>
            Sửa Siêu âm
        </h2>
        <a href="{{ route('admin.sieu-am.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Quay lại
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.sieu-am.update', $loaiSieuAm->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Tên loại siêu âm <span class="text-danger">*</span></label>
                    <input type="text" name="ten" class="form-control @error('ten') is-invalid @enderror" value="{{ old('ten', $loaiSieuAm->ten) }}" required>
                    @error('ten')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Giá tiền (VNĐ) <span class="text-danger">*</span></label>
                        <input type="number" name="gia_tien" class="form-control @error('gia_tien') is-invalid @enderror" value="{{ old('gia_tien', $loaiSieuAm->gia_tien) }}" min="0" step="1000" required>
                        @error('gia_tien')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Thời gian ước tính (phút) <span class="text-danger">*</span></label>
                        <input type="number" name="thoi_gian" class="form-control @error('thoi_gian') is-invalid @enderror" value="{{ old('thoi_gian', $loaiSieuAm->thoi_gian) }}" min="1" required>
                        @error('thoi_gian')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mô tả</label>
                    <textarea name="mo_ta" class="form-control @error('mo_ta') is-invalid @enderror" rows="4">{{ old('mo_ta', $loaiSieuAm->mo_ta) }}</textarea>
                    @error('mo_ta')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="hoat_dong" class="form-check-input" id="hoat_dong" value="1" {{ old('hoat_dong', $loaiSieuAm->hoat_dong) ? 'checked' : '' }}>
                        <label class="form-check-label" for="hoat_dong">
                            Đang hoạt động
                        </label>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Cập nhật
                    </button>
                    <a href="{{ route('admin.sieu-am.index') }}" class="btn btn-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
