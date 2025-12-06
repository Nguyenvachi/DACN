@extends('layouts.patient-modern')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-3">
                <i class="fas fa-ticket-alt text-primary me-2"></i>
                Mã giảm giá
            </h1>
            <p class="text-muted">Sử dụng mã giảm giá khi mua thuốc để được ưu đãi đặc biệt</p>
        </div>
    </div>

    @if($coupons->isEmpty())
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">Hiện chưa có mã giảm giá nào</h5>
                <p class="text-muted">Vui lòng quay lại sau để nhận ưu đãi hấp dẫn</p>
            </div>
        </div>
    @else
        <div class="row">
            @foreach($coupons as $coupon)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm hover-shadow" style="transition: all 0.3s;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    @if($coupon->loai === 'percent')
                                        <span class="badge bg-info bg-gradient">% Phần trăm</span>
                                    @else
                                        <span class="badge bg-success bg-gradient">Số tiền</span>
                                    @endif
                                </div>
                                <div class="text-end">
                                    <h3 class="mb-0 text-primary fw-bold">
                                        @if($coupon->loai === 'percent')
                                            {{ $coupon->gia_tri }}%
                                        @else
                                            {{ number_format($coupon->gia_tri/1000, 0) }}K
                                        @endif
                                    </h3>
                                    <small class="text-muted">Giảm</small>
                                </div>
                            </div>

                            <h5 class="card-title mb-2">{{ $coupon->ten }}</h5>

                            <div class="mb-3">
                                <div class="input-group">
                                    <input type="text" class="form-control bg-light" value="{{ $coupon->ma }}" readonly>
                                    <button class="btn btn-outline-primary btn-copy" type="button" data-code="{{ $coupon->ma }}" title="Sao chép mã">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>

                            <ul class="list-unstyled small text-muted mb-3">
                                @if($coupon->gia_tri_toi_thieu)
                                    <li><i class="fas fa-check-circle text-success me-2"></i>Đơn tối thiểu: <strong>{{ number_format($coupon->gia_tri_toi_thieu, 0, ',', '.') }}đ</strong></li>
                                @else
                                    <li><i class="fas fa-check-circle text-success me-2"></i>Không giới hạn đơn hàng</li>
                                @endif

                                @if($coupon->ngay_ket_thuc)
                                    <li><i class="far fa-clock text-warning me-2"></i>Hết hạn: {{ $coupon->ngay_ket_thuc->format('d/m/Y') }}</li>
                                @else
                                    <li><i class="fas fa-infinity text-info me-2"></i>Không giới hạn thời gian</li>
                                @endif

                                @if($coupon->so_lan_su_dung)
                                    <li><i class="fas fa-users text-primary me-2"></i>Còn {{ $coupon->so_lan_su_dung }} lượt</li>
                                @endif
                            </ul>

                            <a href="{{ route('patient.coupons.show', $coupon) }}" class="btn btn-sm btn-outline-primary w-100">
                                <i class="fas fa-info-circle me-1"></i> Chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@push('styles')
<style>
.hover-shadow:hover {
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15)!important;
    transform: translateY(-5px);
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    $('.btn-copy').click(function() {
        const code = $(this).data('code');
        const button = $(this);

        // Copy to clipboard
        navigator.clipboard.writeText(code).then(function() {
            const originalHtml = button.html();
            button.html('<i class="fas fa-check"></i>');
            button.removeClass('btn-outline-primary').addClass('btn-success');

            setTimeout(function() {
                button.html(originalHtml);
                button.removeClass('btn-success').addClass('btn-outline-primary');
            }, 1500);
        });
    });
});
</script>
@endpush
@endsection
