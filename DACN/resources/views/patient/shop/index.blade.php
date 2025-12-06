@extends('layouts.patient-modern')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-store me-2"></i>Cửa hàng thuốc</h2>
        <a href="{{ route('patient.shop.cart') }}" class="btn btn-primary position-relative">
            <i class="fas fa-shopping-cart me-2"></i>Giỏ hàng
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cartCount">
                {{ count(session('cart', [])) }}
            </span>
        </a>
    </div>

    <div class="row">
        @forelse($thuocs as $thuoc)
            <div class="col-md-4 col-lg-3 mb-4">
                <div class="card h-100">
                    @if($thuoc->hinh_anh)
                        <img src="{{ $thuoc->hinh_anh }}" class="card-img-top" alt="{{ $thuoc->ten_thuoc }}">
                    @else
                        <div class="bg-light p-5 text-center">
                            <i class="fas fa-pills fa-3x text-muted"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h6 class="card-title">{{ $thuoc->ten_thuoc }}</h6>
                        <p class="text-primary fw-bold mb-2">{{ number_format($thuoc->gia_ban, 0, ',', '.') }}đ</p>
                        <p class="small text-muted mb-2">Còn: {{ $thuoc->so_luong_ton }} {{ $thuoc->don_vi_tinh }}</p>
                        <button class="btn btn-sm btn-outline-primary w-100 btn-add-to-cart" data-id="{{ $thuoc->id }}">
                            <i class="fas fa-cart-plus me-1"></i>Thêm vào giỏ
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                <p class="text-muted">Chưa có thuốc nào</p>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $thuocs->links() }}
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('.btn-add-to-cart').click(function() {
        const button = $(this);
        const id = button.data('id');

        button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

        $.ajax({
            url: '/shop/cart/add/' + id,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#cartCount').text(response.cart_count);
                button.html('<i class="fas fa-check me-1"></i>Đã thêm').removeClass('btn-outline-primary').addClass('btn-success');

                setTimeout(function() {
                    button.prop('disabled', false).html('<i class="fas fa-cart-plus me-1"></i>Thêm vào giỏ').removeClass('btn-success').addClass('btn-outline-primary');
                }, 1500);
            }
        });
    });
});
</script>
@endpush
@endsection
