@extends('layouts.admin')

@section('content')

    <style>
        .card-custom {
            border-radius: 12px;
            border: none;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        }
    </style>

    <div class="container-fluid py-4">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-shield-lock-fill text-primary me-2"></i>
                Tạo quyền mới
            </h2>

            <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>

        {{-- MAIN CARD --}}
        <div class="card card-custom">
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

                <form method="POST" action="{{ route('admin.permissions.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tên quyền <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control"
                            placeholder="vd: view-users, edit-posts, delete-appointments" required
                            value="{{ old('name') }}">

                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                        <div class="form-text">
                            Sử dụng định dạng: <strong>action-resource</strong> (ví dụ: view-users, edit-posts)
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i> Tạo quyền
                        </button>
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary">
                            Hủy
                        </a>
                    </div>

                </form>
            </div>
        </div>

    </div>

@endsection
