@extends('layouts.staff')

@section('title', 'Đơn thuốc đã cấp')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-dark">
                <i class="fas fa-check-circle me-2 text-success"></i>
                Đơn thuốc đã cấp
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Đơn thuốc đã cấp</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('staff.donthuoc.pending') }}" class="btn btn-outline-warning">
                <i class="fas fa-hourglass-half me-2"></i>Chờ cấp
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
                            <th>Bệnh nhân</th>
                            <th>Ngày kê</th>
                            <th>Ngày cấp</th>
                            <th>Người cấp</th>
                            <th width="160" class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($donThuocs as $donThuoc)
                            <tr>
                                <td><strong class="text-primary">#{{ $donThuoc->id }}</strong></td>
                                <td>{{ $donThuoc->benhAn->user->name ?? 'N/A' }}</td>
                                <td><small>{{ $donThuoc->created_at?->format('d/m/Y H:i') }}</small></td>
                                <td><small>{{ $donThuoc->ngay_cap_thuoc?->format('d/m/Y H:i') ?? '—' }}</small></td>
                                <td>{{ $donThuoc->nguoiCapThuoc->name ?? '—' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('staff.donthuoc.show', $donThuoc) }}" class="btn btn-sm btn-outline-secondary" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                    <h5 class="text-muted">Chưa có đơn thuốc đã cấp</h5>
                                    <p class="text-muted mb-0">Khi bạn cấp thuốc, đơn sẽ xuất hiện ở đây</p>
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
