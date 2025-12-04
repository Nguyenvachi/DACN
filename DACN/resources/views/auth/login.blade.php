@extends('layouts.guest')

@push('styles')
    <style>
        body {
            background: #f0f2f5;
        }

        .login-card {
            max-width: 420px;
            margin: auto;
            margin-top: 80px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
        }
    </style>
@endpush

@section('content')
    <div class="container py-5">

        {{-- FLASH STATUS --}}
        @if (session('status'))
            <div class="alert alert-info text-center w-100">
                {{ session('status') }}
            </div>
        @endif

        <div class="card login-card">
            <div class="card-body p-4">

                <h3 class="text-center fw-bold mb-3">
                    <i class="bi bi-box-arrow-in-right text-primary"></i>
                    Đăng nhập hệ thống
                </h3>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- Email --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mật khẩu</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                            required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Remember me --}}
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="remember" id="remember" class="form-check-input">
                        <label for="remember" class="form-check-label">Ghi nhớ đăng nhập</label>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">

                        {{-- Forgot password --}}
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="small text-primary">
                                Quên mật khẩu?
                            </a>
                        @endif

                        {{-- Login button --}}
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-door-open"></i> Đăng nhập
                        </button>
                    </div>

                </form>

                {{-- ⭐ NEW: Link chuyển sang đăng ký --}}
                @if (Route::has('register'))
                    <div class="register-link">
                        <p class="text-muted mb-1">Chưa có tài khoản?</p>
                        <a href="{{ route('register') }}" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-person-plus"></i> Đăng ký ngay
                        </a>
                    </div>
                @endif

            </div>
        </div>

    </div>
@endsection
