@extends('layouts.patient-modern')

@section('title', 'Chi tiết hóa đơn #' . $hoaDon->id)
@section('page-title', 'Chi tiết hóa đơn #' . $hoaDon->id)
@section('page-subtitle', 'Xem thông tin chi tiết và lịch sử thanh toán')

@section('content')
    <div class="row g-4">
        {{-- Cột trái: Thông tin chi tiết & Lịch sử --}}
        <div class="col-lg-8">
            {{-- 1. Card thông tin dịch vụ và tính toán tiền --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary"><i class="fas fa-file-invoice me-2"></i>Thông tin hóa đơn</h5>
                        {{-- Badge trạng thái --}}
                        @php
                            $statusLabels = [
                                'unpaid' => ['class' => 'warning', 'text' => 'Chưa thanh toán'],
                                'paid' => ['class' => 'success', 'text' => 'Đã thanh toán'],
                                'partial' => ['class' => 'info', 'text' => 'Thanh toán 1 phần'],
                                'partial_refund' => ['class' => 'info', 'text' => 'Hoàn một phần'],
                                'refunded' => ['class' => 'danger', 'text' => 'Đã hoàn tiền'],
                            ];
                            $currStatus = $statusLabels[$hoaDon->status] ?? [
                                'class' => 'secondary',
                                'text' => $hoaDon->status,
                            ];
                        @endphp
                        <span class="badge badge-{{ $currStatus['class'] }}">{{ $currStatus['text'] }}</span>
                    </div>
                </div>

                <div class="card-body">
                    {{-- Thông tin chung (Mã & Ngày) --}}
                    <div class="row mb-4">
                        <div class="col-6">
                            <small class="text-muted text-uppercase fw-bold">Mã hóa đơn</small>
                            <div class="fs-5 fw-bold text-dark">#{{ $hoaDon->id }}</div>
                        </div>
                        <div class="col-6 text-end">
                            <small class="text-muted text-uppercase fw-bold">Ngày tạo</small>
                            <div class="fs-5 text-dark">{{ optional($hoaDon->created_at)->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>

                    {{-- Thông tin Lịch hẹn (nếu có) --}}
                    @if ($hoaDon->lichHen)
                        <div class="bg-light p-3 rounded mb-4 border">
                            <h6 class="text-dark mb-3"><i class="fas fa-calendar-alt me-2"></i>Thông tin khám bệnh</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <span class="d-block text-muted small">Bác sĩ phụ trách:</span>
                                    <span class="fw-bold">
                                        {{ $hoaDon->lichHen->bacSi->ho_ten ?? (optional($hoaDon->lichHen->bacSi->user)->name ?? 'N/A') }}
                                    </span>
                                </div>
                                <div class="col-md-6">
                                    <span class="d-block text-muted small">Thời gian khám:</span>
                                    <span class="fw-bold">
                                        {{ optional($hoaDon->lichHen->ngay_hen)->format('d/m/Y') }}
                                        <span class="badge bg-secondary ms-1">{{ $hoaDon->lichHen->thoi_gian_hen }}</span>
                                    </span>
                                </div>
                                <div class="col-12">
                                    <span class="d-block text-muted small">Chuyên khoa:</span>
                                    <span>
                                        @if ($hoaDon->lichHen->bacSi && $hoaDon->lichHen->bacSi->chuyenKhoas->count())
                                            {{ $hoaDon->lichHen->bacSi->chuyenKhoas->pluck('ten')->join(', ') }}
                                        @else
                                            <span class="text-muted fst-italic">Không xác định</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Bảng chi tiết dịch vụ --}}
                    <h6 class="mb-3">Chi tiết dịch vụ</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="bg-light text-secondary">
                                <tr>
                                    <th>Dịch vụ</th>
                                    <th class="text-center" style="width: 100px;">SL</th>
                                    <th class="text-end" style="width: 150px;">Đơn giá</th>
                                    <th class="text-end" style="width: 150px;">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Dịch vụ chính từ lịch hẹn --}}
                                @if ($hoaDon->lichHen && $hoaDon->lichHen->dichVu)
                                    <tr>
                                        <td>{{ $hoaDon->lichHen->dichVu->ten_dich_vu }}</td>
                                        <td class="text-center">1</td>
                                        <td class="text-end">{{ number_format($hoaDon->lichHen->dichVu->gia) }}đ</td>
                                        <td class="text-end fw-bold">{{ number_format($hoaDon->lichHen->dichVu->gia) }}đ
                                        </td>
                                    </tr>
                                @endif

                                {{-- Các chi tiết đơn thuốc / dịch vụ thêm --}}
                                @foreach ($hoaDon->chiTiets ?? [] as $chiTiet)
                                    <tr>
                                        <td>{{ $chiTiet->ten_dich_vu }}</td>
                                        <td class="text-center">{{ $chiTiet->so_luong }}</td>
                                        <td class="text-end">{{ number_format($chiTiet->don_gia) }}đ</td>
                                        <td class="text-end fw-bold">{{ number_format($chiTiet->thanh_tien) }}đ</td>
                                    </tr>
                                @endforeach
                            </tbody>

                            {{-- Phần tổng cộng và tính toán --}}
                            @php
                                $paid = $hoaDon->so_tien_da_thanh_toan ?? 0; // Tổng tiền đã nộp vào
                                $refunded = $hoaDon->so_tien_da_hoan ?? 0; // Tổng tiền đã hoàn ra
                                $net = max(0, $paid - $refunded); // Thực thu = Nộp - Hoàn
                                $total = $hoaDon->tong_tien ?? 0;
                                $remaining = $hoaDon->so_tien_con_lai ?? 0;
                            @endphp

                            <tfoot class="border-top-2">
                                <tr>
                                    <td colspan="3" class="text-end text-muted">Tổng cộng:</td>
                                    <td class="text-end fw-bold fs-6">{{ number_format($total) }}đ</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end text-muted">
                                        Đã thanh toán (Thực nộp):
                                        <div style="font-size: 0.85em;" class="fw-normal text-muted fst-italic">
                                            (Tổng nộp: {{ number_format($paid) }}đ - Đã hoàn:
                                            {{ number_format($refunded) }}đ)
                                        </div>
                                    </td>
                                    <td class="text-end text-success fw-bold">
                                        {{ number_format($net) }}đ
                                    </td>
                                </tr>
                                <tr class="bg-light">
                                    <td colspan="3" class="text-end text-uppercase fw-bold text-dark align-middle">
                                        Số tiền còn lại phải thanh toán:
                                    </td>
                                    <td class="text-end">
                                        @if ($remaining > 0)
                                            <span class="text-danger fw-bold fs-5">{{ number_format($remaining) }}đ</span>
                                        @else
                                            <span class="text-success fw-bold"><i
                                                    class="fas fa-check-circle me-1"></i>0đ</span>
                                        @endif
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            {{-- 2. Card Lịch sử giao dịch --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Lịch sử giao dịch</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Thời gian</th>
                                    <th>Loại giao dịch</th>
                                    <th>Phương thức</th>
                                    <th class="text-end pe-4">Số tiền</th>
                                    <th class="text-center">Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Hiển thị Thanh Toán --}}
                                @forelse ($hoaDon->thanhToans as $p)
                                    <tr>
                                        <td class="ps-4 text-muted">
                                            {{ $p->paid_at?->format('d/m/Y H:i') ?? $p->created_at?->format('d/m/Y H:i') }}
                                        </td>
                                        <td>
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success">
                                                <i class="fas fa-arrow-down me-1"></i>Thanh toán
                                            </span>
                                        </td>
                                        <td class="fw-bold text-uppercase text-secondary">{{ $p->provider }}</td>
                                        <td class="text-end pe-4 fw-bold text-dark">+{{ number_format($p->so_tien) }}đ</td>
                                        <td class="text-center">
                                            @if (in_array(strtolower($p->trang_thai), ['thành công', 'succeeded', 'thanh_cong']))
                                                <i class="fas fa-check-circle text-success" title="Thành công"></i>
                                            @else
                                                <span class="badge bg-danger">{{ $p->trang_thai }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    {{-- Không hiện gì nếu chưa có thanh toán --}}
                                @endforelse

                                {{-- Hiển thị Hoàn Tiền --}}
                                @foreach ($hoaDon->hoanTiens as $r)
                                    <tr class="table-warning bg-opacity-10">
                                        <td class="ps-4 text-muted">{{ $r->created_at?->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger">
                                                <i class="fas fa-undo me-1"></i>Hoàn tiền
                                            </span>
                                        </td>
                                        <td class="fw-bold text-uppercase text-secondary">HỆ THỐNG</td>
                                        <td class="text-end pe-4 fw-bold text-danger">-{{ number_format($r->so_tien) }}đ
                                        </td>
                                        <td class="text-center">
                                            <i class="fas fa-check-circle text-success" title="Đã hoàn"></i>
                                        </td>
                                    </tr>
                                @endforeach

                                @if ($hoaDon->thanhToans->isEmpty() && $hoaDon->hoanTiens->isEmpty())
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                            Chưa có giao dịch nào được ghi nhận.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Cột phải: Thông tin & Hành động --}}
        <div class="col-lg-4">
            {{-- 1. Card Bệnh nhân --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-user-circle me-2"></i>Thông tin bệnh nhân</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-light rounded-circle p-3 me-3 text-primary">
                            <i class="fas fa-user fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">{{ $hoaDon->user->name }}</h6>
                            <small class="text-muted">{{ $hoaDon->user->email }}</small>
                        </div>
                    </div>
                    <hr class="my-3">
                    <p class="mb-2 d-flex justify-content-between">
                        <span class="text-muted">Số điện thoại:</span>
                        <span class="fw-bold">{{ $hoaDon->user->so_dien_thoai ?? '---' }}</span>
                    </p>
                    <p class="mb-0 d-flex justify-content-between">
                        <span class="text-muted">Ngày sinh:</span>
                        <span class="fw-bold">
                            {{ $hoaDon->user->ngay_sinh ? \Carbon\Carbon::parse($hoaDon->user->ngay_sinh)->format('d/m/Y') : '---' }}
                        </span>
                    </p>
                </div>
            </div>

            {{-- 2. Card Hành động (Thanh toán) --}}
            <div class="card shadow-sm sticky-top" style="top: 20px; z-index: 1;">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-wallet me-2"></i>Thanh toán & Tùy chọn</h6>
                </div>
                <div class="card-body">
                    @php
                        // Logic tính toán hiển thị nút thanh toán
                        $amountToPay = max(0, (int) $remaining);
                        $canPay = $amountToPay > 0;
                    @endphp

                    @if ($canPay)
                        <div class="alert alert-warning border-warning d-flex align-items-center mb-3" role="alert">
                            <i class="fas fa-exclamation-circle fa-lg me-2"></i>
                            <div>
                                Cần thanh toán: <strong>{{ number_format($amountToPay) }}đ</strong>
                            </div>
                        </div>

                        {{-- Nút thanh toán nhanh --}}
                        @if ($hoaDon->lichHen)
                            <div class="d-grid gap-2 mb-3">
                                <label class="text-muted small fw-bold mb-1">Chọn cổng thanh toán:</label>
                                <div class="row g-2">
                                    {{-- VNPay --}}
                                    <div class="col-6">
                                        <form method="POST" action="{{ route('vnpay.create') }}">
                                            @csrf
                                            <input type="hidden" name="hoa_don_id" value="{{ $hoaDon->id }}">
                                            <input type="hidden" name="amount" value="{{ $amountToPay }}">
                                            <button type="submit" class="btn btn-outline-primary w-100 py-2 h-100">
                                                <img src="https://vnpay.vn/assets/images/logo-icon/logo-primary.svg"
                                                    alt="VNPay" style="height: 20px;">
                                                <div class="small mt-1">VNPAY</div>
                                            </button>
                                        </form>
                                    </div>
                                    {{-- MoMo --}}
                                    <div class="col-6">
                                        <form method="POST" action="{{ route('momo.create') }}">
                                            @csrf
                                            <input type="hidden" name="hoa_don_id" value="{{ $hoaDon->id }}">
                                            <input type="hidden" name="amount" value="{{ $amountToPay }}">
                                            <button type="submit" class="btn btn-outline-danger w-100 py-2 h-100">
                                                <img src="https://upload.wikimedia.org/wikipedia/vi/f/fe/MoMo_Logo.png"
                                                    alt="MoMo" style="height: 20px;">
                                                <div class="small mt-1">MoMo</div>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-secondary mb-3">
                                <small>Hóa đơn này chưa liên kết lịch hẹn nên không thể thanh toán online.</small>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-success border-success mb-3 text-center">
                            <i class="fas fa-check-circle fa-2x mb-2"></i><br>
                            Hóa đơn đã được thanh toán đầy đủ.
                        </div>
                    @endif

                    <hr>

                    {{-- Các nút chức năng phụ --}}
                    <div class="d-grid gap-2">
                        @if ($hoaDon->status === 'paid' || $hoaDon->status === 'da_thanh_toan')
                            <a href="{{ route('admin.hoadon.receipt', $hoaDon) }}" target="_blank"
                                class="btn btn-light border text-dark">
                                <i class="fas fa-file-pdf text-danger me-2"></i>Xuất PDF
                            </a>
                        @endif

                        <button onclick="window.print()" class="btn btn-light border text-dark">
                            <i class="fas fa-print text-secondary me-2"></i>In hóa đơn
                        </button>

                        <a href="{{ route('patient.hoadon.index') }}"
                            class="btn btn-link text-decoration-none text-muted">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
