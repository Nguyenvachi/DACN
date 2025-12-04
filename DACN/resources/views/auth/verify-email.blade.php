@extends('layouts.guest')

@push('styles')
    <style>
        body {
            background: #f0f2f5;
        }

        .verify-card {
            max-width: 460px;
            margin: auto;
            margin-top: 80px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush

@section('content')
    <div class="container py-5">

        {{-- FLASH SUCCESS --}}
        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success text-center">
                <i class="bi bi-check-circle"></i>
                Một email xác minh mới đã được gửi đến địa chỉ của bạn.
            </div>
        @endif

        <div class="card verify-card">
            <div class="card-body p-4">

                <h3 class="fw-bold text-center mb-3">
                    <i class="bi bi-envelope-check text-primary"></i>
                    Xác minh Email
                </h3>

                <p class="text-muted text-center mb-4">
                    Cảm ơn bạn đã đăng ký!
                    Vui lòng kiểm tra email và nhấp vào liên kết để xác minh tài khoản.
                    Nếu bạn chưa nhận được email, bạn có thể yêu cầu gửi lại.
                </p>

                <div class="d-flex justify-content-between">

                    {{-- RESEND VERIFICATION --}}
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-arrow-repeat"></i> Gửi lại email
                        </button>
                    </form>

                    {{-- LOGOUT --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-link text-danger">
                            <i class="bi bi-box-arrow-right"></i> Đăng xuất
                        </button>
                    </form>

                </div>

            </div>
        </div>

    </div>
@endsection
