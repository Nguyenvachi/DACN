{{-- Parent file: resources/views/layouts/admin.blade.php --}}
@extends('layouts.admin')

@section('title', 'Tái khám')

@section('content')
<div>
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-0">Tái khám</h4>
                <div class="text-muted">Quản trị danh sách yêu cầu tái khám</div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small">Chờ xác nhận</div>
                        <div class="fs-4 fw-bold">{{ $stats['pending'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small">Đã xác nhận</div>
                        <div class="fs-4 fw-bold">{{ $stats['confirmed'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small">Đã đặt lịch</div>
                        <div class="fs-4 fw-bold">{{ $stats['booked'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small">Hoàn thành</div>
                        <div class="fs-4 fw-bold">{{ $stats['completed'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small">Đã hủy</div>
                        <div class="fs-4 fw-bold">{{ $stats['cancelled'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label">Tìm kiếm</label>
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="ID, bệnh nhân...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Trạng thái</label>
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

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="taiKhamTable" class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Bệnh nhân</th>
                                <th>Bác sĩ</th>
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
                                    <td>{{ $r->benhAn?->bacSi?->user?->ho_ten ?? $r->benhAn?->bacSi?->user?->name ?? '---' }}</td>
                                    <td>{{ $r->ngay_tai_kham?->format('d/m/Y') ?? '---' }}</td>
                                    <td>{{ $r->thoi_gian_tai_kham ? \Carbon\Carbon::parse($r->thoi_gian_tai_kham)->format('H:i') : '---' }}</td>
                                    <td><span class="badge {{ $r->trang_thai_badge_class }}">{{ $r->trang_thai_text }}</span></td>
                                    <td class="text-end">
                                        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.taikham.show', $r) }}">Xem</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-muted">Chưa có dữ liệu.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $records->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

<x-datatable-script
    tableId="taiKhamTable"
    config='{"paging": false, "info": false, "searching": false, "lengthChange": false}'
/>
