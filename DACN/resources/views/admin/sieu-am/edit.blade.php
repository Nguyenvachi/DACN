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
            <form action="{{ route('admin.sieu-am.update', $sieuAm) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Loại siêu âm <span class="text-danger">*</span></label>
                    <input type="text" name="loai_sieu_am" class="form-control @error('loai_sieu_am') is-invalid @enderror" value="{{ old('loai_sieu_am', $sieuAm->loai_sieu_am) }}" required>
                    @error('loai_sieu_am')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Giá tiền (VNĐ) <span class="text-danger">*</span></label>
                    <input type="number" name="gia_tien" class="form-control @error('gia_tien') is-invalid @enderror" value="{{ old('gia_tien', $sieuAm->gia_tien) }}" min="0" step="1000" required>
                    @error('gia_tien')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Mô tả</label>
                    <textarea name="mo_ta" class="form-control @error('mo_ta') is-invalid @enderror" rows="4">{{ old('mo_ta', $sieuAm->mo_ta) }}</textarea>
                    @error('mo_ta')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
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
