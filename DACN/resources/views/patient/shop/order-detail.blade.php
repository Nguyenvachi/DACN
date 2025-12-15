@extends('layouts.patient-modern')

@section('title', 'Chi tiết đơn hàng #' . ($donHang->ma_don_hang ?? $donHang->id))
@section('page-title', 'Chi tiết đơn hàng')
@section('page-subtitle', 'Thông tin chi tiết và lịch sử đơn hàng')

@section('content')
    <div class="row g-4">
        <div class="col-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0 fw-bold">Đơn hàng #{{ $donHang->ma_don_hang ?? $donHang->id }}</h5>
                            <small class="text-muted">Ngày đặt: {{ $donHang->ngay_dat?->format('d/m/Y H:i') }}</small>
                        </div>
                        <div class="text-end">
                            <span class="text-muted d-block">Trạng thái</span>
                            <span class="badge bg-secondary">{{ $donHang->trang_thai }}</span>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    {{-- Thông tin địa chỉ & liên hệ --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="mb-1 small text-muted">Người nhận</h6>
                            <div class="fw-bold">{{ $donHang->sdt_nguoi_nhan ?? auth()->user()->name }}</div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h6 class="mb-1 small text-muted">Địa chỉ giao</h6>
                            <div class="fw-bold">{{ $donHang->dia_chi_giao ?? '---' }}</div>
                        </div>
                    </div>

                    {{-- Danh sách sản phẩm --}}
                    <div class="list-group mb-3">
                        @foreach($donHang->items as $item)
                            <div class="list-group-item d-flex align-items-center">
                                <div class="me-3" style="width:70px; height:70px;">
                                    @if(optional($item->thuoc)->hinh_anh)
                                        <img src="{{ $item->thuoc->hinh_anh }}" alt="{{ $item->ten_thuoc }}" class="w-100 h-100 object-fit-contain rounded">
                                    @else
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light rounded">
                                            <i class="fas fa-pills text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <div class="fw-bold">{{ $item->ten_thuoc ?? ($item->thuoc->ten ?? 'Thuốc #'.$item->thuoc_id) }}</div>
                                            <small class="text-muted">Đơn vị: {{ $item->don_vi_tinh ?? ($item->thuoc->don_vi ?? 'Hộp') }}</small>
                                            <small class="text-muted d-block">Số lượng: x{{ $item->so_luong }}</small>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold text-primary">{{ number_format($item->don_gia ?? $item->gia ?? 0) }}đ</div>
                                            <small class="text-muted d-block">Thành tiền: {{ number_format($item->thanh_tien ?? ($item->so_luong * ($item->don_gia ?? $item->gia ?? 0))) }}đ</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Tổng kết --}}
                    <div class="d-flex justify-content-end align-items-center">
                        <div class="me-4 text-end">
                            <div class="small text-muted">Tổng tiền hàng</div>
                            <div class="h5 fw-bold text-primary">{{ number_format($donHang->tong_tien ?? 0) }}đ</div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white text-end">
                    <a href="{{ route('patient.shop.orders') }}" class="btn btn-outline-secondary">Quay lại</a>
                    @if($donHang->trang_thai === 'Chờ xử lý')
                        <form action="{{ route('patient.shop.order-cancel', $donHang) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger ms-2">Hủy đơn</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
