@extends('layouts.staff')

@section('title', 'X-Quang đã hoàn thành')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-dark">
                <i class="fas fa-check-circle me-2 text-success"></i>
                X-Quang đã hoàn thành
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">X-Quang hoàn thành</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('staff.xquang.pending') }}" class="btn btn-outline-warning">
                <i class="fas fa-hourglass-start me-2"></i>Danh sách chờ
            </a>
        </div>
    </div>

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-success bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-success fw-bold mb-1 small">HÔM NAY</p>
                            <h2 class="mb-0 fw-bold">{{ number_format($stats['today']) }}</h2>
                        </div>
                        <div><i class="fas fa-calendar-day fa-3x text-success opacity-50"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-primary bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-primary fw-bold mb-1 small">TUẦN NÀY</p>
                            <h2 class="mb-0 fw-bold">{{ number_format($stats['this_week']) }}</h2>
                        </div>
                        <div><i class="fas fa-calendar-week fa-3x text-primary opacity-50"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-info bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-info fw-bold mb-1 small">THÁNG NÀY</p>
                            <h2 class="mb-0 fw-bold">{{ number_format($stats['this_month']) }}</h2>
                        </div>
                        <div><i class="fas fa-calendar-alt fa-3x text-info opacity-50"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('staff.xquang.completed') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small">Tìm kiếm</label>
                    <input type="text" name="search" class="form-control" placeholder="#id, loại, tên BN..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Bác sĩ</label>
                    <select name="bac_si_id" class="form-select">
                        <option value="">-- Tất cả --</option>
                        @foreach($bacSis as $bs)
                            <option value="{{ $bs->id }}" {{ (string) request('bac_si_id') === (string) $bs->id ? 'selected' : '' }}>
                                {{ $bs->user->name ?? $bs->ho_ten ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Từ ngày</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Đến ngày</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-fill"><i class="fas fa-filter"></i> Lọc</button>
                    <a href="{{ route('staff.xquang.completed') }}" class="btn btn-outline-secondary"><i class="fas fa-redo"></i></a>
                </div>
            </form>
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
                            <th>Ngày hoàn thành</th>
                            <th>Bệnh nhân</th>
                            <th>Bác sĩ</th>
                            <th>Loại</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($xQuangs as $xq)
                            <tr>
                                <td><strong class="text-primary">#{{ $xq->id }}</strong></td>
                                <td><small>{{ $xq->updated_at->format('d/m/Y H:i') }}</small></td>
                                <td>
                                    <div>
                                        <div class="fw-medium">{{ $xq->benhAn->user->name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $xq->benhAn->user->so_dien_thoai ?? '' }}</small>
                                    </div>
                                </td>
                                <td><small>{{ $xq->bacSi->user->name ?? 'N/A' }}</small></td>
                                <td><span class="badge bg-light text-dark">{{ $xq->loai }}</span></td>
                                <td class="text-center">
                                    <a href="{{ route('staff.xquang.show', $xq) }}" class="btn btn-sm btn-outline-primary" title="Xem">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($xq->file_path)
                                        <a href="{{ $xq->getDownloadUrl() }}" class="btn btn-sm btn-outline-success" title="Tải" target="_blank">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Chưa có X-Quang hoàn thành</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $xQuangs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
