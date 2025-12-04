@extends('layouts.guest')

@push('styles')
    <style>
        body {
            background: #f0f2f5;
        }

        .register-card {
            max-width: 480px;
            margin: auto;
            margin-top: 60px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush

@section('content')
    <div class="container py-5">

        <div class="card register-card">
            <div class="card-body p-4">

                <h3 class="text-center fw-bold mb-3">
                    <i class="bi bi-person-plus text-primary"></i>
                    Tạo tài khoản mới
                </h3>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    {{-- Họ tên --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Họ tên</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" required autofocus>

                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" required>

                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Mật khẩu --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mật khẩu</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                            required>

                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nhập lại mật khẩu --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Xác nhận mật khẩu</label>
                        <input type="password" name="password_confirmation"
                            class="form-control @error('password_confirmation') is-invalid @enderror" required>

                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nút --}}
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('login') }}" class="small text-primary">
                            Đã có tài khoản?
                        </a>

                        <button type="submit" class="btn btn-success px-4">
                            <i class="bi bi-check-circle"></i> Đăng ký
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
@endsection
