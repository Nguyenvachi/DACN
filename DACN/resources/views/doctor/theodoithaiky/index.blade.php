{{-- Parent file: resources/views/layouts/doctor.blade.php --}}
@extends('layouts.doctor')

@section('title', 'Theo dõi thai kỳ')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: #1f2937;">
                <i class="fas fa-baby me-2" style="color: #10b981;"></i>
                Theo dõi thai kỳ, sức khỏe
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Theo dõi thai kỳ</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="vc-card"><div class="card-body">
                <div class="text-muted small">Tổng bản ghi</div>
                <div class="fs-3 fw-bold" style="color:#10b981;">{{ $stats['total'] ?? 0 }}</div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="vc-card"><div class="card-body">
                <div class="text-muted small">Đã gửi</div>
                <div class="fs-3 fw-bold">{{ $stats['submitted'] ?? 0 }}</div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="vc-card"><div class="card-body">
                <div class="text-muted small">Đã duyệt</div>
                <div class="fs-3 fw-bold">{{ $stats['reviewed'] ?? 0 }}</div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="vc-card"><div class="card-body">
                <div class="text-muted small">Đã ghi nhận</div>
                <div class="fs-3 fw-bold">{{ $stats['recorded'] ?? 0 }}</div>
            </div></div>
        </div>
    </div>

    <div class="card vc-card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small">Tìm bệnh nhân</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Tên, SĐT, email...">
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="">-- Tất cả --</option>
                        <option value="submitted" {{ request('status')=='submitted' ? 'selected' : '' }}>Đã gửi</option>
                        <option value="reviewed" {{ request('status')=='reviewed' ? 'selected' : '' }}>Đã duyệt</option>
                        <option value="recorded" {{ request('status')=='recorded' ? 'selected' : '' }}>Đã ghi nhận</option>
                        <option value="archived" {{ request('status')=='archived' ? 'selected' : '' }}>Lưu trữ</option>
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
                <div class="col-md-1 d-flex align-items-end">
                    <button class="btn btn-outline-primary w-100" type="submit"><i class="fas fa-filter"></i></button>
                </div>
            </form>
        </div>
    </div>

    <div class="card vc-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Bệnh nhân</th>
                            <th>Ngày</th>
                            <th>Tuần thai</th>
                            <th>Trạng thái</th>
                            <th class="text-end"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($records as $r)
                            <tr>
                                <td>{{ $r->id }}</td>
                                <td>{{ $r->benhAn?->user?->name ?? $r->benhAn?->user?->ho_ten ?? '---' }}</td>
                                <td>{{ $r->ngay_theo_doi?->format('d/m/Y') ?? '---' }}</td>
                                <td>{{ $r->tuan_thai ?? '---' }}</td>
                                <td>
                                    <span class="badge {{ $r->trang_thai_badge_class }}">{{ $r->trang_thai_text }}</span>
                                    @if($r->has_canh_bao)
                                        <span class="badge {{ $r->canh_bao_badge_class }} ms-1" title="{{ $r->canh_bao_summary }}">Cảnh báo</span>
                                    @else
                                        <span class="badge {{ $r->canh_bao_badge_class }} ms-1">Ổn</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('doctor.theodoithaiky.show', $r) }}">Xem</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-muted">Chưa có bản ghi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $records->links() }}
        </div>
    </div>
</div>
@endsection
