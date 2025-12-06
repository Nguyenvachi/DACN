@extends('layouts.patient-modern')

@section('title', 'Chi tiết hóa đơn #' . $hoaDon->id)
@section('page-title', 'Chi tiết hóa đơn #' . $hoaDon->id)
@section('page-subtitle', 'Xem thông tin chi tiết hóa đơn')

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        {{-- Thông tin hóa đơn --}}
        <div class="card mb-4">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-file-invoice me-2"></i>Thông tin hóa đơn</h5>
                    @if($hoaDon->trang_thai === 'chua_thanh_toan')
                        <span class="badge badge-warning">Chưa thanh toán</span>
                    @elseif($hoaDon->trang_thai === 'da_thanh_toan')
                        <span class="badge badge-success">Đã thanh toán</span>
                    @elseif($hoaDon->trang_thai === 'thanh_toan_mot_phan')
                        <span class="badge badge-info">Thanh toán 1 phần</span>
                    @elseif($hoaDon->trang_thai === 'hoan_tien')
                        <span class="badge badge-danger">Hoàn tiền</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted mb-1">Mã hóa đơn:</p>
                        <p class="fw-bold">#{{ $hoaDon->id }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1">Ngày tạo:</p>
                        <p class="fw-bold">{{ $hoaDon->ngay_tao?->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                @if($hoaDon->lichHen)
                    <div class="alert alert-info">
                        <h6 class="alert-heading"><i class="fas fa-calendar-check me-2"></i>Thông tin lịch hẹn</h6>
                        <p class="mb-1"><strong>Bác sĩ:</strong> {{ $hoaDon->lichHen->bacSi->ten_bac_si }}</p>
                        <p class="mb-1"><strong>Chuyên khoa:</strong> {{ $hoaDon->lichHen->bacSi->chuyenKhoa->ten_chuyen_khoa ?? 'N/A' }}</p>
                        <p class="mb-0"><strong>Ngày khám:</strong> {{ $hoaDon->lichHen->ngay_gio_kham?->format('d/m/Y H:i') }}</p>
                    </div>
                @endif

                <h6 class="mb-3">Chi tiết dịch vụ:</h6>
                <div class="table-responsive">
                    <table class="table">
                        <thead class="bg-light">
                            <tr>
                                <th>Dịch vụ</th>
                                <th class="text-end">Số lượng</th>
                                <th class="text-end">Đơn giá</th>
                                <th class="text-end">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($hoaDon->lichHen && $hoaDon->lichHen->dichVu)
                                <tr>
                                    <td>{{ $hoaDon->lichHen->dichVu->ten_dich_vu }}</td>
                                    <td class="text-end">1</td>
                                    <td class="text-end">{{ number_format($hoaDon->lichHen->dichVu->gia) }}đ</td>
                                    <td class="text-end">{{ number_format($hoaDon->lichHen->dichVu->gia) }}đ</td>
                                </tr>
                            @endif

                            @foreach($hoaDon->chiTiets ?? [] as $chiTiet)
                                <tr>
                                    <td>{{ $chiTiet->ten_dich_vu }}</td>
                                    <td class="text-end">{{ $chiTiet->so_luong }}</td>
                                    <td class="text-end">{{ number_format($chiTiet->don_gia) }}đ</td>
                                    <td class="text-end">{{ number_format($chiTiet->thanh_tien) }}đ</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-light">
                            <tr>
                                <td colspan="3" class="text-end"><strong>Tổng cộng:</strong></td>
                                <td class="text-end"><strong>{{ number_format($hoaDon->tong_tien) }}đ</strong></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end">Đã thanh toán:</td>
                                <td class="text-end text-success">{{ number_format($hoaDon->da_thanh_toan) }}đ</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Còn lại:</strong></td>
                                <td class="text-end"><strong class="{{ $hoaDon->con_lai > 0 ? 'text-danger' : 'text-success' }}">{{ number_format($hoaDon->con_lai) }}đ</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Lịch sử thanh toán --}}
        @if($hoaDon->paymentLogs && $hoaDon->paymentLogs->count() > 0)
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Lịch sử thanh toán</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Thời gian</th>
                                    <th>Số tiền</th>
                                    <th>Phương thức</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hoaDon->paymentLogs as $log)
                                    <tr>
                                        <td>{{ $log->created_at?->format('d/m/Y H:i') }}</td>
                                        <td><strong>{{ number_format($log->so_tien) }}đ</strong></td>
                                        <td>
                                            @if($log->phuong_thuc === 'vnpay')
                                                <span class="badge badge-info">VNPay</span>
                                            @elseif($log->phuong_thuc === 'momo')
                                                <span class="badge" style="background: #d82d8b; color: white;">MoMo</span>
                                            @elseif($log->phuong_thuc === 'cash')
                                                <span class="badge badge-success">Tiền mặt</span>
                                            @else
                                                <span class="badge badge-secondary">{{ $log->phuong_thuc }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($log->trang_thai === 'thanh_cong')
                                                <span class="badge badge-success">Thành công</span>
                                            @else
                                                <span class="badge badge-danger">Thất bại</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="col-lg-4">
        {{-- Thông tin bệnh nhân --}}
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="fas fa-user me-2"></i>Thông tin bệnh nhân</h6>
            </div>
            <div class="card-body">
                <p class="mb-2"><strong>Họ tên:</strong> {{ $hoaDon->user->name }}</p>
                <p class="mb-2"><strong>Email:</strong> {{ $hoaDon->user->email }}</p>
                <p class="mb-2"><strong>Số điện thoại:</strong> {{ $hoaDon->user->so_dien_thoai ?? 'N/A' }}</p>
                <p class="mb-0"><strong>Ngày sinh:</strong> {{ $hoaDon->user->ngay_sinh ? \Carbon\Carbon::parse($hoaDon->user->ngay_sinh)->format('d/m/Y') : 'N/A' }}</p>
            </div>
        </div>

        {{-- Hành động --}}
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="fas fa-tasks me-2"></i>Hành động</h6>
            </div>
            <div class="card-body d-grid gap-2">
                @if($hoaDon->con_lai > 0 && $hoaDon->lichHen)
                    <a href="{{ route('patient.payment', $hoaDon->lichHen) }}" class="btn btn-primary">
                        <i class="fas fa-credit-card me-2"></i>Thanh toán ngay
                    </a>
                @endif

                @if($hoaDon->trang_thai === 'da_thanh_toan')
                    <a href="{{ route('admin.hoadon.receipt', $hoaDon) }}" target="_blank" class="btn btn-success">
                        <i class="fas fa-download me-2"></i>Tải hóa đơn PDF
                    </a>
                @endif

                <button onclick="window.print()" class="btn btn-outline-primary">
                    <i class="fas fa-print me-2"></i>In hóa đơn
                </button>

                <a href="{{ route('patient.hoadon.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
