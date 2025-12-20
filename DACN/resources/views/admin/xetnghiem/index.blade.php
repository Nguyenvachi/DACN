@extends('layouts.admin')

@section('title', 'Quản lý Xét nghiệm')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-dark">
                <i class="fas fa-flask me-2 text-primary"></i>
                Quản lý Xét nghiệm
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Xét nghiệm</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.xetnghiem.statistics') }}" class="btn btn-outline-primary">
                <i class="fas fa-chart-bar me-2"></i>Thống kê
            </a>
            <a href="{{ route('admin.xetnghiem.export') }}" class="btn btn-success">
                <i class="fas fa-file-excel me-2"></i>Xuất Excel
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Tổng xét nghiệm</p>
                            <h3 class="mb-0 fw-bold text-primary">{{ number_format($stats['total']) }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded p-3">
                            <i class="fas fa-vials fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Chờ thực hiện</p>
                            <h3 class="mb-0 fw-bold text-warning">{{ number_format($stats['pending']) }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded p-3">
                            <i class="fas fa-hourglass-start fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Đã hoàn thành</p>
                            <h3 class="mb-0 fw-bold text-success">{{ number_format($stats['completed']) }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded p-3">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Hôm nay</p>
                            <h3 class="mb-0 fw-bold text-info">{{ number_format($stats['today']) }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 rounded p-3">
                            <i class="fas fa-calendar-day fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.xetnghiem.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Tìm kiếm bệnh nhân</label>
                        <input type="text" name="search" class="form-control" placeholder="Nhập tên..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted">Trạng thái</label>
                        <select name="trang_thai" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="pending" {{ request('trang_thai') == 'pending' ? 'selected' : '' }}>Chờ thực hiện</option>
                            <option value="processing" {{ request('trang_thai') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                            <option value="completed" {{ request('trang_thai') == 'completed' ? 'selected' : '' }}>Đã hoàn thành</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted">Bác sĩ</label>
                        <select name="bac_si_id" class="form-select">
                            <option value="">Tất cả</option>
                            @foreach($bacSis as $bs)
                                <option value="{{ $bs->id }}" {{ request('bac_si_id') == $bs->id ? 'selected' : '' }}>
                                    {{ $bs->user->name ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted">Từ ngày</label>
                        <input type="date" name="tu_ngay" class="form-control" value="{{ request('tu_ngay') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted">Đến ngày</label>
                        <input type="date" name="den_ngay" class="form-control" value="{{ request('den_ngay') }}">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table id="xetnghiemTable" class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="80">Mã XN</th>
                            <th>Ngày chỉ định</th>
                            <th>Bệnh nhân</th>
                            <th>Bác sĩ chỉ định</th>
                            <th>Loại xét nghiệm</th>
                            <th>Trạng thái</th>
                            <th width="120" class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($xetNghiems as $xn)
                        <tr>
                            <td>
                                <strong class="text-primary">#{{ $xn->id }}</strong>
                            </td>
                            <td>
                                <small>{{ $xn->created_at->format('d/m/Y H:i') }}</small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-light rounded-circle me-2 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-user text-muted"></i>
                                    </div>
                                    <div>
                                        <div class="fw-medium">{{ $xn->benhAn->user->name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $xn->benhAn->user->email ?? '' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                {{ $xn->bacSi->user->name ?? 'N/A' }}
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $xn->loai }}</span>
                            </td>
                            <td>
                                @if($xn->trang_thai === 'pending')
                                    <span class="badge bg-warning">
                                        <i class="fas fa-hourglass-start me-1"></i>Chờ thực hiện
                                    </span>
                                @elseif($xn->trang_thai === 'processing')
                                    <span class="badge bg-info">
                                        <i class="fas fa-spinner me-1"></i>Đang xử lý
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>Đã hoàn thành
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.xetnghiem.show', $xn) }}" class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                <p class="text-muted">Không có dữ liệu xét nghiệm</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $xetNghiems->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

<x-datatable-script
    tableId="xetnghiemTable"
    config='{"paging": false, "info": false, "searching": false, "lengthChange": false}'
/>

@push('styles')
<style>
.avatar-sm {
    width: 35px;
    height: 35px;
}
</style>
@endpush
