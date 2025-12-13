@extends('layouts.staff')

@section('title', 'Quản Lý Đơn Thuốc')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-prescription2 text-primary me-2"></i>
                Quản Lý Đơn Thuốc
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Đơn thuốc</li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- Quick Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Đang chờ cấp thuốc
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ \App\Models\DonThuoc::whereNull('ngay_cap_thuoc')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-hourglass-split fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Đã cấp hôm nay
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ \App\Models\DonThuoc::whereDate('ngay_cap_thuoc', today())->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Tổng đơn thuốc
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ \App\Models\DonThuoc::count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-clipboard-data fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <ul class="nav nav-tabs mb-3" id="donThuocTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" href="{{ route('staff.donthuoc.index') }}">
                <i class="bi bi-list-ul me-1"></i>Tất cả
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" href="{{ route('staff.donthuoc.dang-cho') }}">
                <i class="bi bi-hourglass-split me-1"></i>Đang chờ
                @php $dangCho = \App\Models\DonThuoc::whereNull('ngay_cap_thuoc')->count(); @endphp
                @if($dangCho > 0)
                    <span class="badge bg-warning text-dark">{{ $dangCho }}</span>
                @endif
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" href="{{ route('staff.donthuoc.da-cap') }}">
                <i class="bi bi-check-circle me-1"></i>Đã cấp
            </a>
        </li>
    </ul>

    {{-- Search & Filter --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('staff.donthuoc.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Tìm kiếm</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Tên bệnh nhân, SĐT..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Từ ngày</label>
                        <input type="date" name="tu_ngay" class="form-control" 
                               value="{{ request('tu_ngay') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Đến ngày</label>
                        <input type="date" name="den_ngay" class="form-control" 
                               value="{{ request('den_ngay') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Trạng thái</label>
                        <select name="trang_thai" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="da_kham" {{ request('trang_thai') == 'da_kham' ? 'selected' : '' }}>Chờ cấp</option>
                            <option value="da_cap_thuoc" {{ request('trang_thai') == 'da_cap_thuoc' ? 'selected' : '' }}>Đã cấp</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-1"></i>Tìm kiếm
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

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
                            <th width="10%">Ngày kê</th>
                            <th width="10%">SL thuốc</th>
                            <th width="15%">Trạng thái</th>
                            <th width="12%">Ngày cấp</th>
                            <th width="10%" class="text-center">Thao tác</th>
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
                            <td>{{ $dt->benhAn->bacSi->hoten ?? 'N/A' }}</td>
                            <td>{{ $dt->created_at->format('d/m/Y') }}</td>
                            <td><span class="badge bg-info">{{ $dt->items->count() }} loại</span></td>
                            <td>
                                @if($dt->ngay_cap_thuoc)
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>Đã cấp
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-hourglass-split me-1"></i>Chờ cấp
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($dt->ngay_cap_thuoc)
                                    {{ $dt->ngay_cap_thuoc->format('d/m/Y H:i') }}
                                @else
                                    <span class="text-muted">Chưa cấp</span>
                                @endif
                            </td>
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
                <p class="text-muted mt-3">Không có đơn thuốc nào.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
