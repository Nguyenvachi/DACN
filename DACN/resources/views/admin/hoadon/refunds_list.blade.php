@extends('layouts.admin')

@section('title', 'Lịch sử hoàn tiền - Hóa đơn #' . $hoaDon->ma_hoa_don)

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.hoadon.index') }}">Hóa đơn</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.hoadon.show', $hoaDon) }}">{{ $hoaDon->ma_hoa_don }}</a></li>
            <li class="breadcrumb-item active">Lịch sử hoàn tiền</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                <i class="fas fa-history text-info me-2"></i>
                Lịch sử hoàn tiền
            </h2>
            <p class="text-muted mb-0">Hóa đơn: <strong>{{ $hoaDon->ma_hoa_don }}</strong></p>
        </div>
        <a href="{{ route('admin.hoadon.show', $hoaDon) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <!-- Thông tin tổng quan -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <div class="text-muted small mb-1">Tổng tiền hóa đơn</div>
                    <h4 class="mb-0 text-primary">{{ number_format($hoaDon->tong_tien, 0, ',', '.') }} đ</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body text-center">
                    <div class="text-muted small mb-1">Đã thanh toán</div>
                    <h4 class="mb-0 text-success">{{ number_format($hoaDon->so_tien_da_thanh_toan, 0, ',', '.') }} đ</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <div class="text-muted small mb-1">Đã hoàn</div>
                    <h4 class="mb-0 text-warning">{{ number_format($hoaDon->so_tien_da_hoan, 0, ',', '.') }} đ</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-body text-center">
                    <div class="text-muted small mb-1">Tiền thực</div>
                    <h4 class="mb-0 text-info">
                        {{ number_format($hoaDon->so_tien_da_thanh_toan - $hoaDon->so_tien_da_hoan, 0, ',', '.') }} đ
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Danh sách hoàn tiền -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>
                Danh sách các lần hoàn tiền
                <span class="badge bg-info ms-2">{{ $hoanTiens->count() }} giao dịch</span>
            </h5>
        </div>
        <div class="card-body p-0">
            @if($hoanTiens->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">Chưa có giao dịch hoàn tiền nào</p>
                </div>
            @else
                <div class="table-responsive">
                    <table id="refundsTable" class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%">#</th>
                                <th style="width: 15%">Thời gian</th>
                                <th style="width: 12%">Số tiền</th>
                                <th style="width: 25%">Lý do</th>
                                <th style="width: 13%">Phương thức</th>
                                <th style="width: 10%">Trạng thái</th>
                                <th style="width: 15%">Mã tham chiếu</th>
                                <th style="width: 5%">Chi tiết</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hoanTiens as $index => $hoanTien)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="small">
                                            <div><i class="far fa-calendar me-1"></i>{{ $hoanTien->created_at->format('d/m/Y') }}</div>
                                            <div class="text-muted"><i class="far fa-clock me-1"></i>{{ $hoanTien->created_at->format('H:i:s') }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong class="text-warning">{{ number_format($hoanTien->so_tien, 0, ',', '.') }} đ</strong>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 250px;" title="{{ $hoanTien->ly_do }}">
                                            {{ $hoanTien->ly_do }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($hoanTien->provider === 'tien_mat')
                                            <span class="badge bg-success">
                                                <i class="fas fa-money-bill-wave me-1"></i>Tiền mặt
                                            </span>
                                        @elseif($hoanTien->provider === 'chuyen_khoan')
                                            <span class="badge bg-primary">
                                                <i class="fas fa-university me-1"></i>Chuyển khoản
                                            </span>
                                        @elseif($hoanTien->provider === 'hoan_cong')
                                            <span class="badge bg-info">
                                                <i class="fas fa-sync-alt me-1"></i>Hoàn về cổng
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">{{ $hoanTien->provider }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($hoanTien->trang_thai === 'Hoàn thành')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>Hoàn thành
                                            </span>
                                        @elseif($hoanTien->trang_thai === 'Đang xử lý')
                                            <span class="badge bg-warning">
                                                <i class="fas fa-spinner me-1"></i>Đang xử lý
                                            </span>
                                        @elseif($hoanTien->trang_thai === 'Thất bại')
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times-circle me-1"></i>Thất bại
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">{{ $hoanTien->trang_thai }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($hoanTien->provider_ref)
                                            <code class="small">{{ $hoanTien->provider_ref }}</code>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-info"
                                                data-bs-toggle="modal"
                                                data-bs-target="#detailModal{{ $hoanTien->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="2" class="text-end"><strong>Tổng cộng:</strong></td>
                                <td colspan="6">
                                    <strong class="text-warning">
                                        {{ number_format($hoanTiens->sum('so_tien'), 0, ',', '.') }} đ
                                    </strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Timeline -->
    @if($hoanTiens->isNotEmpty())
        <div class="card mt-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-stream me-2"></i>
                    Timeline hoàn tiền
                </h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @foreach($hoanTiens as $hoanTien)
                        <div class="timeline-item mb-3 pb-3 border-bottom">
                            <div class="row">
                                <div class="col-auto">
                                    <div class="avatar avatar-sm rounded-circle
                                        @if($hoanTien->trang_thai === 'Hoàn thành') bg-success
                                        @elseif($hoanTien->trang_thai === 'Đang xử lý') bg-warning
                                        @else bg-danger
                                        @endif
                                        text-white d-flex align-items-center justify-content-center"
                                        style="width: 40px; height: 40px;">
                                        <i class="fas fa-undo-alt"></i>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="mb-1">
                                                Hoàn tiền <strong class="text-warning">{{ number_format($hoanTien->so_tien, 0, ',', '.') }} đ</strong>
                                            </h6>
                                            <p class="text-muted small mb-1">{{ $hoanTien->ly_do }}</p>
                                            <div class="small text-muted">
                                                <i class="far fa-calendar-alt me-1"></i>
                                                {{ $hoanTien->created_at->format('d/m/Y H:i') }}
                                                @if($hoanTien->provider_ref)
                                                    • Mã: <code>{{ $hoanTien->provider_ref }}</code>
                                                @endif
                                            </div>
                                        </div>
                                        <div>
                                            @if($hoanTien->trang_thai === 'Hoàn thành')
                                                <span class="badge bg-success">Hoàn thành</span>
                                            @elseif($hoanTien->trang_thai === 'Đang xử lý')
                                                <span class="badge bg-warning">Đang xử lý</span>
                                            @else
                                                <span class="badge bg-danger">Thất bại</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Modals chi tiết -->
@foreach($hoanTiens as $hoanTien)
<div class="modal fade" id="detailModal{{ $hoanTien->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chi tiết giao dịch hoàn tiền</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">Mã hóa đơn:</th>
                        <td><strong>{{ $hoaDon->ma_hoa_don }}</strong></td>
                    </tr>
                    <tr>
                        <th>Số tiền hoàn:</th>
                        <td><strong class="text-warning">{{ number_format($hoanTien->so_tien, 0, ',', '.') }} đ</strong></td>
                    </tr>
                    <tr>
                        <th>Lý do:</th>
                        <td>{{ $hoanTien->ly_do }}</td>
                    </tr>
                    <tr>
                        <th>Phương thức:</th>
                        <td>
                            @if($hoanTien->provider === 'tien_mat')
                                Tiền mặt
                            @elseif($hoanTien->provider === 'chuyen_khoan')
                                Chuyển khoản
                            @elseif($hoanTien->provider === 'hoan_cong')
                                Hoàn về cổng thanh toán gốc
                            @else
                                {{ $hoanTien->provider }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Trạng thái:</th>
                        <td>
                            @if($hoanTien->trang_thai === 'Hoàn thành')
                                <span class="badge bg-success">Hoàn thành</span>
                            @elseif($hoanTien->trang_thai === 'Đang xử lý')
                                <span class="badge bg-warning">Đang xử lý</span>
                            @else
                                <span class="badge bg-danger">Thất bại</span>
                            @endif
                        </td>
                    </tr>
                    @if($hoanTien->provider_ref)
                    <tr>
                        <th>Mã tham chiếu:</th>
                        <td><code>{{ $hoanTien->provider_ref }}</code></td>
                    </tr>
                    @endif
                    <tr>
                        <th>Thời gian tạo:</th>
                        <td>{{ $hoanTien->created_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>Cập nhật lần cuối:</th>
                        <td>{{ $hoanTien->updated_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<style>
.timeline {
    position: relative;
}
.timeline-item:last-child {
    border-bottom: none !important;
}
.avatar {
    font-size: 16px;
}
</style>
@endsection

{{-- DataTables Script --}}
<x-datatable-script tableId="refundsTable" />
