@extends('layouts.patient-modern')

@section('title', 'Chi tiết mã giảm giá')
@section('page-title', 'Thông tin ưu đãi')
@section('page-subtitle', 'Xem chi tiết điều kiện và cách sử dụng mã giảm giá')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-7">
                {{-- GIFT CARD HERO --}}
                <div class="card border-0 shadow-lg mb-4 overflow-hidden position-relative text-white"
                    style="background: {{ $coupon->loai === 'percent' ? 'linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%)' : 'linear-gradient(135deg, #198754 0%, #20c997 100%)' }}; min-height: 250px;">

                    {{-- Decorative Background Circles --}}
                    <div class="position-absolute top-0 start-0 translate-middle rounded-circle bg-white opacity-10"
                        style="width: 200px; height: 200px;"></div>
                    <div class="position-absolute bottom-0 end-0 translate-middle-x rounded-circle bg-white opacity-10"
                        style="width: 300px; height: 300px;"></div>

                    <div class="card-body p-5 text-center position-relative z-1 d-flex flex-column justify-content-center">
                        <h5 class="text-uppercase letter-spacing-2 opacity-75 mb-3">Mã Giảm Giá Độc Quyền</h5>
                        <h1 class="display-4 fw-bold mb-2">
                            @if ($coupon->loai === 'percent')
                                GIẢM {{ $coupon->gia_tri }}%
                            @else
                                GIẢM {{ number_format($coupon->gia_tri / 1000, 0) }}K
                            @endif
                        </h1>
                        <p class="fs-5 opacity-90 mb-4">{{ $coupon->ten }}</p>

                        {{-- Code Box --}}
                        <div class="bg-white text-dark rounded-pill p-1 d-inline-flex align-items-center shadow-sm mx-auto"
                            style="max-width: 400px; width: 100%;">
                            <div class="px-4 py-2 fw-bold fs-4 flex-grow-1 font-monospace text-truncate" id="codeText">
                                {{ $coupon->ma }}</div>
                            <button class="btn btn-dark rounded-pill px-4 py-2 fw-bold" id="btnCopyMain">
                                <i class="fas fa-copy me-2"></i>COPY
                            </button>
                        </div>
                    </div>
                </div>

                {{-- DETAILS CARD --}}
                <div class="card shadow-sm border-0 position-relative"
                    style="margin-top: -20px; z-index: 2; border-top-left-radius: 0; border-top-right-radius: 0;">
                    <div class="card-body p-4 pt-5">
                        <div class="row g-4 mb-4">
                            {{-- Hạn sử dụng --}}
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle p-3 me-3 text-danger">
                                        <i class="far fa-clock fa-lg"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block text-uppercase fw-bold"
                                            style="font-size: 0.7rem;">Hạn sử dụng</small>
                                        <span class="fw-bold fs-5">
                                            @if ($coupon->ngay_ket_thuc)
                                                {{ $coupon->ngay_ket_thuc->format('d/m/Y') }}
                                            @else
                                                Vô thời hạn
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Đơn tối thiểu --}}
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle p-3 me-3 text-success">
                                        <i class="fas fa-shopping-cart fa-lg"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block text-uppercase fw-bold"
                                            style="font-size: 0.7rem;">Đơn tối thiểu</small>
                                        <span class="fw-bold fs-5">
                                            @if ($coupon->gia_tri_toi_thieu)
                                                {{ number_format($coupon->gia_tri_toi_thieu, 0, ',', '.') }}đ
                                            @else
                                                0đ
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Số lượt còn lại --}}
                            @if ($coupon->so_lan_su_dung)
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded-circle p-3 me-3 text-primary">
                                            <i class="fas fa-ticket-alt fa-lg"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block text-uppercase fw-bold"
                                                style="font-size: 0.7rem;">Số lượt còn lại</small>
                                            <span class="fw-bold fs-5">{{ $coupon->so_lan_su_dung }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Loại mã --}}
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle p-3 me-3 text-info">
                                        <i class="fas fa-tag fa-lg"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block text-uppercase fw-bold"
                                            style="font-size: 0.7rem;">Loại giảm giá</small>
                                        <span class="fw-bold fs-5">
                                            {{ $coupon->loai === 'percent' ? 'Theo %' : 'Tiền mặt' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="border-dashed my-4">

                        {{-- Hướng dẫn sử dụng --}}
                        <div class="bg-light p-4 rounded border">
                            <h6 class="fw-bold mb-3"><i class="fas fa-info-circle text-primary me-2"></i>Cách sử dụng mã
                                này:</h6>
                            <ol class="mb-0 ps-3 text-muted">
                                <li class="mb-2">Bấm nút <strong>COPY</strong> ở trên để sao chép mã.</li>
                                <li class="mb-2">Truy cập <strong>Cửa hàng thuốc</strong> và chọn sản phẩm.</li>
                                <li class="mb-2">Tại bước <strong>Thanh toán</strong>, nhập mã vào ô "Mã giảm giá".</li>
                                <li>Hệ thống sẽ tự động trừ tiền nếu đơn hàng đủ điều kiện.</li>
                            </ol>
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex justify-content-center mt-4 gap-3">
                            <a href="{{ route('patient.coupons.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Quay lại
                            </a>
                            <a href="{{ route('patient.shop.index') }}" class="btn btn-primary px-4">
                                <i class="fas fa-shopping-bag me-2"></i>Mua sắm ngay
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#btnCopyMain').click(function() {
                    const code = $('#codeText').text();
                    const button = $(this);
                    const originalHtml = button.html();

                    navigator.clipboard.writeText(code).then(function() {
                        button.removeClass('btn-dark').addClass('btn-success').html(
                            '<i class="fas fa-check me-2"></i>ĐÃ COPY');

                        setTimeout(function() {
                            button.removeClass('btn-success').addClass('btn-dark').html(
                                originalHtml);
                        }, 2000);
                    });
                });
            });
        </script>
    @endpush

    @push('styles')
        <style>
            .letter-spacing-2 {
                letter-spacing: 2px;
            }

            .opacity-10 {
                opacity: 0.1;
            }

            .opacity-75 {
                opacity: 0.75;
            }

            .opacity-90 {
                opacity: 0.9;
            }

            .border-dashed {
                border-top: 2px dashed #dee2e6;
            }

            .font-monospace {
                font-family: 'Courier New', Courier, monospace;
            }
        </style>
    @endpush
@endsection
