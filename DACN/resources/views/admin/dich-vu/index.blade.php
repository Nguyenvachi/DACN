@extends('layouts.admin')

@section('title', 'Quản lý dịch vụ')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Quản lý dịch vụ</h2>
        <a href="{{ route('admin.dich-vu.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm dịch vụ mới
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filters -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.dich-vu.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Loại dịch vụ</label>
                        <select name="loai" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="Cơ bản" {{ request('loai') == 'Cơ bản' ? 'selected' : '' }}>Cơ bản</option>
                            <option value="Nâng cao" {{ request('loai') == 'Nâng cao' ? 'selected' : '' }}>Nâng cao</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Chuyên khoa</label>
                        <select name="chuyen_khoa_id" class="form-select">
                            <option value="">Tất cả</option>
                            @foreach ($chuyenKhoas as $ck)
                                <option value="{{ $ck->id }}" {{ request('chuyen_khoa_id') == $ck->id ? 'selected' : '' }}>
                                    {{ $ck->ten_chuyen_khoa }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Trạng thái</label>
                        <select name="hoat_dong" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="1" {{ request('hoat_dong') === '1' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="0" {{ request('hoat_dong') === '0' ? 'selected' : '' }}>Ngưng hoạt động</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tìm kiếm</label>
                        <input type="text" name="search" class="form-control" placeholder="Tên dịch vụ..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Services Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên dịch vụ</th>
                            <th>Loại</th>
                            <th>Chuyên khoa</th>
                            <th>Giá tiền</th>
                            <th>Thời gian</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dichVus as $dichVu)
                            <tr>
                                <td>{{ $dichVu->id }}</td>
                                <td>
                                    <strong>{{ $dichVu->ten_dich_vu }}</strong>
                                    @if ($dichVu->mo_ta)
                                        <br><small class="text-muted">{{ Str::limit($dichVu->mo_ta, 50) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $dichVu->loai == 'Cơ bản' ? 'bg-info' : 'bg-warning' }}">
                                        {{ $dichVu->loai }}
                                    </span>
                                </td>
                                <td>{{ $dichVu->chuyenKhoa->ten_chuyen_khoa ?? '-' }}</td>
                                <td class="text-end">
                                    <strong>{{ number_format($dichVu->gia_tien) }} đ</strong>
                                </td>
                                <td>{{ $dichVu->thoi_gian ? $dichVu->thoi_gian . ' phút' : '-' }}</td>
                                <td>
                                    <form action="{{ route('admin.dich-vu.toggle-status', $dichVu) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $dichVu->hoat_dong ? 'btn-success' : 'btn-secondary' }}" 
                                                onclick="return confirm('Xác nhận thay đổi trạng thái?')">
                                            <i class="fas fa-{{ $dichVu->hoat_dong ? 'check' : 'times' }}"></i>
                                            {{ $dichVu->hoat_dong ? 'Hoạt động' : 'Ngưng' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.dich-vu.edit', $dichVu) }}" 
                                           class="btn btn-sm btn-warning" 
                                           title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.dich-vu.destroy', $dichVu) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Xác nhận xóa dịch vụ này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p>Không tìm thấy dịch vụ nào</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Hiển thị {{ $dichVus->firstItem() ?? 0 }} - {{ $dichVus->lastItem() ?? 0 }} 
                    trong tổng số {{ $dichVus->total() }} dịch vụ
                </div>
                {{ $dichVus->appends(request()->except('page'))->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
