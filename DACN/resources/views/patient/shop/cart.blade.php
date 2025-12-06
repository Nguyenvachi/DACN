@extends('layouts.patient-modern')

@section('content')
<div class="container py-4">
    <h2 class="mb-4"><i class="fas fa-shopping-cart me-2"></i>Giỏ hàng của bạn</h2>

    @if(empty($cart))
        <div class="text-center py-5">
            <i class="fas fa-cart-plus fa-4x text-muted mb-3"></i>
            <h5 class="text-muted">Giỏ hàng trống</h5>
            <a href="{{ route('patient.shop.index') }}" class="btn btn-primary mt-3">
                <i class="fas fa-shopping-bag me-2"></i>Tiếp tục mua sắm
            </a>
        </div>
    @else
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Thuốc</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-end">Đơn giá</th>
                                <th class="text-end">Thành tiền</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart as $id => $item)
                                <tr data-id="{{ $id }}">
                                    <td>{{ $item['ten_thuoc'] }}</td>
                                    <td class="text-center">
                                        <input type="number" class="form-control form-control-sm cart-quantity" style="width: 80px; margin: 0 auto;" value="{{ $item['so_luong'] }}" min="1" data-id="{{ $id }}">
                                    </td>
                                    <td class="text-end">{{ number_format($item['gia'], 0, ',', '.') }}đ</td>
                                    <td class="text-end fw-bold item-subtotal">{{ number_format($item['gia'] * $item['so_luong'], 0, ',', '.') }}đ</td>
                                    <td>
                                        <button class="btn btn-sm btn-danger btn-remove" data-id="{{ $id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Tổng cộng:</th>
                                <th class="text-end text-primary" id="cartTotal">{{ number_format($total, 0, ',', '.') }}đ</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('patient.shop.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Tiếp tục mua
                    </a>
                    <a href="{{ route('patient.shop.checkout') }}" class="btn btn-success">
                        <i class="fas fa-credit-card me-2"></i>Thanh toán
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('.cart-quantity').change(function() {
        const id = $(this).data('id');
        const quantity = $(this).val();

        $.ajax({
            url: '{{ route("patient.shop.cart.update") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { id, quantity },
            success: function(response) {
                $('#cartTotal').text(response.total + 'đ');
                location.reload();
            }
        });
    });

    $('.btn-remove').click(function() {
        const id = $(this).data('id');
        const row = $('tr[data-id="' + id + '"]');

        if (confirm('Xóa sản phẩm khỏi giỏ hàng?')) {
            $.ajax({
                url: '/shop/cart/remove/' + id,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    row.fadeOut(function() {
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
