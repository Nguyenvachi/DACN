@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">
        <h1 class="mb-4">Danh sách Bác sĩ</h1>

        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">

            <!-- =========================
                             🔥 BỔ SUNG: Bộ lọc + tìm kiếm
                        ========================== -->
            <div class="p-3 border-bottom bg-light rounded">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="keyword" class="form-control"
                            placeholder="Tìm tên bác sĩ, chuyên khoa..." value="{{ request('keyword') }}">
                    </div>

                    <div class="col-md-3">
                        <select name="chuyen_khoa" class="form-select">
                            <option value="">-- Chọn chuyên khoa --</option>
                            @foreach(($chuyenKhoaList ?? []) as $ck)
                                @php
                                    // support both plain string list and objects (e.g. model with `ten` or `name`)
                                    $ckValue = is_object($ck) ? ($ck->ten ?? $ck->name ?? $ck) : $ck;
                                @endphp
                                <option value="{{ $ckValue }}" {{ request('chuyen_khoa') == $ckValue ? 'selected' : '' }}>
                                    {{ $ckValue }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select name="trang_thai" class="form-select">
                            <option value="">-- Trạng thái --</option>
                            @foreach(($trangThaiOptions ?? ['Đang hoạt động', 'Ngừng hoạt động']) as $tt)
                                <option value="{{ $tt }}" {{ request('trang_thai') == $tt ? 'selected' : '' }}>{{ $tt }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 d-grid">
                        <button class="btn btn-dark btn-sm">
                            <i class="bi bi-funnel"></i> Lọc
                        </button>
                    </div>
                </form>
            </div>
            <!-- ========================= -->


            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Danh sách Bác sĩ</h5>
                <a href="{{ route('admin.bac-si.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Thêm Bác sĩ mới
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">ID</th>
                                <th width="15%">Họ Tên</th>
                                <th width="13%">Chuyên Khoa</th>
                                <th width="12%">Số điện thoại</th>
                                <th width="15%">Email</th>
                                <th width="8%">Kinh nghiệm</th>
                                <th width="10%">Trạng thái</th>
                                <th width="22%">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bacSis as $bacSi)
                                <tr>
                                    <td>{{ $bacSi->id }}</td>
                                    <td><strong>{{ $bacSi->ho_ten }}</strong></td>

                                    <!-- =========================
                                                     🔥 BỔ SUNG: Badge chuyên khoa
                                                ========================== -->
                                    <td>
                                        <span class="badge bg-primary px-3 py-2">
                                            {{ $bacSi->chuyen_khoa }}
                                        </span>
                                    </td>
                                    <!-- ========================= -->

                                    <td>{{ $bacSi->so_dien_thoai }}</td>
                                    <td>{{ $bacSi->email ?? 'N/A' }}</td>
                                    <td>{{ $bacSi->kinh_nghiem ?? 0 }} năm</td>
                                    <td>
                                        @if ($bacSi->trang_thai == 'Đang hoạt động')
                                            <span class="badge bg-success">Đang hoạt động</span>
                                        @else
                                            <span class="badge bg-secondary">Ngừng hoạt động</span>
                                        @endif
                                    </td>

                                    <td>

                                        <!-- =========================
                                                         🔥 BỔ SUNG: Quick View modal
                                                    ========================== -->
                                        <button class="btn btn-sm btn-outline-info me-1 mb-1" data-bs-toggle="modal"
                                            data-bs-target="#modalBacSi{{ $bacSi->id }}"
                                            title="Xem">
                                            <i class="bi bi-eye"></i>
                                        </button>

                                        <div class="modal fade" id="modalBacSi{{ $bacSi->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Thông tin Bác sĩ</h5>
                                                        <button class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <p><strong>Họ tên:</strong> {{ $bacSi->ho_ten }}</p>
                                                        <p><strong>Chuyên khoa:</strong> {{ $bacSi->chuyen_khoa }}</p>
                                                        <p><strong>SĐT:</strong> {{ $bacSi->so_dien_thoai }}</p>
                                                        <p><strong>Email:</strong> {{ $bacSi->email }}</p>
                                                        <p><strong>Kinh nghiệm:</strong> {{ $bacSi->kinh_nghiem }} năm</p>
                                                        <p><strong>Trạng thái:</strong> {{ $bacSi->trang_thai }}</p>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Đóng</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- ========================= -->


                                        <a href="{{ route('admin.bac-si.edit', $bacSi) }}"
                                            class="btn btn-sm btn-outline-primary me-1 mb-1"
                                            title="Sửa">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <a href="{{ route('admin.lichlamviec.index', $bacSi) }}"
                                            class="btn btn-sm btn-outline-success me-1 mb-1"
                                            title="Lịch làm việc">
                                            <i class="bi bi-calendar-check"></i>
                                        </a>

                                        <a href="{{ route('admin.lichnghi.index', $bacSi) }}"
                                            class="btn btn-sm btn-outline-secondary me-1 mb-1"
                                            title="Lịch nghỉ">
                                            <i class="bi bi-calendar-x"></i>
                                        </a>

                                        <a href="{{ route('admin.cadieuchinh.index', $bacSi) }}"
                                            class="btn btn-sm btn-outline-purple me-1 mb-1"
                                            title="Ca điều chỉnh">
                                            <i class="bi bi-calendar2-event"></i>
                                        </a>

                                        <form action="{{ route('admin.bac-si.destroy', $bacSi) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa bác sĩ này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger mb-1" title="Xóa">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="bi bi-inbox fs-2 text-muted mb-3 d-block"></i>
                                        <p class="mb-0">Chưa có bác sĩ nào.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($bacSis->hasPages())
                    <div class="mt-4">
                        {{ $bacSis->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- =========================
                     🔥 BỔ SUNG: CSS làm đẹp UI
                ========================== -->
    <style>
        .table th {
            background: #f8f9fc !important;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 13px;
        }

        .table-striped>tbody>tr:nth-of-type(odd) {
            background-color: #fafafa;
        }

        .card {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .btn-outline-purple {
            color: #a855f7;
            border-color: #a855f7;
        }

        .btn-outline-purple:hover {
            background-color: #a855f7;
            color: white;
            border-color: #a855f7;
        }
    </style>
@endsection
