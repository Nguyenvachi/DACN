@extends('layouts.patient-modern')

@section('title', 'Đơn hàng của tôi')
@section('page-title', 'Lịch sử mua hàng')
@section('page-subtitle', 'Theo dõi trạng thái và lịch sử các đơn hàng thuốc')

@section('content')
    <div class="row g-4">
        {{-- Tabs trạng thái (Scrollable trên mobile) --}}
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-2">
                    <ul class="nav nav-pills nav-fill flex-nowrap overflow-auto" role="tablist" style="scrollbar-width: none;">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-pill text-nowrap px-4" data-bs-toggle="pill"
                                data-bs-target="#all" type="button">
                                Tất cả
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill text-nowrap px-4" data-bs-toggle="pill"
                                data-bs-target="#pending" type="button">
                                Chờ xác nhận
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill text-nowrap px-4" data-bs-toggle="pill"
                                data-bs-target="#processing" type="button">
                                Đang xử lý
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill text-nowrap px-4" data-bs-toggle="pill"
                                data-bs-target="#shipping" type="button">
                                Đang giao
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill text-nowrap px-4" data-bs-toggle="pill"
                                data-bs-target="#completed" type="button">
                                Hoàn thành
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill text-nowrap px-4" data-bs-toggle="pill"
                                data-bs-target="#cancelled" type="button">
                                Đã hủy
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Danh sách đơn hàng --}}
        <div class="col-12">
            @forelse($donHangs as $donHang)
                <div class="card shadow-sm mb-4 border-0">
                    {{-- Header đơn hàng --}}
                    <div class="card-header bg-white py-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-bold text-dark">Đơn hàng #{{ $donHang->id }}</span>
                                <span class="text-muted mx-1">|</span>
                                <small class="text-muted">{{ $donHang->created_at?->format('d/m/Y H:i') }}</small>
                            </div>
                            <div>
                                @php
                                    $statusColors = [
                                        'cho_xac_nhan' => 'warning',
                                        'dang_xu_ly' => 'info',
                                        'dang_giao' => 'primary',
                                        'hoan_thanh' => 'success',
                                        'da_huy' => 'danger',
                                    ];
                                    $statusLabels = [
                                        'cho_xac_nhan' => 'Chờ xác nhận',
                                        'dang_xu_ly' => 'Đang xử lý',
                                        'dang_giao' => 'Đang giao hàng',
                                        'hoan_thanh' => 'Hoàn thành',
                                        'da_huy' => 'Đã hủy',
                                    ];
                                    $color = $statusColors[$donHang->trang_thai] ?? 'secondary';
                                    $label = $statusLabels[$donHang->trang_thai] ?? $donHang->trang_thai;
                                @endphp
                                <span
                                    class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} border border-{{ $color }} border-opacity-25 px-3 py-2">
                                    {{ $label }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Body: Danh sách sản phẩm --}}
                    <div class="card-body">
                        @foreach ($donHang->items as $item)
                            <div class="d-flex align-items-start mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                {{-- Ảnh sản phẩm --}}
                                <div class="flex-shrink-0 me-3 border rounded p-1 bg-light"
                                    style="width: 70px; height: 70px;">
                                    @if ($item->thuoc && $item->thuoc->hinh_anh)
                                        <img src="{{ $item->thuoc->hinh_anh }}" alt="{{ $item->ten_thuoc }}"
                                            class="w-100 h-100 object-fit-contain rounded">
                                    @else
                                        <div
                                            class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                            <i class="fas fa-pills fa-lg"></i>
                                        </div>
                                    @endif
                                </div>

                                {{-- Thông tin sản phẩm --}}
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="mb-1 text-dark fw-semibold">{{ $item->ten_thuoc }}</h6>
                                            <small class="text-muted d-block">Đơn vị:
                                                {{ $item->don_vi_tinh ?? 'Hộp' }}</small>
                                            <small class="text-muted">x{{ $item->so_luong }}</small>
                                        </div>
                                        <div class="text-end">
                                            @if ($item->gia_cu > $item->gia)
                                                <small
                                                    class="text-decoration-line-through text-muted d-block">{{ number_format($item->gia_cu) }}đ</small>
                                            @endif
                                            <span class="text-primary fw-bold">{{ number_format($item->gia) }}đ</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        {{-- Tổng tiền --}}
                        <div class="d-flex justify-content-end align-items-center pt-2 border-top border-dashed">
                            <span class="text-muted me-2">Thành tiền:</span>
                            <h5 class="mb-0 text-primary fw-bold">{{ number_format($donHang->tong_tien) }}đ</h5>
                        </div>
                    </div>

                    {{-- Footer: Actions --}}
                    <div class="card-footer bg-white py-3 border-top-0">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('patient.shop.order-detail', $donHang) }}"
                                class="btn btn-light border text-muted btn-sm">
                                Xem chi tiết
                            </a>

                            @if ($donHang->trang_thai === 'hoan_thanh')
                                <a href="{{ route('patient.shop.order-detail', $donHang) }}?action=rebuy"
                                    class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-cart-plus me-1"></i>Mua lại
                                </a>
                                <button class="btn btn-warning text-white btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#reviewModal{{ $donHang->id }}">
                                    <i class="fas fa-star me-1"></i>Đánh giá
                                </button>
                            @endif

                            @if ($donHang->trang_thai === 'cho_xac_nhan')
                                <form action="{{ route('patient.shop.order-cancel', $donHang) }}" method="POST"
                                    onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này?');" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-times me-1"></i>Hủy đơn
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                {{-- Empty State --}}
                <div class="text-center py-5">
                    <div class="mb-4">
                        <span class="fa-stack fa-3x">
                            <i class="fas fa-circle fa-stack-2x text-light"></i>
                            <i class="fas fa-shopping-basket fa-stack-1x text-secondary opacity-25"></i>
                        </span>
                    </div>
                    <h5 class="text-muted fw-bold">Chưa có đơn hàng nào</h5>
                    <p class="text-muted mb-4">Bạn chưa mua thuốc lần nào. Hãy ghé cửa hàng ngay nhé!</p>
                    <a href="{{ route('patient.shop.index') }}" class="btn btn-primary px-4 py-2">
                        <i class="fas fa-store me-2"></i>Đến Cửa hàng thuốc
                    </a>
                </div>
            @endforelse

            {{-- Pagination --}}
            @if ($donHangs->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $donHangs->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Style riêng cho thanh scroll ngang --}}
    @push('styles')
        <style>
            .nav-pills .nav-link {
                color: #6c757d;
                background-color: transparent;
                border: 1px solid transparent;
                transition: all 0.2s;
            }

            .nav-pills .nav-link:hover {
                background-color: #f8f9fa;
                color: var(--bs-primary);
            }

            .nav-pills .nav-link.active {
                background-color: var(--bs-primary);
                color: #fff;
                box-shadow: 0 4px 6px -1px rgba(var(--bs-primary-rgb), 0.3);
            }

            /* Ẩn thanh cuộn trên Chrome/Safari */
            .nav-pills::-webkit-scrollbar {
                display: none;
            }
        </style>
    @endpush
@endsection
