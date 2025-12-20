@extends('layouts.staff')

@section('title', 'Chi tiết đơn thuốc')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-dark">
                <i class="fas fa-prescription-bottle-alt me-2 text-primary"></i>
                Đơn thuốc #{{ $donThuoc->id }}
            </h4>
            <div class="text-muted small">
                Bệnh nhân: <strong>{{ $donThuoc->benhAn->user->name ?? 'N/A' }}</strong>
                — Bác sĩ: <strong>{{ $donThuoc->benhAn->bacSi->user->name ?? 'N/A' }}</strong>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('staff.donthuoc.pending') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('status'))
        <div class="alert alert-info alert-dismissible fade show">
            {{ session('status') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Status card --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <div class="text-muted small mb-1">Trạng thái</div>
                    @if ($donThuoc->trang_thai === 'da_cap_thuoc')
                        <span class="badge bg-success fs-6"><i class="fas fa-check-circle me-1"></i>Đã cấp thuốc</span>
                        <div class="text-muted small mt-2">
                            Ngày cấp: <strong>{{ $donThuoc->ngay_cap_thuoc?->format('d/m/Y H:i') ?? '—' }}</strong>
                            — Người cấp: <strong>{{ $donThuoc->nguoiCapThuoc->name ?? '—' }}</strong>
                        </div>
                        @if ($donThuoc->ghi_chu_cap_thuoc)
                            <div class="mt-2"><strong>Ghi chú cấp thuốc:</strong> {{ $donThuoc->ghi_chu_cap_thuoc }}</div>
                        @endif
                    @else
                        <span class="badge bg-warning text-dark fs-6"><i class="fas fa-hourglass-half me-1"></i>Chờ cấp</span>
                    @endif
                </div>

                @if ($donThuoc->trang_thai !== 'da_cap_thuoc')
                    <div>
                        <a class="btn btn-success" href="#dispenseForm">
                            <i class="fas fa-hand-holding-medical me-2"></i>Xác nhận cấp thuốc
                        </a>
                    </div>
                @endif
            </div>

            @if ($donThuoc->trang_thai !== 'da_cap_thuoc')
                <div class="mt-3" id="dispenseForm">
                    <form method="POST" action="{{ route('staff.donthuoc.dispense', $donThuoc) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Ghi chú (tùy chọn)</label>
                            <textarea name="ghi_chu_cap_thuoc" class="form-control" rows="3" placeholder="VD: Đã đối chiếu thuốc, hướng dẫn bệnh nhân..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-success" onclick="return confirm('Xác nhận đã cấp thuốc cho đơn #{{ $donThuoc->id }}?')">
                            Xác nhận
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    {{-- Items --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <h5 class="fw-semibold mb-0"><i class="fas fa-pills me-2 text-primary"></i>Danh sách thuốc</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Thuốc</th>
                            <th width="140" class="text-center">Số lượng</th>
                            <th>Liều dùng</th>
                            <th>Cách dùng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($donThuoc->items as $item)
                            <tr>
                                <td>
                                    <div class="fw-medium">{{ $item->thuoc->ten ?? '—' }}</div>
                                    <small class="text-muted">{{ $item->thuoc->hoat_chat ?? '' }}</small>
                                </td>
                                <td class="text-center"><span class="badge bg-light text-dark">{{ $item->so_luong }}</span></td>
                                <td>{{ $item->lieu_dung ?? '—' }}</td>
                                <td>{{ $item->cach_dung ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Đơn thuốc chưa có thuốc.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($donThuoc->ghi_chu)
                <div class="mt-3">
                    <div class="text-muted small mb-1">Ghi chú bác sĩ</div>
                    <div class="p-3 bg-light rounded">{{ $donThuoc->ghi_chu }}</div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
