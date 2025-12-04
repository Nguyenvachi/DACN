@extends('layouts.guest')

@push('styles')
    <style>
        body {
            background: #f0f2f5;
        }

        .confirm-card {
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

        <div class="card confirm-card">
            <div class="card-body p-4">

                <h3 class="text-center fw-bold mb-3">
                    <i class="bi bi-shield-lock text-primary"></i>
                    Xác nhận mật khẩu
                </h3>

                <p class="text-muted small mb-4 text-center">
                    Đây là khu vực bảo mật. Vui lòng nhập lại mật khẩu để tiếp tục.
                </p>

                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    {{-- Password --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mật khẩu</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                            required autocomplete="current-password">

                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check2-circle"></i>
                        Xác nhận
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
