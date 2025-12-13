@extends('layouts.admin')

@section('title', 'Sửa Xét nghiệm')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">
            <i class="bi bi-pencil me-2"></i>
            Sửa Xét nghiệm
        </h2>
        <a href="{{ route('admin.xet-nghiem.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Quay lại
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.xet-nghiem.update', $xetNghiem) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Tên xét nghiệm <span class="text-danger">*</span></label>
                    <input type="text" name="ten_xet_nghiem" class="form-control @error('ten_xet_nghiem') is-invalid @enderror" value="{{ old('ten_xet_nghiem', $xetNghiem->ten_xet_nghiem) }}" required>
                    @error('ten_xet_nghiem')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Giá tiền (VNĐ) <span class="text-danger">*</span></label>
                    <input type="number" name="gia_tien" class="form-control @error('gia_tien') is-invalid @enderror" value="{{ old('gia_tien', $xetNghiem->gia_tien) }}" min="0" step="1000" required>
                    @error('gia_tien')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Mô tả</label>
                    <textarea name="mo_ta" class="form-control @error('mo_ta') is-invalid @enderror" rows="4">{{ old('mo_ta', $xetNghiem->ghi_chu) }}</textarea>
                    @error('mo_ta')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Cập nhật
                    </button>
                    <a href="{{ route('admin.xet-nghiem.index') }}" class="btn btn-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
