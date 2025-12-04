@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="fas fa-tag me-2"></i>
                Thêm Thẻ
            </h2>

            <a href="{{ route('admin.tag.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        {{-- FORM CARD --}}
        <div class="card shadow border-0">
            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Lỗi!</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.tag.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tên thẻ <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-lg"
                            placeholder="Ví dụ: Chăm sóc da, Nha khoa, Tai mũi họng..." value="{{ old('name') }}"
                            required>
                    </div>

                    {{-- BUTTONS --}}
                    <div class="mt-4 d-flex justify-content-end">
                        <a href="{{ route('admin.tag.index') }}" class="btn btn-light me-2">
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
@endsection
