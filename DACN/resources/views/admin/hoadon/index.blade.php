@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">

        {{-- ============================
         🔥 HEADER
    ============================= --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="fas fa-file-invoice-dollar me-2"></i>Quản lý Hóa đơn
            </h2>
            <a href="{{ route('admin.hoadon.all-refunds') }}" class="btn btn-warning">
                <i class="fas fa-undo me-1"></i> Quản lý hoàn tiền
            </a>
        </div>

        {{-- ============================
         🔥 ALERT MESSAGE
    ============================= --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif


        {{-- ============================
         🔥 TABLE LIST
    ============================= --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0">
                <h5 class="fw-semibold mb-0">
                    <i class="fas fa-list me-1"></i>Danh sách hóa đơn
                </h5>
            </div>

            <div class="card-body">

                <div class="table-responsive">
                    <table id="hoadonTable" class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="10%">Lịch hẹn</th>
                                <th width="20%">Bệnh nhân</th>
                                <th width="15%">Tổng tiền</th>
                                <th width="15%">Trạng thái</th>
                                <th width="15%">Thanh toán</th>
                                <th width="10%">Hành động</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($items as $it)
                                <tr>
                                    <td>{{ $it->id }}</td>

                                    <td>
                                        <span class="badge bg-primary">#{{ $it->lich_hen_id }}</span>
                                    </td>

                                    <td>
                                        {{ optional($it->user)->name ?? '#' . $it->user_id }}
                                    </td>

                                    <td class="fw-bold text-success">
                                        {{ number_format($it->tong_tien, 0, ',', '.') }} đ
                                    </td>

                                    <td>
                                        @php
                                            $statusColor = match ($it->trang_thai) {
                                                'Đã thanh toán' => 'success',
                                                'Chưa thanh toán' => 'warning',
                                                'Hủy' => 'danger',
                                                default => 'secondary',
                                            };
                                        @endphp

                                        <span class="badge bg-{{ $statusColor }}">
                                            {{ $it->trang_thai }}
                                        </span>
                                    </td>

                                    <td>
                                        @if ($it->phuong_thuc)
                                            <span class="badge bg-info">
                                                {{ strtoupper($it->phuong_thuc) }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>

                                    <td>
                                        <a href="{{ route('admin.hoadon.show', $it) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Xem
                                        </a>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                        <p class="mb-0 text-muted">Chưa có hóa đơn nào</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
@endsection

{{-- DataTables Script --}}
<x-datatable-script tableId="hoadonTable" />
