@extends('layouts.staff')

@section('title', 'Siêu âm đã hoàn thành')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">
                <i class="fas fa-check-circle me-2 text-success"></i>
                Siêu âm đã hoàn thành
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Siêu âm hoàn thành</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('staff.sieuam.pending') }}" class="btn btn-warning">
            <i class="fas fa-clock me-1"></i> Chờ thực hiện
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- THÊM: Stats (đồng bộ module Xét nghiệm, nhưng nội dung theo Siêu âm) --}}
    @if(isset($stats))
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-success bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-success fw-bold mb-1 small">HOÀN THÀNH HÔM NAY</p>
                            <h3 class="mb-0 fw-bold">{{ number_format($stats['today'] ?? 0) }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-25 rounded p-3">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-info bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-info fw-bold mb-1 small">TUẦN NÀY</p>
                            <h3 class="mb-0 fw-bold">{{ number_format($stats['this_week'] ?? 0) }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-25 rounded p-3">
                            <i class="fas fa-calendar-week fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-warning bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-warning fw-bold mb-1 small">THÁNG NÀY</p>
                            <h3 class="mb-0 fw-bold">{{ number_format($stats['this_month'] ?? 0) }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-25 rounded p-3">
                            <i class="fas fa-calendar-alt fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Search --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control"
                           placeholder="Tìm theo tên bệnh nhân..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i> Tìm
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4">Mã SA</th>
                            <th>Bệnh nhân</th>
                            <th>Loại siêu âm</th>
                            <th>Bác sĩ chỉ định</th>
                            <th>Ngày hoàn thành</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sieuAms as $sa)
                            <tr>
                                <td class="px-4">
                                    <span class="badge bg-gradient-success">SA-{{ $sa->id }}</span>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $sa->benhAn->user->name ?? 'N/A' }}</strong>
                                        <div class="small text-muted">BA-{{ $sa->benh_an_id }}</div>
                                    </div>
                                </td>
                                <td>{{ $sa->loai }}</td>
                                <td>
                                    <small>{{ $sa->bacSi->ho_ten ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    <small>{{ $sa->updated_at->format('d/m/Y H:i') }}</small>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('staff.sieuam.show', $sa->id) }}"
                                       class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i> Chi tiết
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block opacity-25"></i>
                                    Chưa có siêu âm nào hoàn thành
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($sieuAms->hasPages())
            <div class="card-footer bg-white">
                {{ $sieuAms->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
