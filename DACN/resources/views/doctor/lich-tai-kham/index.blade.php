@extends('layouts.doctor')

@section('title', 'Danh sách lịch tái khám')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-calendar-check"></i> Lịch tái khám</h2>
        <a href="{{ route('doctor.lich-tai-kham.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tạo lịch tái khám
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
            <form method="GET" action="{{ route('doctor.lich-tai-kham.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="trang_thai" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="Đã hẹn" {{ request('trang_thai') == 'Đã hẹn' ? 'selected' : '' }}>Đã hẹn</option>
                            <option value="Đã xác nhận" {{ request('trang_thai') == 'Đã xác nhận' ? 'selected' : '' }}>Đã xác nhận</option>
                            <option value="Đã khám" {{ request('trang_thai') == 'Đã khám' ? 'selected' : '' }}>Đã khám</option>
                            <option value="Đã hủy" {{ request('trang_thai') == 'Đã hủy' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Từ ngày</label>
                        <input type="date" name="tu_ngay" class="form-control" value="{{ request('tu_ngay') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Đến ngày</label>
                        <input type="date" name="den_ngay" class="form-control" value="{{ request('den_ngay') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Lọc
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Mã</th>
                            <th>Bệnh nhân</th>
                            <th>Ngày hẹn</th>
                            <th>Giờ hẹn</th>
                            <th>Lý do</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($lichTaiKhams as $lich)
                            <tr>
                                <td>#{{ $lich->id }}</td>
                                <td>
                                    <strong>{{ $lich->benhNhan->name }}</strong>
                                    <br><small class="text-muted">BA #{{ $lich->benh_an_id }}</small>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($lich->ngay_hen)->format('d/m/Y') }}</td>
                                <td>{{ $lich->gio_hen ? \Carbon\Carbon::parse($lich->gio_hen)->format('H:i') : '-' }}</td>
                                <td>{{ Str::limit($lich->ly_do, 50) }}</td>
                                <td>
                                    @php
                                        $badgeClass = match($lich->trang_thai) {
                                            'Đã hẹn' => 'bg-info',
                                            'Đã xác nhận' => 'bg-primary',
                                            'Đã khám' => 'bg-success',
                                            'Đã hủy' => 'bg-secondary',
                                            'Quá hạn' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $lich->trang_thai }}</span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('doctor.lich-tai-kham.show', $lich) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if (!in_array($lich->trang_thai, ['Đã khám', 'Đã hủy']))
                                            <form action="{{ route('doctor.lich-tai-kham.destroy', $lich) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Xác nhận hủy lịch tái khám?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hủy">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p>Chưa có lịch tái khám nào</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Hiển thị {{ $lichTaiKhams->firstItem() ?? 0 }} - {{ $lichTaiKhams->lastItem() ?? 0 }} 
                    trong tổng số {{ $lichTaiKhams->total() }} lịch tái khám
                </div>
                {{ $lichTaiKhams->appends(request()->except('page'))->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
