@extends('layouts.guest')

@push('styles')
    <style>
        body {
            background: #f0f2f5;
        }

        .reset-card {
            max-width: 420px;
            margin: auto;
            margin-top: 80px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush

@section('content')
    <div class="container py-5">

        <div class="card reset-card">
            <div class="card-body p-4">

                <h3 class="text-center fw-bold mb-3">
                    <i class="bi bi-arrow-repeat text-primary"></i>
                    Đặt lại mật khẩu
                </h3>

                <form method="POST" action="{{ route('password.store') }}">
                    @csrf

                    {{-- Token reset password --}}
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    {{-- Email --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" value="{{ old('email', $request->email) }}"
                            class="form-control @error('email') is-invalid @enderror" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- New Password --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mật khẩu mới</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                            required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Xác nhận mật khẩu</label>
                        <input type="password" name="password_confirmation"
                            class="form-control @error('password_confirmation') is-invalid @enderror" required>
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <button class="btn btn-primary w-100">
                        <i class="bi bi-check-circle"></i> Đặt lại mật khẩu
                    </button>

                </form>

                <div class="text-center mt-3">
                    <a href="{{ route('login') }}" class="small text-primary">
                        ← Quay lại đăng nhập
                    </a>
                </div>

            </div>
        </div>

    </div>
@endsection
