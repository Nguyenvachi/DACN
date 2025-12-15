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
                <a href="{{ route('admin.hoadon.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>
        </div>

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
         🔥 CHI TIẾT DỊCH VỤ
    ============================= --}}
        @if($hoaDon->chiTiets && $hoaDon->chiTiets->count() > 0)
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-0">
                <h5 class="fw-semibold mb-0">
                    <i class="bi bi-list-check me-1"></i> Chi tiết dịch vụ
                </h5>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="15%">Loại dịch vụ</th>
                                <th width="30%">Tên dịch vụ</th>
                                <th width="15%">Số lượng</th>
                                <th width="15%">Đơn giá</th>
                                <th width="20%" class="text-end">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hoaDon->chiTiets as $index => $ct)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @php
                                            $badgeColor = match($ct->loai_dich_vu) {
                                                'thuoc' => 'secondary',
                                                'noi_soi' => 'info',
                                                'x_quang' => 'warning',
                                                'xet_nghiem' => 'primary',
                                                'thu_thuat' => 'danger',
                                                'dich_vu_kham' => 'success',
                                                default => 'dark'
                                            };
                                            $labelText = match($ct->loai_dich_vu) {
                                                'thuoc' => 'Thuốc',
                                                'noi_soi' => 'Nội soi',
                                                'x_quang' => 'X-quang',
                                                'xet_nghiem' => 'Xét nghiệm',
                                                'thu_thuat' => 'Thủ thuật',
                                                'dich_vu_kham' => 'Dịch vụ khám',
                                                default => ucfirst($ct->loai_dich_vu)
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $badgeColor }}">{{ $labelText }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $ct->ten_dich_vu }}</strong>
                                        @if($ct->mo_ta)
                                            <br><small class="text-muted">{{ $ct->mo_ta }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $ct->so_luong }}</td>
                                    <td>{{ number_format($ct->don_gia, 0, ',', '.') }} đ</td>
                                    <td class="text-end fw-bold text-primary">{{ number_format($ct->thanh_tien, 0, ',', '.') }} đ</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="5" class="text-end">TỔNG CỘNG:</th>
                                <th class="text-end text-danger fs-5">{{ number_format($hoaDon->tong_tien, 0, ',', '.') }} đ</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        @endif



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
         🔥 THÔNG TIN BỔ SUNG
    ============================= --}}
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Lưu ý:</strong> Admin chỉ có quyền xem hóa đơn. Để thu tiền, thanh toán và tải biên lai, vui lòng liên hệ nhân viên.
        </div>

    </div>
@endsection
