{{-- Parent file: resources/views/layouts/staff.blade.php --}}
@extends('layouts.staff')

@section('title', 'Xét Nghiệm Đã Hoàn Thành')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Xét Nghiệm Đã Hoàn Thành</h2>
            <p class="text-muted mb-0">
                <i class="fas fa-check-circle"></i> Danh sách các xét nghiệm đã upload kết quả
            </p>
        </div>
        <a href="{{ route('staff.xetnghiem.pending') }}" class="btn btn-outline-primary">
            <i class="fas fa-clipboard-list"></i> Xét nghiệm chờ xử lý
        </a>
    </div>

    {{-- Statistics Cards --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-success bg-opacity-10 p-3">
                                <i class="fas fa-check-double text-success fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Đã hoàn thành</h6>
                            <h3 class="mb-0">{{ $xetNghiems->total() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-info bg-opacity-10 p-3">
                                <i class="fas fa-calendar-day text-info fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Hôm nay</h6>
                            <h3 class="mb-0">{{ $todayCount ?? ($stats['today'] ?? 0) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                                <i class="fas fa-calendar-week text-warning fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Tuần này</h6>
                            <h3 class="mb-0">{{ $weekCount ?? ($stats['this_week'] ?? 0) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Form --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('staff.xetnghiem.completed') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Tìm kiếm</label>
                    <input type="text" name="search" class="form-control"
                           placeholder="Tên bệnh nhân, mã XN..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Từ ngày</label>
                    <input type="date" name="from_date" class="form-control"
                           value="{{ request('from_date') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Đến ngày</label>
                    <input type="date" name="to_date" class="form-control"
                           value="{{ request('to_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Bác sĩ chỉ định</label>
                    <select name="bac_si_id" class="form-select">
                        <option value="">-- Tất cả --</option>
                        @foreach($bacSis as $bs)
                            <option value="{{ $bs->id }}" {{ request('bac_si_id') == $bs->id ? 'selected' : '' }}>
                                {{ $bs->ho_ten ?? optional($bs->user)->name ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="fas fa-filter"></i> Lọc
                    </button>
                    <a href="{{ route('staff.xetnghiem.completed') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- List --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if($xetNghiems->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Mã XN</th>
                                <th>Bệnh nhân</th>
                                <th>Loại xét nghiệm</th>
                                <th>Bác sĩ chỉ định</th>
                                <th>Ngày chỉ định</th>
                                <th>Ngày hoàn thành</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($xetNghiems as $xn)
                            <tr>
                                <td>
                                    <span class="badge bg-success">
                                        <i class="fas fa-vial"></i> #{{ $xn->id }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            @if($xn->benhAn->user->avatar)
                                                <img src="{{ $xn->benhAn->user->avatar }}"
                                                     class="rounded-circle"
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $xn->benhAn->user->name ?? ($xn->benhAn->user->ho_ten ?? 'N/A') }}</div>
                                            <small class="text-muted">{{ $xn->benhAn->user->so_dien_thoai ?? '' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $xn->loai_xet_nghiem }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user-md text-primary me-2"></i>
                                        <div>
                                            <div>{{ $xn->bacSi->ho_ten ?? optional($xn->bacSi->user)->name ?? 'N/A' }}</div>
                                            @php
                                                $ckName = optional(optional($xn->bacSi)->chuyenKhoa)->ten
                                                    ?? (optional($xn->bacSi)->chuyen_khoa ?? '');
                                            @endphp
                                            @if(!empty($ckName))
                                                <small class="text-muted">{{ $ckName }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $ngayChiDinh = $xn->ngay_chi_dinh ?? $xn->created_at;
                                    @endphp
                                    <div>{{ optional($ngayChiDinh)->format('d/m/Y') ?? '--' }}</div>
                                    <small class="text-muted">{{ optional($ngayChiDinh)->format('H:i') ?? '' }}</small>
                                </td>
                                <td>
                                    @if($xn->ngay_thuc_hien)
                                        <div>{{ $xn->ngay_thuc_hien->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $xn->ngay_thuc_hien->format('H:i') }}</small>
                                    @else
                                        <span class="text-muted">--</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('staff.xetnghiem.show', $xn) }}"
                                           class="btn btn-outline-primary"
                                           title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($xn->file_path)
                                            <a href="{{ $xn->getDownloadUrl() }}"
                                               class="btn btn-outline-success"
                                               title="Tải file kết quả">
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
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Hiển thị {{ $xetNghiems->firstItem() }} - {{ $xetNghiems->lastItem() }}
                        trong tổng số {{ $xetNghiems->total() }} xét nghiệm
                    </div>
                    {{ $xetNghiems->withQueryString()->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Không có xét nghiệm nào đã hoàn thành</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
