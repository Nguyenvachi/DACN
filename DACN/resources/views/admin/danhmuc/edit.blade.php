@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">

        {{-- ============================
         🔥 HEADER
    ============================= --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="fas fa-folder-edit me-2"></i> Sửa Danh mục
            </h2>

            <a href="{{ route('admin.danhmuc.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>


        {{-- ============================
         🔥 CARD FORM
    ============================= --}}
        <div class="card shadow-lg border-0">
            <div class="card-body">

                <form action="{{ route('admin.danhmuc.update', $danhmuc) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">

                        {{-- 🔸 Tên danh mục --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tên danh mục <span class="text-danger">*</span></label>
                            <input type="text" name="name"
                                class="form-control form-control-lg @error('name') is-invalid @enderror"
                                value="{{ old('name', $danhmuc->name) }}" placeholder="Nhập tên danh mục">

                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- 🔸 Meta Title --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Meta Title</label>
                            <input type="text" name="meta_title" class="form-control form-control-lg"
                                value="{{ old('meta_title', $danhmuc->meta_title) }}" placeholder="Tiêu đề SEO">
                        </div>

                        {{-- 🔸 Meta Description --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Meta Description</label>
                            <textarea name="meta_description" class="form-control form-control-lg" rows="3" placeholder="Mô tả SEO">{{ old('meta_description', $danhmuc->meta_description) }}</textarea>
                        </div>

                        {{-- 🔸 Mô tả danh mục --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Mô tả</label>
                            <textarea name="description" class="form-control form-control-lg" rows="5" placeholder="Mô tả chi tiết danh mục">{{ old('description', $danhmuc->description) }}</textarea>
                        </div>
                    </div>

                    {{-- ============================
                     🔥 BUTTONS
                ============================= --}}
                    <div class="mt-4 d-flex justify-content-end">
                        <a href="{{ route('admin.danhmuc.index') }}" class="btn btn-light me-2">
                            <i class="fas fa-times"></i> Hủy
                        </a>

                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i> Cập nhật
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>

    {{-- ============================
     🔥 CSS Tùy chỉnh nhẹ
============================ --}}
    <style>
        .form-label {
            font-size: 15px;
            font-weight: 600;
        }

        textarea {
            resize: none;
        }
    </style>
@endsection
