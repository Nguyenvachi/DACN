@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">

        {{-- Header động --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-building me-2"></i>
                {{ $loaiPhong->exists ? 'Sửa loại phòng' : 'Thêm loại phòng' }}
            </h2>

            <a href="{{ route('admin.loaiphong.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>

        {{-- Card form --}}
        <div class="card shadow-lg border-0">
            <div class="card-body">

                <form method="post"
                    action="{{ $loaiPhong->exists ? route('admin.loaiphong.update', $loaiPhong) : route('admin.loaiphong.store') }}">
                    @csrf
                    @if ($loaiPhong->exists)
                        @method('PUT')
                    @endif

                    <div class="row g-4">

                        {{-- Tên loại phòng --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tên loại phòng <span class="text-danger">*</span></label>
                            <input type="text" name="ten" class="form-control form-control-lg"
                                value="{{ old('ten', $loaiPhong->ten) }}" required>
                            @error('ten')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Slug --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Slug</label>
                            <input type="text" name="slug" class="form-control form-control-lg"
                                value="{{ old('slug', $loaiPhong->slug) }}" placeholder="Tự động tạo nếu để trống">
                            @error('slug')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <small class="text-muted">URL-friendly identifier (tự động tạo nếu để trống)</small>
                        </div>

                        {{-- Mô tả --}}
                        <div class="col-12">
                            <label class="form-label fw-bold">Mô tả</label>
                            <textarea name="mo_ta" rows="3" class="form-control form-control-lg" 
                                placeholder="Nhập mô tả về loại phòng này">{{ old('mo_ta', $loaiPhong->mo_ta) }}</textarea>
                            @error('mo_ta')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                    </div>

                    {{-- Nút --}}
                    <div class="mt-4 d-flex justify-content-end">
                        <a href="{{ route('admin.loaiphong.index') }}" class="btn btn-light me-2">
                            <i class="bi bi-x-lg"></i> Hủy
                        </a>
                        <button class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i> Lưu
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
@endsection

@push('styles')
    <style>
        .form-label {
            font-weight: 600;
            font-size: 15px;
        }

        .form-control-lg {
            border-radius: 10px;
            padding: 10px 14px;
        }

        textarea {
            resize: none;
        }
    </style>
@endpush
