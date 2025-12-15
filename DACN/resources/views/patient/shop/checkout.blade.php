@extends('layouts.patient-modern')

@section('title', 'Thanh toán')
@section('page-title', 'Thanh toán đơn hàng')
@section('page-subtitle', 'Hoàn tất thông tin để đặt hàng')

@section('content')
    <div class="container py-4">
        {{-- Progress Steps (Tùy chọn hiển thị cho đẹp) --}}
        <div class="row justify-content-center mb-5">
            <div class="col-md-8">
                <div class="position-relative d-flex justify-content-between align-items-center">
                    <div class="position-absolute top-50 start-0 translate-middle-y w-100 bg-secondary bg-opacity-25 rounded"
                        style="height: 4px; z-index: 0;"></div>

                    <div class="text-center position-relative" style="z-index: 1;">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2"
                            style="width: 40px; height: 40px;">
                            <i class="fas fa-check"></i>
                        </div>
                        <small class="fw-bold text-success">Giỏ hàng</small>
                    </div>

                    <div class="text-center position-relative" style="z-index: 1;">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2 shadow"
                            style="width: 40px; height: 40px;">
                            <span class="fw-bold">2</span>
                        </div>
                        <small class="fw-bold text-primary">Thanh toán</small>
                    </div>

                    <div class="text-center position-relative" style="z-index: 1;">
                        <div class="bg-secondary bg-opacity-25 text-muted rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2"
                            style="width: 40px; height: 40px;">
                            <span class="fw-bold">3</span>
                        </div>
                        <small class="text-muted">Hoàn tất</small>
                    </div>
                </div>
            </div>
        </div>

        @if (session('error'))
            <div class="alert alert-danger shadow-sm border-danger">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('patient.shop.place-order') }}" id="checkoutForm">
            @csrf
            <input type="hidden" name="gateway" id="gatewayInput" value="">
            <div class="row g-4">
                {{-- CỘT TRÁI: THÔNG TIN GIAO HÀNG --}}
                <div class="col-lg-7">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-map-marker-alt me-2"></i>Thông tin giao
                                hàng</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-bold">Họ và tên người nhận</label>
                                    <input type="text" class="form-control bg-light" value="{{ auth()->user()->name }}"
                                        readonly disabled>
                                    <div class="form-text"><i class="fas fa-info-circle me-1"></i>Mặc định lấy theo tài
                                        khoản đăng nhập</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Số điện thoại <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i
                                                class="fas fa-phone-alt text-muted"></i></span>
                                        <input type="text" name="sdt_nguoi_nhan"
                                            class="form-control @error('sdt_nguoi_nhan') is-invalid @enderror"
                                            value="{{ old('sdt_nguoi_nhan', auth()->user()->so_dien_thoai) }}"
                                            placeholder="Nhập số điện thoại..." required>
                                    </div>
                                    @error('sdt_nguoi_nhan')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-bold">Địa chỉ nhận hàng <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i
                                                class="fas fa-home text-muted"></i></span>
                                        <input type="text" name="dia_chi_giao"
                                            class="form-control @error('dia_chi_giao') is-invalid @enderror"
                                            value="{{ old('dia_chi_giao', auth()->user()->dia_chi) }}"
                                            placeholder="Số nhà, đường, phường/xã..." required>
                                    </div>
                                    @error('dia_chi_giao')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-bold">Ghi chú giao hàng</label>
                                    <textarea name="ghi_chu" class="form-control" rows="3"
                                        placeholder="Ví dụ: Giao giờ hành chính, gọi trước khi giao...">{{ old('ghi_chu') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Phương thức thanh toán (Demo - Có thể mở rộng sau) --}}
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h5 class="mb-0 fw-bold text-success"><i class="fas fa-credit-card me-2"></i>Phương thức thanh
                                toán</h5>
                        </div>
                        <div class="card-body p-4">
                            {{-- COD --}}
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" id="cod"
                                    value="cod" checked>
                                <label class="form-check-label fw-bold" for="cod">
                                    <i class="fas fa-money-bill-wave text-success me-2"></i>Thanh toán khi nhận hàng (COD)
                                </label>
                                <div class="text-muted small ms-4">Bạn sẽ thanh toán tiền mặt cho shipper khi nhận được
                                    hàng.</div>
                            </div>

                            {{-- Online --}}
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="online"
                                    value="online">
                                <label class="form-check-label fw-bold" for="online">
                                    <i class="fas fa-qrcode me-2"></i>Thanh toán Online (VNPay / MoMo)
                                </label>
                                <div class="text-muted small ms-4">Thanh toán ngay qua cổng VNPay hoặc MoMo.</div>
                            </div>

                            {{-- Hiển thị nút thanh toán online khi chọn --}}
                            <div id="online-payment-options" class="mt-3" style="display: none;">
                                <div class="row g-2">
                                    {{-- VNPay --}}
                                    <div class="col-6">
                                        <button type="button" id="btnVnpayCheckout" class="btn btn-outline-primary w-100 py-2">
                                            <img src="https://vnpay.vn/assets/images/logo-icon/logo-primary.svg" alt="VNPay" style="height: 20px;">
                                            <div class="small mt-1">VNPAY</div>
                                        </button>
                                    </div>
                                    {{-- MoMo --}}
                                    <div class="col-6">
                                        <button type="button" id="btnMomoCheckout" class="btn btn-outline-danger w-100 py-2">
                                            <img src="https://upload.wikimedia.org/wikipedia/vi/f/fe/MoMo_Logo.png" alt="MoMo" style="height: 20px;">
                                            <div class="small mt-1">MoMo</div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CỘT PHẢI: TÓM TẮT ĐƠN HÀNG --}}
                <div class="col-lg-5">
                    <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h5 class="mb-0 fw-bold">Đơn hàng của bạn ({{ count($cart) }})</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;">
                                @foreach ($cart as $id => $item)
                                    <div class="list-group-item d-flex align-items-center py-3">
                                        <div class="flex-shrink-0 me-3 border rounded bg-light"
                                            style="width: 50px; height: 50px;">
                                            @if (isset($item['hinh_anh']) && $item['hinh_anh'])
                                                <img src="{{ $item['hinh_anh'] }}"
                                                    class="w-100 h-100 object-fit-contain rounded">
                                            @else
                                                <div
                                                    class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                                    <i class="fas fa-pills"></i></div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0 text-dark small fw-bold text-truncate"
                                                style="max-width: 150px;">{{ $item['ten_thuoc'] }}</h6>
                                            <small class="text-muted">x {{ $item['so_luong'] }}</small>
                                        </div>
                                        <div class="text-end">
                                            <span
                                                class="text-dark fw-bold small">{{ number_format($item['gia'] * $item['so_luong'], 0, ',', '.') }}đ</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="card-footer bg-light p-4">
                            {{-- Mã giảm giá --}}
                            <div class="input-group mb-3">
                                <input type="text" id="couponCode" class="form-control" placeholder="Mã giảm giá">
                                <button class="btn btn-outline-secondary" type="button" id="btnApplyCoupon">Áp
                                    dụng</button>
                            </div>
                            <div id="couponResult" class="alert p-2 small mb-3" style="display: none;"></div>

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Tạm tính:</span>
                                <span class="fw-bold">{{ number_format($total, 0, ',', '.') }}đ</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2 text-success" id="discountRow"
                                style="display: none;">
                                <span><i class="fas fa-tag me-1"></i>Giảm giá:</span>
                                <span class="fw-bold" id="discountAmount">-0đ</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Phí vận chuyển:</span>
                                <span class="text-success fw-bold">Miễn phí</span>
                            </div>

                            <hr class="border-dashed">

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <span class="h5 mb-0 fw-bold">Tổng cộng:</span>
                                <span class="h4 mb-0 fw-bold text-primary"
                                    id="finalTotal">{{ number_format($total, 0, ',', '.') }}đ</span>
                            </div>

                            <input type="hidden" name="coupon_code" id="hiddenCouponCode">

                            <button type="submit"
                                class="btn btn-primary w-100 btn-lg py-3 fw-bold text-uppercase shadow-sm hover-scale">
                                <i class="fas fa-check-circle me-2"></i>Đặt hàng ngay
                            </button>

                            <div class="text-center mt-3">
                                <a href="{{ route('patient.shop.cart') }}" class="text-muted small text-decoration-none">
                                    <i class="fas fa-arrow-left me-1"></i>Quay lại giỏ hàng
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                const subtotal = {{ $total }};

                // Logic mã giảm giá (Tương tự Cart nhưng áp dụng cho Checkout)
                $('#btnApplyCoupon').click(function() {
                    const code = $('#couponCode').val().trim();
                    if (!code) return;

                    const btn = $(this);
                    btn.prop('disabled', true).text('Checking...');

                    $.ajax({
                        url: '{{ route('patient.coupons.check') }}',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            ma: code,
                            tong_tien: subtotal
                        },
                        success: function(res) {
                            if (res.success) {
                                const discount = res.coupon.giam_gia;
                                const final = subtotal - discount;

                                $('#discountAmount').text('-' + res.coupon.giam_gia_formatted);
                                $('#finalTotal').text(final.toLocaleString('vi-VN') + 'đ');
                                $('#discountRow').slideDown();
                                $('#hiddenCouponCode').val(code);

                                showMsg('success', 'Đã áp dụng mã giảm giá!');
                            } else {
                                showMsg('danger', res.message);
                                resetCoupon();
                            }
                            btn.prop('disabled', false).text('Áp dụng');
                        },
                        error: function() {
                            showMsg('danger', 'Lỗi kiểm tra mã');
                            btn.prop('disabled', false).text('Áp dụng');
                        }
                    });
                });

                function showMsg(type, text) {
                    $('#couponResult').removeClass('alert-success alert-danger').addClass('alert-' + type).text(text)
                        .slideDown();
                }

                function resetCoupon() {
                    $('#discountRow').slideUp();
                    $('#finalTotal').text(subtotal.toLocaleString('vi-VN') + 'đ');
                    $('#hiddenCouponCode').val('');
                }

                // Toggle thanh toán online
                $('input[name="payment_method"]').change(function() {
                    if ($(this).val() === 'online') {
                        $('#online-payment-options').slideDown();
                    } else {
                        $('#online-payment-options').slideUp();
                    }
                });

                // Handle quick checkout via gateway buttons: set gateway and submit main checkout form
                $('#btnVnpayCheckout').click(function() {
                    $('#gatewayInput').val('vnpay');
                    $('input[name="payment_method"][value="online"]').prop('checked', true).trigger('change');
                    $('#checkoutForm').submit();
                });

                $('#btnMomoCheckout').click(function() {
                    $('#gatewayInput').val('momo');
                    $('input[name="payment_method"][value="online"]').prop('checked', true).trigger('change');
                    $('#checkoutForm').submit();
                });
            });
        </script>
    @endpush

    @push('styles')
        <style>
            .hover-scale {
                transition: transform 0.2s;
            }

            .hover-scale:hover {
                transform: scale(1.02);
            }
        </style>
    @endpush
@endsection
