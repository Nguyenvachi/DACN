@extends('layouts.patient-modern')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="mb-4">
                <a href="{{ route('patient.coupons.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-ticket-alt me-2"></i>
                            Chi tiết mã giảm giá
                        </h4>
                        <span class="badge bg-light text-dark">
                            @if($coupon->loai === 'percent')
                                Giảm {{ $coupon->gia_tri }}%
                            @else
                                Giảm {{ number_format($coupon->gia_tri, 0, ',', '.') }}đ
                            @endif
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-center py-4 mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px;">
                        <h2 class="text-white mb-2">{{ $coupon->ten }}</h2>
                        <div class="input-group input-group-lg mx-auto" style="max-width: 400px;">
                            <input type="text" class="form-control text-center fw-bold fs-4" value="{{ $coupon->ma }}" id="couponCode" readonly>
                            <button class="btn btn-light" type="button" id="btnCopy">
                                <i class="fas fa-copy"></i> Sao chép
                            </button>
                        </div>
                        <p class="text-white-50 mt-2 mb-0">Nhập mã này khi thanh toán</p>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-tag text-primary me-2"></i>Loại giảm giá
                                </h6>
                                @if($coupon->loai === 'percent')
                                    <p class="mb-0 fw-bold">Phần trăm ({{ $coupon->gia_tri }}%)</p>
                                @else
                                    <p class="mb-0 fw-bold">Số tiền cố định</p>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-gift text-success me-2"></i>Giá trị giảm
                                </h6>
                                <p class="mb-0 fw-bold text-success fs-5">
                                    @if($coupon->loai === 'percent')
                                        {{ $coupon->gia_tri }}%
                                    @else
                                        {{ number_format($coupon->gia_tri, 0, ',', '.') }}đ
                                    @endif
                                </p>
                            </div>
                        </div>

                        @if($coupon->gia_tri_toi_thieu)
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-shopping-cart text-info me-2"></i>Đơn hàng tối thiểu
                                </h6>
                                <p class="mb-0 fw-bold">{{ number_format($coupon->gia_tri_toi_thieu, 0, ',', '.') }}đ</p>
                            </div>
                        </div>
                        @endif

                        @if($coupon->ngay_bat_dau)
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-muted mb-2">
                                    <i class="far fa-calendar-check text-primary me-2"></i>Ngày bắt đầu
                                </h6>
                                <p class="mb-0 fw-bold">{{ $coupon->ngay_bat_dau->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        @endif

                        @if($coupon->ngay_ket_thuc)
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-muted mb-2">
                                    <i class="far fa-calendar-times text-danger me-2"></i>Ngày kết thúc
                                </h6>
                                <p class="mb-0 fw-bold">{{ $coupon->ngay_ket_thuc->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        @endif

                        @if($coupon->so_lan_su_dung)
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-users text-warning me-2"></i>Số lần sử dụng còn lại
                                </h6>
                                <p class="mb-0 fw-bold">{{ $coupon->so_lan_su_dung }} lượt</p>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="alert alert-info mt-4">
                        <h6 class="alert-heading">
                            <i class="fas fa-info-circle me-2"></i>Cách sử dụng:
                        </h6>
                        <ol class="mb-0 ps-3">
                            <li>Sao chép mã giảm giá bằng nút "Sao chép" ở trên</li>
                            <li>Chọn thuốc và thêm vào giỏ hàng</li>
                            <li>Tại trang thanh toán, nhập mã vào ô "Mã giảm giá"</li>
                            <li>Hệ thống sẽ tự động tính giảm giá cho bạn</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#btnCopy').click(function() {
        const code = $('#couponCode').val();
        const button = $(this);

        navigator.clipboard.writeText(code).then(function() {
            button.html('<i class="fas fa-check"></i> Đã sao chép');
            button.removeClass('btn-light').addClass('btn-success');

            setTimeout(function() {
                button.html('<i class="fas fa-copy"></i> Sao chép');
                button.removeClass('btn-success').addClass('btn-light');
            }, 2000);
        });
    });
});
</script>
@endpush
@endsection
