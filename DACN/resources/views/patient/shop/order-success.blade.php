@extends('layouts.patient-modern')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="text-center mb-4">
                <div class="mb-4">
                    <i class="fas fa-check-circle fa-5x text-success"></i>
                </div>
                <h2 class="text-success mb-3">Đặt hàng thành công!</h2>
                <p class="lead text-muted">Cảm ơn bạn đã đặt hàng. Chúng tôi sẽ xử lý đơn hàng của bạn sớm nhất.</p>
            </div>

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Thông tin đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Mã đơn hàng:</strong></div>
                        <div class="col-sm-8"><code>{{ $donHang->ma_don_hang }}</code></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Ngày đặt:</strong></div>
                        <div class="col-sm-8">{{ $donHang->ngay_dat->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Địa chỉ giao:</strong></div>
                        <div class="col-sm-8">{{ $donHang->dia_chi_giao }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>SĐT:</strong></div>
                        <div class="col-sm-8">{{ $donHang->sdt_nguoi_nhan }}</div>
                    </div>

                    <hr>

                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Tạm tính:</strong></div>
                        <div class="col-sm-8">{{ number_format($donHang->tong_tien, 0, ',', '.') }}đ</div>
                    </div>

                    @if($donHang->coupon_id && $donHang->giam_gia > 0)
                        <div class="row mb-2 text-success">
                            <div class="col-sm-4"><strong><i class="fas fa-tag me-1"></i>Giảm giá:</strong></div>
                            <div class="col-sm-8">
                                -{{ number_format($donHang->giam_gia, 0, ',', '.') }}đ
                                <small class="text-muted">(Mã: {{ $donHang->coupon->ma }})</small>
                            </div>
                        </div>
                    @endif

                    <hr>

                    <div class="row">
                        <div class="col-sm-4"><h5>Tổng thanh toán:</h5></div>
                        <div class="col-sm-8"><h5 class="text-primary">{{ number_format($donHang->thanh_toan, 0, ',', '.') }}đ</h5></div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('patient.shop.index') }}" class="btn btn-primary">
                    <i class="fas fa-shopping-bag me-2"></i>Tiếp tục mua sắm
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
