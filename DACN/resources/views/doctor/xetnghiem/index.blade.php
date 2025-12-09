@extends('layouts.doctor')

@section('title', 'Danh sách xét nghiệm')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: #1f2937;">
                <i class="fas fa-flask me-2" style="color: #8b5cf6;"></i>
                Danh sách xét nghiệm
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Xét nghiệm</li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="vc-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Tổng xét nghiệm</p>
                            <h3 class="fw-bold mb-0" style="color: #8b5cf6;">{{ $xetNghiems->total() }}</h3>
                        </div>
                        <div class="rounded p-3" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                            <i class="fas fa-vials fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="vc-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Chờ kết quả</p>
                            <h3 class="fw-bold mb-0" style="color: #f59e0b;">{{ $xetNghiems->whereIn('trang_thai', ['pending', 'processing'])->count() }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded p-3">
                            <i class="fas fa-hourglass-half fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="vc-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Đã có kết quả</p>
                            <h3 class="fw-bold mb-0" style="color: #10b981;">{{ $xetNghiems->where('trang_thai', 'completed')->count() }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded p-3">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Danh sách xét nghiệm --}}
    <div class="card vc-card">
        <div class="card-body">
            @if($xetNghiems->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-flask fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Chưa có xét nghiệm nào được chỉ định</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Mã XN</th>
                                <th>Ngày chỉ định</th>
                                <th>Bệnh nhân</th>
                                <th>Loại xét nghiệm</th>
                                <th>Trạng thái</th>
                                <th class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($xetNghiems as $xn)
                            <tr>
                                <td>
                                    <strong class="text-primary">#XN{{ $xn->id }}</strong>
                                </td>
                                <td>
                                    <small>{{ $xn->created_at->format('d/m/Y H:i') }}</small>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $xn->benhAn->user->name ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">BA #{{ $xn->benh_an_id }}</small>
                                    </div>
                                </td>
                                <td>{{ $xn->loai }}</td>
                                <td>
                                    @if($xn->trang_thai === 'pending')
                                        <span class="badge bg-warning">
                                            <i class="fas fa-hourglass-half me-1"></i>Chờ thực hiện
                                        </span>
                                    @elseif($xn->trang_thai === 'processing')
                                        <span class="badge bg-info">
                                            <i class="fas fa-spinner me-1"></i>Đang xử lý
                                        </span>
                                    @elseif($xn->trang_thai === 'completed')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>Đã có kết quả
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">{{ $xn->trang_thai_text }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('doctor.xetnghiem.show', $xn->id) }}"
                                           class="btn btn-outline-primary"
                                           title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($xn->trang_thai === 'completed' && $xn->file_path)
                                        <a href="{{ route('doctor.xetnghiem.download', $xn->id) }}"
                                           class="btn btn-outline-success"
                                           title="Tải kết quả"
                                           target="_blank">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-end mt-3">
                    {{ $xetNghiems->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table th {
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6b7280;
    }
</style>
@endpush
