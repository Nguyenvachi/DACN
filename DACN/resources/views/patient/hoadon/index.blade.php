@extends('layouts.patient-modern')

@section('title', 'Quản lý hóa đơn')
@section('page-title', 'Quản lý hóa đơn')
@section('page-subtitle', 'Xem và thanh toán các hóa đơn của bạn')

@section('content')
    <div class="row g-4">
        {{-- Thống kê tổng quan --}}
        <div class="col-12">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted mb-1">Tổng hóa đơn</p>
                                    <h3 class="mb-0">{{ $statistics['total'] }}</h3>
                                </div>
                                <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                    <i class="fas fa-file-invoice text-primary fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted mb-1">Chưa thanh toán</p>
                                    <h3 class="mb-0 text-warning">{{ $statistics['unpaid'] }}</h3>
                                </div>
                                <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                    <i class="fas fa-clock text-warning fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted mb-1">Đã thanh toán</p>
                                    <h3 class="mb-0 text-success">{{ $statistics['paid'] }}</h3>
                                </div>
                                <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                    <i class="fas fa-check-circle text-success fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted mb-1">Tổng chi phí</p>
                                    <h3 class="mb-0">{{ number_format($statistics['total_amount']) }}đ</h3>
                                </div>
                                <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                    <i class="fas fa-money-bill-wave text-info fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Danh sách hóa đơn --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white d-flex align-items-center justify-content-between">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Danh sách hóa đơn</h5>
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm" id="filter-status">
                            <option value="">Tất cả trạng thái</option>
                            <option value="unpaid">Chưa thanh toán</option>
                            <option value="paid">Đã thanh toán</option>
                            <option value="refunded">Hoàn tiền</option>
                        </select>
                        <button class="btn btn-sm btn-primary" onclick="window.print()">
                            <i class="fas fa-print me-1"></i>In
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="patientHoaDonTable" class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Mã HĐ</th>
                                    <th>Ngày tạo</th>
                                    <th>Lịch hẹn</th>
                                    <th>Tổng tiền</th>
                                    <th>Đã thanh toán</th>
                                    <th>Còn lại</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($hoaDons as $hoaDon)
                                    <tr>
                                        <td><strong>#{{ $hoaDon->id }}</strong></td>
                                        <td>{{ optional($hoaDon->created_at)->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @if ($hoaDon->lichHen)
                                                <small class="text-muted">
                                                    {{ $hoaDon->lichHen->bacSi->ho_ten ?? (optional($hoaDon->lichHen->bacSi->user)->name ?? '-') }}<br>
                                                    @php
                                                        $ngay = $hoaDon->lichHen->ngay_hen ?? null;
                                                        $thoigian = $hoaDon->lichHen->thoi_gian_hen ?? null;
                                                    @endphp
                                                    {{ $ngay ? \Carbon\Carbon::parse($ngay)->format('d/m/Y') : '-' }}{{ $thoigian ? ' ' . $thoigian : '' }}
                                                </small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td><strong>{{ number_format($hoaDon->tong_tien) }}đ</strong></td>
                                        @php
                                            $paid = $hoaDon->so_tien_da_thanh_toan ?? 0;
                                            $refunded = $hoaDon->so_tien_da_hoan ?? 0;
                                            $net = max(0, $paid - $refunded);
                                        @endphp
                                        <td>
                                            <div>{{ number_format($net) }}đ</div>
                                            <small class="text-muted">(Nộp: {{ number_format($paid) }}đ · Hoàn:
                                                {{ number_format($refunded) }}đ)</small>
                                        </td>
                                        <td>
                                            @if (($hoaDon->so_tien_con_lai ?? 0) > 0)
                                                <span
                                                    class="text-danger fw-bold">{{ number_format($hoaDon->so_tien_con_lai ?? 0) }}đ</span>
                                            @else
                                                <span class="text-success">0đ</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($hoaDon->status === 'unpaid')
                                                <span class="badge badge-warning">Chưa thanh toán</span>
                                            @elseif($hoaDon->status === 'paid')
                                                <span class="badge badge-success">Đã thanh toán</span>
                                            @elseif($hoaDon->status === 'partial')
                                                <span class="badge badge-info">Thanh toán 1 phần</span>
                                            @elseif($hoaDon->status === 'refunded')
                                                <span class="badge badge-danger">Hoàn tiền</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('patient.hoadon.show', $hoaDon) }}"
                                                    class="btn btn-outline-primary" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if (($hoaDon->so_tien_con_lai ?? 0) > 0)
                                                    <a href="{{ route('patient.lichhen.payment', $hoaDon->lichHen) }}"
                                                        class="btn btn-outline-success" title="Thanh toán">
                                                        <i class="fas fa-credit-card"></i>
                                                    </a>
                                                @endif
                                                @if ($hoaDon->status === 'paid')
                                                    <a href="{{ route('admin.hoadon.receipt', $hoaDon) }}" target="_blank"
                                                        class="btn btn-outline-info" title="Tải hóa đơn">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5 text-muted">
                                            <i class="fas fa-inbox fs-1 d-block mb-3"></i>
                                            Chưa có hóa đơn nào
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($hoaDons->hasPages())
                    <div class="card-footer bg-white">
                        {{ $hoaDons->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Filter status
            document.getElementById('filter-status')?.addEventListener('change', function() {
                const status = this.value;
                const url = new URL(window.location.href);
                if (status) {
                    url.searchParams.set('status', status);
                } else {
                    url.searchParams.delete('status');
                }
                window.location.href = url.toString();
            });

            // DataTables (avoid conflict with Laravel pagination)
            $(function() {
                const tableSelector = '#patientHoaDonTable';
                if (!window.jQuery || !$.fn?.DataTable || !$(tableSelector).length) return;
                if ($.fn.DataTable.isDataTable(tableSelector)) return;

                const dt = $(tableSelector).DataTable({
                    paging: false,
                    searching: false,
                    info: false,
                    lengthChange: false,
                    responsive: false,
                    scrollX: true,
                    autoWidth: true,
                    order: [],
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/vi.json'
                    },
                    columnDefs: [{
                        targets: -1,
                        orderable: false
                    }]
                });

                setTimeout(function() {
                    dt.columns.adjust();
                }, 0);

                let resizeTimer;
                $(window).on('resize.patientHoaDonTable', function() {
                    clearTimeout(resizeTimer);
                    resizeTimer = setTimeout(function() {
                        dt.columns.adjust();
                    }, 150);
                });
            });
        </script>
    @endpush
@endsection
