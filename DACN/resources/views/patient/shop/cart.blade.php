@extends('layouts.patient-modern')

@section('title', 'Giỏ hàng')
@section('page-title', 'Giỏ hàng của bạn')
@section('page-subtitle', 'Kiểm tra lại các sản phẩm trước khi thanh toán')

@section('content')
    <div class="row g-4">
        @if (empty($cart))
            {{-- EMPTY STATE --}}
            <div class="col-12">
                <div class="card shadow-sm border-0 py-5 text-center">
                    <div class="card-body">
                        <div class="mb-4">
                            <span class="fa-stack fa-3x">
                                <i class="fas fa-circle fa-stack-2x text-light"></i>
                                <i class="fas fa-shopping-cart fa-stack-1x text-secondary opacity-25"></i>
                            </span>
                        </div>
                        <h4 class="text-muted fw-bold">Giỏ hàng của bạn đang trống</h4>
                        <p class="text-muted mb-4">Hãy dạo một vòng cửa hàng để chọn thuốc nhé!</p>
                        <a href="{{ route('patient.shop.index') }}" class="btn btn-primary px-4 py-2">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại cửa hàng
                        </a>
                    </div>
                </div>
            </div>
        @else
            {{-- CỘT TRÁI: DANH SÁCH SẢN PHẨM --}}
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold">Sản phẩm trong giỏ ({{ count($cart) }})</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-secondary small text-uppercase">
                                    <tr>
                                        <th style="width: 45%" class="ps-4">Sản phẩm</th>
                                        <th style="width: 20%" class="text-center">Đơn giá</th>
                                        <th style="width: 20%" class="text-center">Số lượng</th>
                                        <th style="width: 15%" class="text-end pe-4">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cart as $id => $item)
                                        <tr data-id="{{ $id }}" class="cart-item-row">
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    {{-- Ảnh thumbnail (Giả lập nếu chưa có) --}}
                                                    <div class="flex-shrink-0 me-3 border rounded bg-light d-flex align-items-center justify-content-center"
                                                        style="width: 60px; height: 60px;">
                                                        @if (isset($item['hinh_anh']) && $item['hinh_anh'])
                                                            <img src="{{ $item['hinh_anh'] }}"
                                                                alt="{{ $item['ten_thuoc'] }}"
                                                                class="w-100 h-100 object-fit-contain rounded">
                                                        @else
                                                            <i class="fas fa-pills text-muted"></i>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 text-dark fw-semibold text-wrap">
                                                            {{ $item['ten_thuoc'] }}</h6>
                                                        <a href="javascript:void(0)"
                                                            class="text-danger small text-decoration-none btn-remove"
                                                            data-id="{{ $id }}">
                                                            <i class="fas fa-trash-alt me-1"></i>Xóa
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center text-muted">
                                                {{ number_format($item['gia'], 0, ',', '.') }}đ
                                            </td>
                                            <td class="text-center">
                                                <div class="input-group input-group-sm d-inline-flex w-auto">
                                                    <button class="btn btn-outline-secondary btn-decrease"
                                                        type="button">-</button>
                                                    <input type="number" class="form-control text-center cart-quantity p-0"
                                                        style="width: 50px;" value="{{ $item['so_luong'] }}" min="1"
                                                        data-id="{{ $id }}">
                                                    <button class="btn btn-outline-secondary btn-increase"
                                                        type="button">+</button>
                                                </div>
                                            </td>
                                            <td class="text-end pe-4 fw-bold text-primary item-subtotal">
                                                {{ number_format($item['gia'] * $item['so_luong'], 0, ',', '.') }}đ
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <a href="{{ route('patient.shop.index') }}" class="text-decoration-none text-muted small">
                    <i class="fas fa-arrow-left me-1"></i>Tiếp tục mua sắm
                </a>
            </div>

            {{-- CỘT PHẢI: TỔNG TIỀN & THANH TOÁN --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold">Tổng quan đơn hàng</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Tạm tính:</span>
                            <span class="fw-bold" id="subTotal">{{ number_format($total, 0, ',', '.') }}đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Giảm giá:</span>
                            <span class="text-success">-0đ</span>
                        </div>
                        <hr class="border-dashed">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="fw-bold fs-5 text-dark">Tổng cộng:</span>
                            <span class="fw-bold fs-4 text-primary"
                                id="cartTotal">{{ number_format($total, 0, ',', '.') }}đ</span>
                        </div>

                        <a href="{{ route('patient.shop.checkout') }}" class="btn btn-primary w-100 btn-lg py-2 shadow-sm">
                            Tiến hành thanh toán <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                    <div class="card-footer bg-light text-center py-3">
                        <small class="text-muted"><i class="fas fa-shield-alt me-1"></i>Bảo mật thanh toán 100%</small>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Hàm cập nhật giỏ hàng
                function updateCart(id, quantity) {
                    if (quantity < 1) quantity = 1;

                    $.ajax({
                        url: '{{ route('patient.shop.cart.update') }}',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            id: id,
                            quantity: quantity
                        },
                        success: function(response) {
                            // Cập nhật lại giao diện (Reload để đơn giản và đồng bộ data PHP)
                            // Trong thực tế có thể update DOM trực tiếp nếu muốn mượt hơn
                            location.reload();
                        }
                    });
                }

                // Nút tăng giảm số lượng
                $('.btn-decrease').click(function() {
                    const input = $(this).next('input');
                    const newVal = parseInt(input.val()) - 1;
                    if (newVal >= 1) {
                        input.val(newVal);
                        updateCart(input.data('id'), newVal);
                    }
                });

                $('.btn-increase').click(function() {
                    const input = $(this).prev('input');
                    const newVal = parseInt(input.val()) + 1;
                    input.val(newVal);
                    updateCart(input.data('id'), newVal);
                });

                // Input thay đổi trực tiếp
                $('.cart-quantity').change(function() {
                    updateCart($(this).data('id'), $(this).val());
                });

                // Xóa sản phẩm
                $('.btn-remove').click(function() {
                    const id = $(this).data('id');
                    const row = $('tr[data-id="' + id + '"]');

                    if (confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?')) {
                        $.ajax({
                            url: '/shop/cart/remove/' + id,
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function() {
                                row.fadeOut(300, function() {
                                    location.reload();
                                });
                            }
                        });
                    }
                });
            });
        </script>
    @endpush
@endsection
