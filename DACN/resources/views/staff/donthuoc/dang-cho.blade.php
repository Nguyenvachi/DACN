@extends('layouts.staff')

@section('title', 'Đơn Thuốc Chờ Cấp')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-hourglass-split text-warning me-2"></i>
                Đơn Thuốc Chờ Cấp
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('staff.donthuoc.index') }}">Đơn thuốc</a></li>
                    <li class="breadcrumb-item active">Chờ cấp</li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- Alert --}}
    @if($donThuocs->count() > 0)
    <div class="alert alert-warning alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong>Có {{ $donThuocs->total() }} đơn thuốc đang chờ cấp.</strong> Vui lòng xử lý kịp thời.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Tabs --}}
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('staff.donthuoc.index') }}">
                <i class="bi bi-list-ul me-1"></i>Tất cả
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('staff.donthuoc.dang-cho') }}">
                <i class="bi bi-hourglass-split me-1"></i>Đang chờ
                <span class="badge bg-warning text-dark">{{ $donThuocs->total() }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('staff.donthuoc.da-cap') }}">
                <i class="bi bi-check-circle me-1"></i>Đã cấp
            </a>
        </li>
    </ul>

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-body">
            @if($donThuocs->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="8%">Mã ĐT</th>
                            <th width="20%">Bệnh nhân</th>
                            <th width="15%">Bác sĩ</th>
                            <th width="12%">Ngày kê</th>
                            <th width="10%">SL thuốc</th>
                            <th width="15%">Thời gian chờ</th>
                            <th width="10%" class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($donThuocs as $dt)
                        <tr>
                            <td><strong>DT-{{ str_pad($dt->id, 4, '0', STR_PAD_LEFT) }}</strong></td>
                            <td>
                                <div><strong>{{ $dt->benhAn->user->name ?? 'N/A' }}</strong></div>
                                <small class="text-muted">{{ $dt->benhAn->user->so_dien_thoai ?? '' }}</small>
                            </td>
                            <td>BS. {{ $dt->benhAn->bacSi->ho_ten ?? 'N/A' }}</td>
                            <td>{{ $dt->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="badge bg-info">{{ $dt->items->count() }} loại</span>
                                <br>
                                <small class="text-muted">{{ $dt->items->sum('so_luong') }} tổng</small>
                            </td>
                            <td>
                                @php
                                    $minutes = $dt->created_at->diffInMinutes(now());
                                    $hours = floor($minutes / 60);
                                    $mins = $minutes % 60;
                                @endphp
                                @if($hours > 0)
                                    <span class="badge bg-warning text-dark">{{ $hours }}h {{ $mins }}p</span>
                                @else
                                    <span class="badge bg-warning text-dark">{{ $mins }} phút</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('staff.donthuoc.show', $dt) }}" 
                                   class="btn btn-sm btn-success"
                                   title="Cấp thuốc">
                                    <i class="bi bi-check-circle"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $donThuocs->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3">Tuyệt vời! Không có đơn thuốc nào đang chờ cấp.</p>
                <a href="{{ route('staff.donthuoc.index') }}" class="btn btn-primary">
                    <i class="bi bi-list-ul me-1"></i>Xem tất cả đơn thuốc
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
