@extends('layouts.staff')

@section('title', 'Đơn Thuốc Đã Cấp')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-check-circle text-success me-2"></i>
                Đơn Thuốc Đã Cấp
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('staff.donthuoc.index') }}">Đơn thuốc</a></li>
                    <li class="breadcrumb-item active">Đã cấp</li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- Tabs --}}
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('staff.donthuoc.index') }}">
                <i class="bi bi-list-ul me-1"></i>Tất cả
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('staff.donthuoc.dang-cho') }}">
                <i class="bi bi-hourglass-split me-1"></i>Đang chờ
                @php $dangCho = \App\Models\DonThuoc::whereNull('ngay_cap_thuoc')->count(); @endphp
                @if($dangCho > 0)
                    <span class="badge bg-warning text-dark">{{ $dangCho }}</span>
                @endif
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('staff.donthuoc.da-cap') }}">
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
                            <th width="18%">Bệnh nhân</th>
                            <th width="13%">Bác sĩ</th>
                            <th width="12%">Ngày kê</th>
                            <th width="10%">SL thuốc</th>
                            <th width="12%">Ngày cấp</th>
                            <th width="15%">Người cấp</th>
                            <th width="12%" class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($donThuocs as $dt)
                        <tr>
                            <td><strong>#{{ str_pad($dt->id, 4, '0', STR_PAD_LEFT) }}</strong></td>
                            <td>
                                <div>{{ $dt->benhAn->user->name ?? 'N/A' }}</div>
                                <small class="text-muted">{{ $dt->benhAn->user->so_dien_thoai ?? '' }}</small>
                            </td>
                            <td>BS. {{ $dt->benhAn->bacSi->hoten ?? 'N/A' }}</td>
                            <td>{{ $dt->created_at->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-info">{{ $dt->items->count() }} loại</span>
                            </td>
                            <td>
                                <div>{{ $dt->ngay_cap_thuoc->format('d/m/Y') }}</div>
                                <small class="text-muted">{{ $dt->ngay_cap_thuoc->format('H:i') }}</small>
                            </td>
                            <td>{{ $dt->nguoiCapThuoc->name ?? 'N/A' }}</td>
                            <td class="text-center">
                                <a href="{{ route('staff.donthuoc.show', $dt) }}" 
                                   class="btn btn-sm btn-primary"
                                   title="Xem chi tiết">
                                    <i class="bi bi-eye"></i>
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
                <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3">Chưa có đơn thuốc nào được cấp.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
