@extends('layouts.guest')

@push('styles')
    <style>
        body {
            background: #f0f2f5;
        }

        .forgot-card {
            max-width: 420px;
            margin: auto;
            margin-top: 70px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
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

        <div class="card forgot-card">
            <div class="card-body p-4">

                <h3 class="text-center fw-bold mb-3">
                    <i class="bi bi-key text-primary"></i>
                    Quên mật khẩu?
                </h3>

                <p class="text-muted small mb-4 text-center">
                    Nhập email của bạn và hệ thống sẽ gửi liên kết đặt lại mật khẩu.
                </p>

                <form method="POST" action="{{ route('password.email') }}">
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

                    {{-- Submit --}}
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-envelope-check"></i>
                        Gửi liên kết đặt lại mật khẩu
                    </button>

                    <div class="text-center mt-3">
                        <a href="{{ route('login') }}" class="small text-primary">
                            ← Quay lại đăng nhập
                        </a>
                    </div>

                </form>

            </div>
        </div>

    </div>
@endsection
