@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">

        {{-- ============================
         🔥 TIÊN ĐỀ + THÔNG BÁO
    ============================= --}}
        <h1 class="fw-bold mb-4">
            <i class="bi bi-heart-pulse me-2"></i>Quản lý Dịch vụ
        </h1>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif


        {{-- ============================
         🔥 DANH SÁCH DỊCH VỤ
    ============================= --}}
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold">
                    <i class="bi bi-list-ul me-2"></i>Danh sách dịch vụ
                </h5>

                <a href="{{ route('admin.dich-vu.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg me-1"></i> Thêm dịch vụ
                </a>
            </div>

            <div class="card-body">
                {{-- Bộ lọc --}}
                <form method="GET" action="{{ route('admin.dich-vu.index') }}" class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tìm kiếm</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Nhập tên dịch vụ..." 
                               value="{{ request('search') }}">
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Loại dịch vụ</label>
                        <select name="loai" class="form-select">
                            <option value="">-- Tất cả --</option>
                            <option value="Cơ bản" {{ request('loai') == 'Cơ bản' ? 'selected' : '' }}>Cơ bản</option>
                            <option value="Nâng cao" {{ request('loai') == 'Nâng cao' ? 'selected' : '' }}>Nâng cao</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Trạng thái</label>
                        <select name="hoat_dong" class="form-select">
                            <option value="">-- Tất cả --</option>
                            <option value="true" {{ request('hoat_dong') == 'true' ? 'selected' : '' }}>Đang hoạt động</option>
                            <option value="false" {{ request('hoat_dong') == 'false' ? 'selected' : '' }}>Tạm dừng</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bi bi-search me-1"></i>Lọc
                        </button>
                        <a href="{{ route('admin.dich-vu.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                    </div>
                </form>

                <div class="table-responsive">
                    <table id="dichvuTable" class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">ID</th>
                                <th width="20%">Tên dịch vụ</th>
                                <th width="10%">Loại</th>
                                <th width="30%">Mô tả</th>
                                <th width="8%">Thời gian<br>(phút)</th>
                                <th width="12%">Giá (VNĐ)</th>
                                <th width="8%">Trạng thái</th>
                                <th width="7%" class="text-center">Hành động</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($dsDichVu as $dichVu)
                                <tr>
                                    <td>{{ $dichVu->id }}</td>

                                    <td class="fw-semibold">
                                        {{ $dichVu->ten_dich_vu }}
                                    </td>

                                    <td>
                                        @if($dichVu->loai == 'Cơ bản')
                                            <span class="badge bg-primary">
                                                <i class="bi bi-check-circle me-1"></i>Cơ bản
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-star me-1"></i>Nâng cao
                                            </span>
                                        @endif
                                    </td>

                                    <td>{{ Str::limit($dichVu->mo_ta, 80) }}</td>

                                    <td class="text-center">{{ $dichVu->thoi_gian_uoc_tinh }}</td>

                                    <td class="text-end">{{ number_format($dichVu->gia, 0, ',', '.') }}</td>

                                    <td>
                                        @if($dichVu->hoat_dong)
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>Hoạt động
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="bi bi-pause-circle me-1"></i>Tạm dừng
                                            </span>
                                        @endif
                                    </td>

                                    <td class="text-center text-nowrap">
                                        <a href="{{ route('admin.dich-vu.edit', $dichVu) }}"
                                            class="btn btn-sm btn-outline-primary me-1"
                                            title="Sửa">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <form action="{{ route('admin.dich-vu.destroy', $dichVu) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa dịch vụ này?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty

                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="bi bi-inbox fs-2 text-muted mb-3 d-block"></i>
                                        <p class="text-muted mb-0">Chưa có dịch vụ nào.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-center mt-3">
                    {{ $dsDichVu->links() }}
                </div>

            </div>
        </div>

    </div>
@endsection

@push('styles')
<style>
    .table-responsive {
        overflow-x: auto;
    }
    .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }
</style>
@endpush
