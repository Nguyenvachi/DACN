@extends('layouts.patient-modern')

@section('content')
<div class="container py-4">
    <h2 class="mb-4"><i class="fas fa-shopping-cart me-2"></i>Thanh toán đơn hàng</h2>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('patient.shop.place-order') }}" id="checkoutForm">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Thông tin giao hàng</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Địa chỉ giao hàng <span class="text-danger">*</span></label>
                            <input type="text" name="dia_chi_giao" class="form-control @error('dia_chi_giao') is-invalid @enderror" value="{{ old('dia_chi_giao') }}" required>
                            @error('dia_chi_giao')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" name="sdt_nguoi_nhan" class="form-control @error('sdt_nguoi_nhan') is-invalid @enderror" value="{{ old('sdt_nguoi_nhan') }}" required>
                            @error('sdt_nguoi_nhan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="ghi_chu" class="form-control" rows="3">{{ old('ghi_chu') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Danh sách sản phẩm</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Thuốc</th>
                                        <th class="text-center">Số lượng</th>
                                        <th class="text-end">Đơn giá</th>
                                        <th class="text-end">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart as $id => $item)
                                        <tr>
                                            <td>{{ $item['ten_thuoc'] }}</td>
                                            <td class="text-center">{{ $item['so_luong'] }}</td>
                                            <td class="text-end">{{ number_format($item['gia'], 0, ',', '.') }}đ</td>
                                            <td class="text-end fw-bold">{{ number_format($item['gia'] * $item['so_luong'], 0, ',', '.') }}đ</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card sticky-top" style="top: 20px;">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Tổng đơn hàng</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-ticket-alt me-2"></i>Mã giảm giá</label>
                            <div class="input-group">
                                <input type="text" name="coupon_code" id="couponCode" class="form-control" placeholder="Nhập mã giảm giá">
                                <button type="button" class="btn btn-outline-primary" id="btnApplyCoupon">
                                    Áp dụng
                                </button>
                            </div>
                            <small class="text-muted">
                                <a href="{{ route('patient.coupons.index') }}" target="_blank">Xem mã giảm giá có sẵn</a>
                            </small>
                        </div>

                        <div id="couponResult" class="alert" style="display: none;"></div>

                        <hr>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Tạm tính:</span>
                            <strong id="subtotal">{{ number_format($total, 0, ',', '.') }}đ</strong>
                        </div>

                        <div class="d-flex justify-content-between mb-2 text-success" id="discountRow" style="display: none;">
                            <span><i class="fas fa-tag me-1"></i>Giảm giá:</span>
                            <strong id="discount">0đ</strong>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-3">
                            <h5>Tổng cộng:</h5>
                            <h5 class="text-primary" id="finalTotal">{{ number_format($total, 0, ',', '.') }}đ</h5>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-check-circle me-2"></i>Đặt hàng
                        </button>
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
    let appliedCoupon = null;

    $('#btnApplyCoupon').click(function() {
        const code = $('#couponCode').val().trim();

        if (!code) {
            showResult('Vui lòng nhập mã giảm giá', 'warning');
            return;
        }

        $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Đang kiểm tra...');

        $.ajax({
            url: '{{ route("patient.coupons.check") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                ma: code,
                tong_tien: subtotal
            },
            success: function(response) {
                if (response.success) {
                    appliedCoupon = response.coupon;
                    const discount = response.coupon.giam_gia;
                    const final = subtotal - discount;

                    $('#discount').text(response.coupon.giam_gia_formatted);
                    $('#discountRow').show();
                    $('#finalTotal').text(final.toLocaleString('vi-VN') + 'đ');

                    showResult('✓ ' + response.message + ' (-' + response.coupon.giam_gia_formatted + ')', 'success');
                    $('#btnApplyCoupon').html('<i class="fas fa-check"></i> Đã áp dụng').removeClass('btn-outline-primary').addClass('btn-success');
                } else {
                    showResult('✗ ' + response.message, 'danger');
                    $('#btnApplyCoupon').prop('disabled', false).html('Áp dụng');
                }
            },
            error: function(xhr) {
                const message = xhr.responseJSON?.message || 'Có lỗi xảy ra';
                showResult('✗ ' + message, 'danger');
                $('#btnApplyCoupon').prop('disabled', false).html('Áp dụng');
            }
        });
    });

    function showResult(message, type) {
        $('#couponResult')
            .removeClass('alert-success alert-warning alert-danger')
            .addClass('alert-' + type)
            .html(message)
            .fadeIn();
    }
});
</script>
@endpush
@endsection
