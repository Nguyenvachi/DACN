@extends('layouts.patient-modern')

@section('title', 'Thanh toán đơn hàng')
@section('page-title', 'Thanh toán đơn hàng')
@section('page-subtitle', 'Hoàn tất thanh toán cho đơn hàng #' . $donHang->id)

@section('content')
    <div class="container py-4">
        {{-- Progress Steps --}}
        <div class="row justify-content-center mb-5">
            <div class="col-md-8">
                <div class="position-relative d-flex justify-content-between align-items-center">
                    <div class="position-absolute top-50 start-0 translate-middle-y w-100 bg-secondary bg-opacity-25 rounded"
                        style="height: 4px; z-index: 0;"></div>

                    <div class="text-center position-relative" style="z-index: 1;">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2"
                            style="width: 40px; height: 40px;">
                            <i class="fas fa-check"></i>
                        </div>
                        <small class="fw-bold text-success">Giỏ hàng</small>
                    </div>

                    <div class="text-center position-relative" style="z-index: 1;">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2"
                            style="width: 40px; height: 40px;">
                            <i class="fas fa-check"></i>
                        </div>
                        <small class="fw-bold text-success">Thanh toán</small>
                    </div>

                    <div class="text-center position-relative" style="z-index: 1;">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2 shadow"
                            style="width: 40px; height: 40px;">
                            <span class="fw-bold">3</span>
                        </div>
                        <small class="fw-bold text-primary">Hoàn tất</small>
                    </div>
                </div>
            </div>
        </div>

        @if (session('error'))
            <div class="alert alert-danger shadow-sm border-danger">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            </div>
        @endif

        <div class="row g-4">
            {{-- CỘT TRÁI: THÔNG TIN ĐƠN HÀNG --}}
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-shopping-cart me-2"></i>Thông tin đơn hàng</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="border rounded p-3 bg-light">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Mã đơn hàng:</strong> #{{ $donHang->id }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Ngày đặt:</strong> {{ $donHang->ngay_dat->format('d/m/Y H:i') }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Người nhận:</strong> {{ $donHang->user->name }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>SĐT:</strong> {{ $donHang->sdt_nguoi_nhan }}
                                        </div>
                                        <div class="col-12">
                                            <strong>Địa chỉ:</strong> {{ $donHang->dia_chi_giao }}
                                        </div>
                                        @if($donHang->ghi_chu)
                                            <div class="col-12">
                                                <strong>Ghi chú:</strong> {{ $donHang->ghi_chu }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Phương thức thanh toán --}}
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="mb-0 fw-bold text-success"><i class="fas fa-credit-card me-2"></i>Chọn phương thức thanh toán</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            {{-- VNPay --}}
                            <div class="col-md-6">
                                <form id="vnpayForm" method="POST" action="{{ route('vnpay.create') }}" target="_blank">
                                    @csrf
                                    <input type="hidden" name="hoa_don_id" value="shop_{{ $donHang->id }}">
                                    <input type="hidden" name="amount" value="{{ $donHang->thanh_toan }}">
                                    <input type="hidden" name="type" value="shop">
                                    <button type="submit" class="btn btn-outline-primary w-100 py-3">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <img src="https://vnpay.vn/assets/images/logo-icon/logo-primary.svg" alt="VNPay" style="height: 24px; margin-right: 10px;">
                                            <div>
                                                <div class="fw-bold">VNPAY</div>
                                                <small class="text-muted">Thanh toán qua VNPay</small>
                                            </div>
                                        </div>
                                    </button>
                                </form>
                            </div>

                            {{-- MoMo --}}
                            <div class="col-md-6">
                                <form id="momoForm" method="POST" action="{{ route('momo.create') }}" target="_blank">
                                    @csrf
                                    <input type="hidden" name="hoa_don_id" value="shop_{{ $donHang->id }}">
                                    <input type="hidden" name="amount" value="{{ $donHang->thanh_toan }}">
                                    <input type="hidden" name="type" value="shop">
                                    <button type="submit" class="btn btn-outline-danger w-100 py-3">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <img src="https://upload.wikimedia.org/wikipedia/vi/f/fe/MoMo_Logo.png" alt="MoMo" style="height: 24px; margin-right: 10px;">
                                            <div>
                                                <div class="fw-bold">MoMo</div>
                                                <small class="text-muted">Thanh toán qua MoMo</small>
                                            </div>
                                        </div>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="mt-3 text-center">
                            <a href="{{ route('patient.shop.order-detail', $donHang) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Quay lại chi tiết đơn hàng
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CỘT PHẢI: TÓM TẮT THANH TOÁN --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="mb-0 fw-bold">Tóm tắt thanh toán</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach($donHang->items as $item)
                                <div class="list-group-item d-flex align-items-center py-3">
                                    <div class="flex-shrink-0 me-3 border rounded bg-light"
                                        style="width: 50px; height: 50px;">
                                        @if (isset($item->thuoc->hinh_anh) && $item->thuoc->hinh_anh)
                                            <img src="{{ $item->thuoc->hinh_anh }}"
                                                class="w-100 h-100 object-fit-contain rounded">
                                        @else
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                                <i class="fas fa-pills"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 text-dark small fw-bold text-truncate"
                                            style="max-width: 120px;">{{ $item->thuoc->ten }}</h6>
                                        <div class="text-muted small">
                                            {{ number_format($item->don_gia, 0, ',', '.') }}đ x {{ $item->so_luong }}
                                        </div>
                                    </div>
                                    <div class="text-end fw-bold">
                                        {{ number_format($item->don_gia * $item->so_luong, 0, ',', '.') }}đ
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="p-3 border-top">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tạm tính:</span>
                                <span>{{ number_format($donHang->tong_tien, 0, ',', '.') }}đ</span>
                            </div>
                            @if($donHang->giam_gia > 0)
                                <div class="d-flex justify-content-between mb-2 text-success">
                                    <span>Giảm giá:</span>
                                    <span>-{{ number_format($donHang->giam_gia, 0, ',', '.') }}đ</span>
                                </div>
                            @endif
                            <div class="d-flex justify-content-between fw-bold fs-5 text-primary border-top pt-2">
                                <span>Tổng cộng:</span>
                                <span>{{ number_format($donHang->thanh_toan, 0, ',', '.') }}đ</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        @if(session('auto_gateway'))
            <script>
                $(function() {
                    var g = '{{ session('auto_gateway') }}';
                    if (g === 'momo') {
                        // submit momo form (opens in new tab)
                        $('#momoForm').submit();
                    } else if (g === 'vnpay') {
                        $('#vnpayForm').submit();
                    }
                });
            </script>
        @endif
    @endpush
@endsection
