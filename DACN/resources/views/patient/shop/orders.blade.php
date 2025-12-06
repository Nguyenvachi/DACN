@extends('layouts.patient-modern')

@section('title', 'Đơn hàng của tôi')
@section('page-title', 'Đơn hàng của tôi')
@section('page-subtitle', 'Quản lý đơn hàng thuốc đã đặt mua')

@section('content')
<div class="row g-4">
    {{-- Tabs trạng thái --}}
    <div class="col-12">
        <ul class="nav nav-pills mb-4" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#all" type="button">
                    Tất cả ({{ $donHangs->total() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pending" type="button">
                    Chờ xác nhận
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#processing" type="button">
                    Đang xử lý
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#shipping" type="button">
                    Đang giao
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#completed" type="button">
                    Hoàn thành
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#cancelled" type="button">
                    Đã hủy
                </button>
            </li>
        </ul>
    </div>

    {{-- Danh sách đơn hàng --}}
    <div class="col-12">
        @forelse($donHangs as $donHang)
            <div class="card mb-3">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Đơn hàng #{{ $donHang->id }}</h6>
                        <small class="text-muted">{{ $donHang->created_at?->format('d/m/Y H:i') }}</small>
                    </div>
                    <div class="text-end">
                        @if($donHang->trang_thai === 'cho_xac_nhan')
                            <span class="badge badge-warning">Chờ xác nhận</span>
                        @elseif($donHang->trang_thai === 'dang_xu_ly')
                            <span class="badge badge-info">Đang xử lý</span>
                        @elseif($donHang->trang_thai === 'dang_giao')
                            <span class="badge" style="background: #3b82f6; color: white;">Đang giao</span>
                        @elseif($donHang->trang_thai === 'hoan_thanh')
                            <span class="badge badge-success">Hoàn thành</span>
                        @elseif($donHang->trang_thai === 'da_huy')
                            <span class="badge badge-danger">Đã hủy</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    {{-- Sản phẩm --}}
                    @foreach($donHang->items as $item)
                        <div class="d-flex align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="flex-shrink-0 me-3">
                                @if($item->thuoc && $item->thuoc->hinh_anh)
                                    <img src="{{ Storage::url($item->thuoc->hinh_anh) }}" alt="{{ $item->ten_thuoc }}" class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                        <i class="fas fa-pills text-muted fs-3"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $item->ten_thuoc }}</h6>
                                <p class="text-muted mb-1">x{{ $item->so_luong }}</p>
                                <p class="mb-0"><strong>{{ number_format($item->gia) }}đ</strong></p>
                            </div>
                            <div class="text-end">
                                <p class="mb-0"><strong>{{ number_format($item->thanh_tien) }}đ</strong></p>
                            </div>
                        </div>
                    @endforeach

                    {{-- Tổng tiền --}}
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <div>
                            <p class="mb-1"><strong>Địa chỉ giao hàng:</strong></p>
                            <p class="mb-0 text-muted">{{ $donHang->dia_chi_giao_hang }}</p>
                        </div>
                        <div class="text-end">
                            <p class="mb-1 text-muted">Tổng tiền:</p>
                            <h5 class="mb-0 text-primary">{{ number_format($donHang->tong_tien) }}đ</h5>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white d-flex justify-content-end gap-2">
                    <a href="{{ route('patient.shop.order-detail', $donHang) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-eye me-1"></i>Xem chi tiết
                    </a>

                    @if($donHang->trang_thai === 'hoan_thanh')
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $donHang->id }}">
                            <i class="fas fa-star me-1"></i>Đánh giá
                        </button>
                        <a href="{{ route('patient.shop.order-detail', $donHang) }}?action=rebuy" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-redo me-1"></i>Mua lại
                        </a>
                    @endif

                    @if($donHang->trang_thai === 'cho_xac_nhan')
                        <form action="{{ route('patient.shop.order-cancel', $donHang) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-times me-1"></i>Hủy đơn
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-shopping-bag fs-1 text-muted d-block mb-3"></i>
                    <h5 class="text-muted">Chưa có đơn hàng nào</h5>
                    <p class="text-muted">Hãy đặt mua thuốc để có đơn hàng đầu tiên</p>
                    <a href="{{ route('patient.shop.index') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-shopping-cart me-2"></i>Đi mua thuốc
                    </a>
                </div>
            </div>
        @endforelse

        {{-- Pagination --}}
        @if($donHangs->hasPages())
            <div class="mt-4">
                {{ $donHangs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
