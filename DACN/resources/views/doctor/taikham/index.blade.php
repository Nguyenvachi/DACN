{{-- Parent file: resources/views/layouts/doctor.blade.php --}}
@extends('layouts.doctor')

@section('title', 'Tái khám')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: #1f2937;">
                <i class="fas fa-calendar-alt me-2" style="color: #10b981;"></i>
                Tái khám
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Tái khám</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="vc-card"><div class="card-body">
                <div class="text-muted small">Tổng</div>
                <div class="fs-3 fw-bold" style="color:#10b981;">{{ $stats['total'] ?? 0 }}</div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="vc-card"><div class="card-body">
                <div class="text-muted small">Chờ xác nhận</div>
                <div class="fs-3 fw-bold">{{ $stats['pending'] ?? 0 }}</div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="vc-card"><div class="card-body">
                <div class="text-muted small">Đã xác nhận</div>
                <div class="fs-3 fw-bold">{{ $stats['confirmed'] ?? 0 }}</div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="vc-card"><div class="card-body">
                <div class="text-muted small">Đã đặt lịch</div>
                <div class="fs-3 fw-bold">{{ $stats['booked'] ?? 0 }}</div>
            </div></div>
        </div>
    </div>

    <div class="card vc-card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-5">
                    <label class="form-label small">Tìm bệnh nhân</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Tên, SĐT, email...">
                </div>
                <div class="col-md-4">
                    <label class="form-label small">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="">-- Tất cả --</option>
                        <option value="Chờ xác nhận" {{ request('status')=='Chờ xác nhận' ? 'selected' : '' }}>Chờ xác nhận</option>
                        <option value="Đã xác nhận" {{ request('status')=='Đã xác nhận' ? 'selected' : '' }}>Đã xác nhận</option>
                        <option value="Đã đặt lịch" {{ request('status')=='Đã đặt lịch' ? 'selected' : '' }}>Đã đặt lịch</option>
                        <option value="Hoàn thành" {{ request('status')=='Hoàn thành' ? 'selected' : '' }}>Hoàn thành</option>
                        <option value="Đã hủy" {{ request('status')=='Đã hủy' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-outline-primary w-100" type="submit">Lọc</button>
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
                            <th>Giờ</th>
                            <th>Trạng thái</th>
                            <th class="text-end"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($records as $r)
                            <tr>
                                <td>{{ $r->id }}</td>
                                <td>{{ $r->benhAn?->user?->name ?? $r->benhAn?->user?->ho_ten ?? '---' }}</td>
                                <td>{{ $r->ngay_tai_kham?->format('d/m/Y') ?? '---' }}</td>
                                <td>{{ $r->thoi_gian_tai_kham ? \Carbon\Carbon::parse($r->thoi_gian_tai_kham)->format('H:i') : '---' }}</td>
                                <td><span class="badge {{ $r->trang_thai_badge_class }}">{{ $r->trang_thai_text }}</span></td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('doctor.taikham.show', $r) }}">Xem</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-muted">Chưa có yêu cầu tái khám.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $records->links() }}
        </div>
    </div>
</div>
@endsection
