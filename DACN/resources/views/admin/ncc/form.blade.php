@extends('layouts.admin')

@section('content')

    <style>
        .card-custom {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
        }

        .form-label {
            font-weight: 600;
        }
    </style>

    <div class="container-fluid py-4">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-building-fill-add me-2 text-primary"></i>
                {{ $ncc->exists ? 'Sửa nhà cung cấp' : 'Thêm nhà cung cấp' }}
            </h2>

            <a href="{{ route('admin.ncc.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>

        {{-- ERRORS --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Lỗi:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $e)
                        <li>• {{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORM CARD --}}
        <div class="card card-custom">
            <div class="card-body">

                <form method="post"
                    action="{{ $ncc->exists ? route('admin.ncc.update', $ncc) : route('admin.ncc.store') }}">
                    @csrf
                    @if ($ncc->exists)
                        @method('PUT')
                    @endif

                    <div class="row g-3">

                        {{-- Tên --}}
                        <div class="col-md-6">
                            <label class="form-label">Tên <span class="text-danger">*</span></label>
                            <input type="text" name="ten" class="form-control" required
                                value="{{ old('ten', $ncc->ten) }}">
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email', $ncc->email) }}">
                        </div>

                        {{-- Điện thoại --}}
                        <div class="col-md-4">
                            <label class="form-label">Điện thoại</label>
                            <input type="text" name="so_dien_thoai" class="form-control"
                                value="{{ old('so_dien_thoai', $ncc->so_dien_thoai) }}">
                        </div>

                        {{-- Địa chỉ --}}
                        <div class="col-md-8">
                            <label class="form-label">Địa chỉ</label>
                            <input type="text" name="dia_chi" class="form-control"
                                value="{{ old('dia_chi', $ncc->dia_chi) }}">
                        </div>

                        {{-- Ghi chú --}}
                        <div class="col-12">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="ghi_chu" rows="3" class="form-control">{{ old('ghi_chu', $ncc->ghi_chu) }}</textarea>
                        </div>

                    </div>

                    {{-- BUTTONS --}}
                    <div class="mt-4 d-flex justify-content-end">
                        <a href="{{ route('admin.ncc.index') }}" class="btn btn-light me-2">Hủy</a>

                        <button class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i>
                            Lưu
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>

@endsection
