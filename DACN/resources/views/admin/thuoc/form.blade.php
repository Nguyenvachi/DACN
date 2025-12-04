@extends('layouts.admin')

@section('content')

    <div class="container-fluid py-4">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="fas fa-capsules me-2 text-primary"></i>
                {{ $thuoc->exists ? 'Sửa thuốc' : 'Thêm thuốc' }}
            </h2>

            <a href="{{ route('admin.thuoc.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        {{-- CARD FORM --}}
        <div class="card shadow-sm border-0">
            <div class="card-body">

                {{-- ERROR --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Lỗi:</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- FORM --}}
                <form method="post"
                    action="{{ $thuoc->exists ? route('admin.thuoc.update', $thuoc) : route('admin.thuoc.store') }}">
                    @csrf
                    @if ($thuoc->exists)
                        @method('PUT')
                    @endif

                    <div class="row g-3">

                        {{-- Tên thuốc --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tên thuốc <span class="text-danger">*</span></label>
                            <input type="text" name="ten" class="form-control" value="{{ old('ten', $thuoc->ten) }}"
                                required>
                        </div>

                        {{-- Hoạt chất --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Hoạt chất</label>
                            <input type="text" name="hoat_chat" class="form-control"
                                value="{{ old('hoat_chat', $thuoc->hoat_chat) }}">
                        </div>

                        {{-- Hàm lượng --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Hàm lượng</label>
                            <input type="text" name="ham_luong" class="form-control"
                                value="{{ old('ham_luong', $thuoc->ham_luong) }}">
                        </div>

                        {{-- Đơn vị --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Đơn vị</label>
                            <input type="text" name="don_vi" class="form-control"
                                value="{{ old('don_vi', $thuoc->don_vi) }}">
                        </div>

                        {{-- Giá tham khảo --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Giá tham khảo (VNĐ)</label>
                            <input type="number" step="0.01" min="0" name="gia_tham_khao" class="form-control"
                                value="{{ old('gia_tham_khao', $thuoc->gia_tham_khao) }}">
                        </div>

                    </div>

                    {{-- BUTTONS --}}
                    <div class="mt-4 d-flex gap-2">
                        <button class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Lưu
                        </button>

                        <a href="{{ route('admin.thuoc.index') }}" class="btn btn-light">
                            <i class="fas fa-times"></i> Hủy
                        </a>
                    </div>

                </form>

            </div>
        </div>

    </div>

@endsection
