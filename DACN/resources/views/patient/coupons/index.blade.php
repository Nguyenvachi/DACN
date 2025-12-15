@extends('layouts.patient-modern')

@section('title', 'Mã giảm giá')
@section('page-title', 'Kho Voucher')
@section('page-subtitle', 'Săn mã giảm giá để mua sắm tiết kiệm hơn')

@section('content')
    <div class="container py-4">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-ticket-alt text-primary me-2"></i>Mã giảm giá của tôi</h5>
            <a href="{{ route('patient.shop.index') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-shopping-bag me-1"></i>Dùng ngay
            </a>
        </div>

        @if ($coupons->isEmpty())
            {{-- EMPTY STATE --}}
            <div class="card shadow-sm border-0 py-5 text-center">
                <div class="card-body">
                    <div class="mb-3">
                        <span class="fa-stack fa-3x">
                            <i class="fas fa-circle fa-stack-2x text-light"></i>
                            <i class="fas fa-ticket-alt fa-stack-1x text-secondary opacity-25"></i>
                        </span>
                    </div>
                    <h5 class="text-muted fw-bold">Chưa có mã giảm giá nào</h5>
                    <p class="text-muted">Hãy quay lại sau để nhận ưu đãi nhé!</p>
                </div>
            </div>
        @else
            <div class="row g-4">
                @foreach ($coupons as $coupon)
                    <div class="col-md-6 col-xl-4">
                        {{-- VOUCHER CARD --}}
                        <div
                            class="card border-0 shadow-sm h-100 overflow-hidden position-relative voucher-card transition-all">
                            <div class="row g-0 h-100">
                                {{-- Cột trái: Giá trị giảm --}}
                                <div
                                    class="col-4 d-flex flex-column align-items-center justify-content-center text-white p-3 text-center position-relative
                        {{ $coupon->loai === 'percent' ? 'bg-primary bg-gradient' : 'bg-success bg-gradient' }}">

                                    {{-- Icon loại --}}
                                    <div class="mb-2 opacity-50">
                                        <i
                                            class="fas {{ $coupon->loai === 'percent' ? 'fa-percent' : 'fa-money-bill-wave' }} fa-lg"></i>
                                    </div>

                                    {{-- Giá trị to --}}
                                    <h3 class="fw-bold mb-0">
                                        @if ($coupon->loai === 'percent')
                                            {{ $coupon->gia_tri }}%
                                        @else
                                            {{ number_format($coupon->gia_tri / 1000, 0) }}K
                                        @endif
                                    </h3>
                                    <small class="text-white-50 text-uppercase fw-bold" style="font-size: 0.7rem;">Giảm
                                        giá</small>

                                    {{-- Răng cưa trang trí --}}
                                    <div class="voucher-sawtooth-right"></div>
                                </div>

                                {{-- Cột phải: Thông tin --}}
                                <div
                                    class="col-8 bg-white p-3 d-flex flex-column justify-content-between position-relative">
                                    <div>
                                        {{-- Tiêu đề có Link --}}
                                        <h6 class="fw-bold mb-1 text-truncate">
                                            <a href="{{ route('patient.coupons.show', $coupon) }}"
                                                class="text-dark text-decoration-none stretched-link">
                                                {{ $coupon->ten }}
                                            </a>
                                        </h6>

                                        {{-- Điều kiện --}}
                                        <div class="small text-muted mb-2">
                                            @if ($coupon->gia_tri_toi_thieu)
                                                Đơn tối thiểu {{ number_format($coupon->gia_tri_toi_thieu / 1000, 0) }}K
                                            @else
                                                Cho mọi đơn hàng
                                            @endif
                                        </div>

                                        {{-- Hạn sử dụng --}}
                                        <div
                                            class="d-flex align-items-center small {{ $coupon->ngay_ket_thuc && $coupon->ngay_ket_thuc->isPast() ? 'text-danger' : 'text-secondary' }}">
                                            <i class="far fa-clock me-1"></i>
                                            @if ($coupon->ngay_ket_thuc)
                                                HSD: {{ $coupon->ngay_ket_thuc->format('d/m/Y') }}
                                            @else
                                                Vô thời hạn
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Copy Code (Cần đặt z-index cao hơn stretched-link để bấm được) --}}
                                    <div class="mt-3 d-flex align-items-center justify-content-between bg-light rounded p-2 border border-dashed position-relative"
                                        style="z-index: 2;">
                                        <code class="text-primary fw-bold mb-0 ps-1">{{ $coupon->ma }}</code>
                                        <button class="btn btn-sm btn-white border shadow-sm btn-copy fw-bold text-primary"
                                            data-code="{{ $coupon->ma }}" title="Sao chép">
                                            Copy
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('.btn-copy').click(function() {
                    const code = $(this).data('code');
                    const button = $(this);
                    const originalText = button.text();

                    // Copy to clipboard
                    navigator.clipboard.writeText(code).then(function() {
                        button.removeClass('text-primary').addClass('text-success').html(
                            '<i class="fas fa-check"></i>');

                        setTimeout(function() {
                            button.html(originalText).removeClass('text-success').addClass(
                                'text-primary');
                        }, 1500);
                    });
                });
            });
        </script>
    @endpush

    @push('styles')
        <style>
            .transition-all:hover {
                transform: translateY(-3px);
                box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
            }

            .border-dashed {
                border-style: dashed !important;
            }

            /* Tạo hiệu ứng răng cưa bên phải cột màu */
            .voucher-sawtooth-right {
                position: absolute;
                right: -6px;
                top: 0;
                bottom: 0;
                width: 12px;
                background-image: radial-gradient(#fff 50%, transparent 50%);
                background-size: 12px 12px;
                background-position: left center;
                background-repeat: repeat-y;
                z-index: 1;
            }
        </style>
    @endpush
@endsection
