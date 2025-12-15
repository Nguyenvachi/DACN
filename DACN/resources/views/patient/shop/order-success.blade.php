@extends('layouts.patient-modern')

@section('title', 'Đặt hàng thành công')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                {{-- Success Icon & Message --}}
                <div class="text-center mb-5">
                    <div class="mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10 text-success"
                            style="width: 80px; height: 80px;">
                            <i class="fas fa-check fa-3x"></i>
                        </div>
                    </div>
                    <h2 class="fw-bold text-dark mb-2">Đặt hàng thành công!</h2>
                    <p class="text-muted">Cảm ơn bạn đã tin tưởng Healthcare Clinic.<br>Đơn hàng của bạn đang được xử lý.</p>
                </div>

                {{-- Receipt Card --}}
                <div class="card shadow-sm border-0 mb-4 position-relative overflow-hidden receipt-card">
                    {{-- Decorative Top Border --}}
                    <div class="position-absolute top-0 start-0 w-100 bg-success" style="height: 6px;"></div>

                    <div class="card-body p-4 pt-5">
                        <div class="text-center mb-4">
                            <small class="text-muted text-uppercase fw-bold ls-1">Mã đơn hàng</small>
                            <h4 class="fw-bold text-primary my-1">#{{ $donHang->id }}</h4> {{-- Hoặc $donHang->ma_don_hang nếu có --}}
                            <small class="text-muted">{{ $donHang->created_at->format('d/m/Y H:i') }}</small>
                        </div>

                        <hr class="border-dashed my-4">

                        {{-- Thông tin giao hàng --}}
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3 small text-uppercase text-muted"><i
                                    class="fas fa-map-marker-alt me-2"></i>Giao đến</h6>
                            <div class="d-flex mb-2">
                                <i class="fas fa-user text-muted mt-1 me-3" style="width: 16px;"></i>
                                <span class="fw-semibold">{{ $donHang->nguoiNhan->name ?? auth()->user()->name }}</span>
                            </div>
                            <div class="d-flex mb-2">
                                <i class="fas fa-phone text-muted mt-1 me-3" style="width: 16px;"></i>
                                <span>{{ $donHang->sdt_nguoi_nhan }}</span>
                            </div>
                            <div class="d-flex">
                                <i class="fas fa-home text-muted mt-1 me-3" style="width: 16px;"></i>
                                <span>{{ $donHang->dia_chi_giao }}</span>
                            </div>
                        </div>

                        <hr class="border-dashed my-4">

                        {{-- Tóm tắt thanh toán --}}
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Tạm tính</span>
                            <span
                                class="fw-semibold">{{ number_format($donHang->tong_tien + ($donHang->giam_gia ?? 0), 0, ',', '.') }}đ</span>
                        </div>

                        @if ($donHang->giam_gia > 0)
                            <div class="d-flex justify-content-between mb-2 text-success">
                                <span><i class="fas fa-ticket-alt me-1"></i>Giảm giá</span>
                                <span>-{{ number_format($donHang->giam_gia, 0, ',', '.') }}đ</span>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between mb-4">
                            <span class="text-muted">Phí vận chuyển</span>
                            <span class="text-success fw-bold">Miễn phí</span>
                        </div>

                        <div class="p-3 bg-light rounded d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-dark">Tổng thanh toán</span>
                            <span
                                class="fs-4 fw-bold text-primary">{{ number_format($donHang->tong_tien, 0, ',', '.') }}đ</span>
                        </div>
                    </div>

                    {{-- Decorative Bottom (Optional: Zigzag image background or just rounded) --}}
                </div>

                {{-- Actions --}}
                <div class="d-grid gap-2">
                    <a href="{{ route('patient.shop.index') }}" class="btn btn-primary btn-lg shadow-sm">
                        <i class="fas fa-shopping-bag me-2"></i>Tiếp tục mua sắm
                    </a>
                    <a href="{{ route('patient.shop.orders') }}" class="btn btn-link text-muted text-decoration-none">
                        Xem lịch sử đơn hàng
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .ls-1 {
                letter-spacing: 1px;
            }

            .border-dashed {
                border-top: 2px dashed #e9ecef;
                opacity: 1;
            }

            .receipt-card {
                background-image: radial-gradient(circle at 0 0, transparent 10px, #fff 11px), radial-gradient(circle at 100% 0, transparent 10px, #fff 11px), radial-gradient(circle at 100% 100%, transparent 10px, #fff 11px), radial-gradient(circle at 0 100%, transparent 10px, #fff 11px);
                background-position: top left, top right, bottom right, bottom left;
                background-size: 51% 51%;
                background-repeat: no-repeat;
            }
        </style>
    @endpush
@endsection
