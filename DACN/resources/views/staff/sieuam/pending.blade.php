@extends('layouts.staff')

@section('title', 'Siêu âm chờ thực hiện')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">
                <i class="fas fa-ultrasound me-2 text-warning"></i>
                Siêu âm chờ thực hiện
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Siêu âm chờ</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('staff.sieuam.completed') }}" class="btn btn-success">
            <i class="fas fa-check-circle me-1"></i> Đã hoàn thành
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
            <div class="card border-0 shadow-sm bg-warning bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-warning fw-bold mb-1 small">CHỜ THỰC HIỆN</p>
                            <h3 class="mb-0 fw-bold">{{ number_format($stats['pending'] ?? 0) }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-25 rounded p-3">
                            <i class="fas fa-hourglass-start fa-2x text-warning"></i>
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
                            <p class="text-info fw-bold mb-1 small">ĐANG XỬ LÝ</p>
                            <h3 class="mb-0 fw-bold">{{ number_format($stats['processing'] ?? 0) }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-25 rounded p-3">
                            <i class="fas fa-spinner fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-success bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-success fw-bold mb-1 small">HOÀN THÀNH HÔM NAY</p>
                            <h3 class="mb-0 fw-bold">{{ number_format($stats['completed_today'] ?? 0) }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-25 rounded p-3">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
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
                            <th>Trạng thái</th>
                            <th>Ngày chỉ định</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sieuAms as $sa)
                            <tr>
                                <td class="px-4">
                                    <span class="badge bg-gradient-primary">SA-{{ $sa->id }}</span>
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
                                    <span class="badge bg-{{ $sa->trang_thai_badge_class }}">
                                        {{ $sa->trang_thai_text }}
                                    </span>
                                </td>
                                <td>
                                    <small>{{ $sa->created_at->format('d/m/Y H:i') }}</small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('staff.sieuam.show', $sa->id) }}"
                                           class="btn btn-outline-info" title="Chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($sa->trang_thai === 'pending')
                                            <form action="{{ route('staff.sieuam.processing', $sa->id) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-primary"
                                                        title="Bắt đầu xử lý">
                                                    <i class="fas fa-play"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('staff.sieuam.upload.form', $sa->id) }}"
                                           class="btn btn-outline-success" title="Upload kết quả">
                                            <i class="fas fa-upload"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block opacity-25"></i>
                                    Không có siêu âm nào đang chờ
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
