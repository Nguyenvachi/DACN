@extends('layouts.patient-modern')

@section('title', 'Cửa hàng thuốc')
@section('page-title', 'Nhà thuốc trực tuyến')
@section('page-subtitle', 'Mua thuốc và thực phẩm chức năng chính hãng')

@section('content')
    <div class="row g-4">
        {{-- THANH CÔNG CỤ & TÌM KIẾM --}}
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-6">
                            <form action="" method="GET" class="position-relative">
                                <i
                                    class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                                <input type="text" class="form-control form-control-lg ps-5 bg-light border-0"
                                    placeholder="Tìm kiếm tên thuốc, hoạt chất...">
                            </form>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <a href="{{ route('patient.shop.cart') }}"
                                class="btn btn-primary btn-lg position-relative px-4">
                                <i class="fas fa-shopping-cart me-2"></i>Giỏ hàng
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light"
                                    id="cartCount">
                                    {{ count(session('cart', [])) }}
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- DANH SÁCH THUỐC --}}
        <div class="col-12">
            <div class="row g-4">
                @forelse($thuocs as $thuoc)
                    <div class="col-sm-6 col-lg-3">
                        <div class="card h-100 shadow-sm border-0 border-hover-primary transition-all product-card">
                            {{-- Hình ảnh thuốc --}}
                            <div class="position-relative overflow-hidden bg-light rounded-top d-flex align-items-center justify-content-center"
                                style="height: 200px;">
                                @if ($thuoc->hinh_anh)
                                    <img src="{{ $thuoc->hinh_anh }}" class="img-fluid"
                                        style="max-height: 100%; object-fit: contain;" alt="{{ $thuoc->ten_thuoc }}">
                                @else
                                    <div class="text-center text-muted opacity-50">
                                        <i class="fas fa-pills fa-4x mb-2"></i>
                                        <div class="small">Chưa có ảnh</div>
                                    </div>
                                @endif

                                {{-- Badge Tình trạng kho --}}
                                <div class="position-absolute top-0 end-0 m-2">
                                    @if ($thuoc->so_luong_ton > 0)
                                        <span class="badge bg-success bg-opacity-75 shadow-sm">Còn hàng</span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-75 shadow-sm">Hết hàng</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Thông tin --}}
                            <div class="card-body d-flex flex-column p-3">
                                <small class="text-muted mb-1 text-uppercase" style="font-size: 0.7rem;">
                                    {{ $thuoc->hoat_chat ?? 'Dược phẩm' }}
                                </small>
                                <h6 class="card-title fw-bold text-dark mb-2 text-truncate" title="{{ $thuoc->ten_thuoc }}">
                                    {{ $thuoc->ten_thuoc }}
                                </h6>

                                <div class="mt-auto pt-3 d-flex align-items-end justify-content-between">
                                    <div>
                                        <span class="d-block text-muted small text-decoration-line-through"></span>
                                        <span
                                            class="fs-5 fw-bold text-primary">{{ number_format($thuoc->gia_ban, 0, ',', '.') }}đ</span>
                                        <small class="text-muted">/ {{ $thuoc->don_vi_tinh }}</small>
                                    </div>
                                </div>
                            </div>

                            {{-- Footer Action --}}
                            <div class="card-footer bg-white border-top-0 p-3 pt-0">
                                @if ($thuoc->so_luong_ton > 0)
                                    <button class="btn btn-outline-primary w-100 btn-add-to-cart fw-semibold"
                                        data-id="{{ $thuoc->id }}">
                                        <i class="fas fa-cart-plus me-1"></i>Thêm vào giỏ
                                    </button>
                                @else
                                    <button class="btn btn-secondary w-100" disabled>
                                        <i class="fas fa-ban me-1"></i>Tạm hết hàng
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <span class="fa-stack fa-2x mb-3">
                            <i class="fas fa-circle fa-stack-2x text-light"></i>
                            <i class="fas fa-search fa-stack-1x text-secondary opacity-50"></i>
                        </span>
                        <h5 class="text-muted fw-bold">Không tìm thấy sản phẩm nào</h5>
                        <p class="text-muted">Vui lòng thử từ khóa khác hoặc quay lại sau.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Pagination --}}
        <div class="col-12 mt-4 d-flex justify-content-center">
            {{ $thuocs->links() }}
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('.btn-add-to-cart').click(function() {
                    const button = $(this);
                    const id = button.data('id');
                    const originalHtml = button.html();

                    // Hiệu ứng loading
                    button.prop('disabled', true).html(
                        '<span class="spinner-border spinner-border-sm me-1"></span>Đang thêm...');

                    $.ajax({
                        url: '/shop/cart/add/' + id,
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            // Cập nhật số lượng trên icon giỏ hàng
                            $('#cartCount').text(response.cart_count);

                            // Hiệu ứng thành công
                            button.removeClass('btn-outline-primary').addClass(
                                'btn-success text-white');
                            button.html('<i class="fas fa-check me-1"></i>Đã thêm');

                            // Reset lại nút sau 1.5s
                            setTimeout(function() {
                                button.prop('disabled', false)
                                    .removeClass('btn-success text-white')
                                    .addClass('btn-outline-primary')
                                    .html(originalHtml);
                            }, 1500);
                        },
                        error: function() {
                            // Xử lý lỗi
                            button.prop('disabled', false).html('Lỗi! Thử lại');
                        }
                    });
                });
            });
        </script>
    @endpush

    @push('styles')
        <style>
            .product-card {
                transition: transform 0.2s, box-shadow 0.2s;
            }

            .product-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
                border-color: var(--bs-primary) !important;
            }

            .btn-add-to-cart {
                transition: all 0.3s;
            }

            .form-control-lg:focus {
                box-shadow: none;
                background-color: #fff !important;
                border: 1px solid var(--bs-primary) !important;
            }
        </style>
    @endpush
@endsection
