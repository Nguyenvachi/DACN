@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">

        {{-- ============================
         🔥 HEADER
    ============================= --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="fas fa-file-invoice-dollar me-2"></i>
                Hóa đơn {{ $hoaDon->ma_hoa_don ?? '#' . $hoaDon->id }}
            </h2>
            <div class="d-flex gap-2">
                @if(isset($hoaDon->so_tien_da_thanh_toan) && $hoaDon->so_tien_da_thanh_toan > 0 && $hoaDon->status != 'refunded')
                    <a href="{{ route('admin.hoadon.refund.form', $hoaDon->id) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-arrow-return-left"></i> Hoàn tiền
                    </a>
                @endif
                @if($hoaDon->hoanTiens && $hoaDon->hoanTiens->count() > 0)
                    <a href="{{ route('admin.hoadon.refunds.list', $hoaDon->id) }}" class="btn btn-info btn-sm">
                        <i class="bi bi-clock-history"></i> Lịch sử hoàn tiền
                    </a>
                @endif
                <a href="{{ route('admin.hoadon.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>
        </div>

        {{-- ============================
         🔥 ALERT
    ============================= --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif


        {{-- ============================
         🔥 THÔNG TIN HÓA ĐƠN
    ============================= --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">

                @php
                    $statusColor = match ($hoaDon->trang_thai) {
                        'Đã thanh toán' => 'success',
                        'Chưa thanh toán' => 'warning',
                        'Hủy' => 'danger',
                        default => 'secondary',
                    };
                @endphp

                <div class="row g-4">

                    <div class="col-md-6">
                        <h6 class="text-muted">Mã hóa đơn</h6>
                        <p class="fw-bold mb-2">{{ $hoaDon->ma_hoa_don ?? '#' . $hoaDon->id }}</p>

                        <h6 class="text-muted">Lịch hẹn</h6>
                        <p class="fw-bold mb-2">#{{ $hoaDon->lich_hen_id }}</p>

                        <h6 class="text-muted">Bệnh nhân</h6>
                        <p class="fw-bold mb-2">{{ optional($hoaDon->user)->name ?? '#' . $hoaDon->user_id }}</p>

                        <h6 class="text-muted">Tổng tiền</h6>
                        <p class="fw-bold text-primary fs-5 mb-2">
                            {{ number_format($hoaDon->tong_tien, 0, ',', '.') }} đ
                        </p>

                        @if(isset($hoaDon->so_tien_da_thanh_toan))
                            <h6 class="text-muted">Đã thanh toán</h6>
                            <p class="fw-bold text-success mb-2">
                                {{ number_format($hoaDon->so_tien_da_thanh_toan, 0, ',', '.') }} đ
                            </p>
                        @endif

                        @if(isset($hoaDon->so_tien_da_hoan) && $hoaDon->so_tien_da_hoan > 0)
                            <h6 class="text-muted">Đã hoàn</h6>
                            <p class="fw-bold text-warning mb-2">
                                {{ number_format($hoaDon->so_tien_da_hoan, 0, ',', '.') }} đ
                            </p>
                        @endif

                        @if(isset($hoaDon->so_tien_con_lai))
                            <h6 class="text-muted">Còn lại</h6>
                            <p class="fw-bold text-danger mb-2">
                                {{ number_format($hoaDon->so_tien_con_lai, 0, ',', '.') }} đ
                            </p>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <h6 class="text-muted">Trạng thái</h6>
                        <p>
                            <span class="badge bg-{{ $statusColor }} fs-6 px-3 py-2">
                                {{ $hoaDon->trang_thai }}
                            </span>
                            @if(isset($hoaDon->status))
                                <span class="badge bg-secondary fs-6 px-3 py-2 ms-2">
                                    {{ strtoupper($hoaDon->status) }}
                                </span>
                            @endif
                        </p>

                        <h6 class="text-muted">Phương thức thanh toán</h6>
                        <p class="fw-bold">{{ $hoaDon->phuong_thuc ?? '—' }}</p>

                        <h6 class="text-muted">Ghi chú</h6>
                        <p>{{ $hoaDon->ghi_chu ?? '—' }}</p>

                        <h6 class="text-muted">Ngày tạo</h6>
                        <p>{{ $hoaDon->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>

                </div>

            </div>
        </div>



        {{-- ============================
         🔥 DANH SÁCH THANH TOÁN
    ============================= --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-0">
                <h5 class="fw-semibold mb-0">
                    <i class="fas fa-receipt me-1"></i> Lịch sử thanh toán
                </h5>
            </div>

            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="15%">Provider</th>
                                <th width="15%">Số tiền</th>
                                <th width="15%">Trạng thái</th>
                                <th width="20%">Mã giao dịch</th>
                                <th width="20%">Thời gian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($hoaDon->thanhToans as $tt)
                                <tr>
                                    <td>{{ $tt->id }}</td>
                                    <td><span class="badge bg-info">{{ strtoupper($tt->provider) }}</span></td>
                                    <td class="fw-bold text-success">
                                        {{ number_format($tt->so_tien, 0, ',', '.') }} đ
                                    </td>
                                    <td>
                                        <span
                                            class="badge
                                    @if ($tt->trang_thai === 'success') bg-success
                                    @elseif($tt->trang_thai === 'pending') bg-warning
                                    @else bg-danger @endif">
                                            {{ $tt->trang_thai }}
                                        </span>
                                    </td>
                                    <td>{{ $tt->transaction_ref ?? '-' }}</td>
                                    <td>{{ $tt->paid_at ?? $tt->created_at }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-3 text-muted">
                                        <i class="fas fa-inbox me-2"></i> Chưa có thanh toán
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>


        {{-- ============================
         🔥 FORM THU TIỀN / ONLINE
    ============================= --}}
        <div class="row g-4 mb-4">

            {{-- Thu tiền mặt --}}
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h5 class="fw-semibold mb-3">
                            <i class="fas fa-hand-holding-usd me-2 text-success"></i>Thu tiền mặt
                        </h5>

                        <form method="POST" action="{{ route('admin.hoadon.cash_collect', $hoaDon) }}" class="row g-3">
                            @csrf
                            <div class="col-12">
                                <label class="form-label">Số tiền</label>
                                <input type="number" name="so_tien" class="form-control"
                                    value="{{ (int) $hoaDon->tong_tien }}" min="0" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Ghi chú (tùy chọn)</label>
                                <input type="text" name="ghi_chu" class="form-control">
                            </div>

                            <div class="col-12 text-end">
                                <button class="btn btn-success">
                                    <i class="fas fa-check-circle me-1"></i> Xác nhận thanh toán
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Thanh toán online --}}
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h5 class="fw-semibold mb-3">
                            <i class="fas fa-credit-card me-2 text-primary"></i>Thanh toán Online
                        </h5>

                        {{-- VNPay --}}
                        <form method="POST" action="{{ route('vnpay.create') }}" class="mb-2">
                            @csrf
                            <input type="hidden" name="hoa_don_id" value="{{ $hoaDon->id }}">
                            <input type="hidden" name="amount" value="{{ $hoaDon->tong_tien }}">
                            <button class="btn btn-primary w-100" {{ $hoaDon->tong_tien == 0 ? 'disabled' : '' }}>
                                Thanh toán qua VNPay
                            </button>
                        </form>

                        {{-- MoMo --}}
                        <form method="POST" action="{{ route('momo.create') }}">
                            @csrf
                            <input type="hidden" name="hoa_don_id" value="{{ $hoaDon->id }}">
                            <input type="hidden" name="amount" value="{{ $hoaDon->tong_tien }}">
                            <button class="btn btn-danger w-100" {{ $hoaDon->tong_tien == 0 ? 'disabled' : '' }}>
                                Thanh toán qua MoMo
                            </button>
                        </form>

                    </div>
                </div>
            </div>

        </div>


        {{-- ============================
        🔥 ACTION BUTTONS
    ============================= --}}
        <div class="d-flex gap-2">
            <a class="btn btn-outline-dark" href="{{ route('admin.hoadon.receipt', $hoaDon) }}">
                <i class="fas fa-file-pdf me-1"></i> Tải biên lai (PDF)
            </a>

            <a class="btn btn-outline-info" href="{{ route('admin.hoadon.payment_logs', $hoaDon) }}">
                <i class="bi bi-clock-history me-1"></i> Payment Logs
            </a>

            {{-- Các nút tải theo loại hóa đơn (Parent file: resources/views/admin/hoadon/show.blade.php) --}}
            <div class="btn-group" role="group" aria-label="Hoá đơn theo loại">
                <a class="btn btn-outline-secondary" href="{{ route('admin.hoadon.receipt.type', [$hoaDon, 'phieu-thu']) }}">
                    <i class="fas fa-receipt me-1"></i> Phiếu thu khám
                </a>
                <a class="btn btn-outline-secondary" href="{{ route('admin.hoadon.receipt.type', [$hoaDon, 'dich-vu']) }}">
                    <i class="fas fa-stethoscope me-1"></i> Hóa đơn dịch vụ
                </a>
                <a class="btn btn-outline-secondary" href="{{ route('admin.hoadon.receipt.type', [$hoaDon, 'thuoc']) }}">
                    <i class="fas fa-pills me-1"></i> Hóa đơn thuốc
                </a>
                <a class="btn btn-outline-secondary" href="{{ route('admin.hoadon.receipt.type', [$hoaDon, 'tong-hop']) }}">
                    <i class="fas fa-layer-group me-1"></i> Hóa đơn tổng hợp
                </a>
            </div>
        </div>

    </div>
@endsection
