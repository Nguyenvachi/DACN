@extends('layouts.staff')

@section('title', 'Đơn thuốc chờ cấp')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-dark">
                <i class="fas fa-prescription-bottle-alt me-2 text-warning"></i>
                Đơn thuốc chờ cấp
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Đơn thuốc chờ cấp</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('staff.donthuoc.completed') }}" class="btn btn-outline-success">
                <i class="fas fa-check-circle me-2"></i>Đã cấp
            </a>
        </div>
    </div>

    {{-- Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="80">Mã</th>
                            <th>Ngày kê</th>
                            <th>Bệnh nhân</th>
                            <th>Bác sĩ</th>
                            <th width="140">Trạng thái</th>
                            <th width="160" class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($donThuocs as $donThuoc)
                            <tr>
                                <td><strong class="text-primary">#{{ $donThuoc->id }}</strong></td>
                                <td><small>{{ $donThuoc->created_at?->format('d/m/Y H:i') }}</small></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-light rounded-circle me-2 d-flex align-items-center justify-content-center">
                                            <i class="fas fa-user text-muted"></i>
                                        </div>
                                        <div>
                                            <div class="fw-medium">{{ $donThuoc->benhAn->user->name ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $donThuoc->benhAn->user->so_dien_thoai ?? '' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td><small>{{ $donThuoc->benhAn->bacSi->user->name ?? 'N/A' }}</small></td>
                                <td>
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-hourglass-half me-1"></i>Chờ cấp
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('staff.donthuoc.show', $donThuoc) }}" class="btn btn-sm btn-outline-secondary" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="fas fa-check-circle fa-3x text-success mb-3 d-block"></i>
                                    <h5 class="text-muted">Không có đơn thuốc chờ cấp</h5>
                                    <p class="text-muted mb-0">Tất cả đơn thuốc đã được cấp</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $donThuocs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.avatar-sm { width: 35px; height: 35px; }
</style>
@endpush
