@extends('layouts.doctor')

@section('title', 'Theo dõi thai kỳ')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: #1f2937;">
                <i class="fas fa-heart me-2" style="color: #ec4899;"></i>
                Theo dõi thai kỳ
            </h4>
            <p class="text-muted mb-0">Quản lý hồ sơ theo dõi thai kỳ của bệnh nhân</p>
        </div>
        <a href="{{ route('doctor.theo-doi-thai-ky.create') }}" class="btn vc-btn-primary">
            <i class="fas fa-plus me-2"></i>Tạo hồ sơ mới
        </a>
    </div>

    {{-- Filters --}}
    <div class="card vc-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('doctor.theo-doi-thai-ky.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Tìm theo tên, email, SĐT..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="trang_thai" class="form-select">
                            <option value="">-- Tất cả trạng thái --</option>
                            <option value="Đang theo dõi" {{ request('trang_thai') === 'Đang theo dõi' ? 'selected' : '' }}>Đang theo dõi</option>
                            <option value="Đã sinh" {{ request('trang_thai') === 'Đã sinh' ? 'selected' : '' }}>Đã sinh</option>
                            <option value="Sẩy thai" {{ request('trang_thai') === 'Sẩy thai' ? 'selected' : '' }}>Sẩy thai</option>
                            <option value="Nạo thai" {{ request('trang_thai') === 'Nạo thai' ? 'selected' : '' }}>Nạo thai</option>
                            <option value="Chuyển viện" {{ request('trang_thai') === 'Chuyển viện' ? 'selected' : '' }}>Chuyển viện</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i>Tìm kiếm
                        </button>
                        <a href="{{ route('doctor.theo-doi-thai-ky.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-redo me-1"></i>Đặt lại
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- List --}}
    <div class="card vc-card">
        <div class="card-body">
            @if($theoDoiList->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Bệnh nhân</th>
                            <th>Ngày kinh cuối</th>
                            <th>Ngày dự sinh</th>
                            <th>Tuổi thai</th>
                            <th>Loại thai</th>
                            <th>Trạng thái</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($theoDoiList as $item)
                        @php
                            $tuoiThai = $item->tuoiThaiHienTai();
                            $soNgayConLai = $item->soNgayConLai();
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        <i class="fas fa-user-circle fa-2x text-primary"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $item->user->name }}</strong><br>
                                        <small class="text-muted">{{ $item->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($item->ngay_kinh_cuoi)->format('d/m/Y') }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($item->ngay_du_sinh)->format('d/m/Y') }}
                                @if($item->trang_thai === 'Đang theo dõi')
                                <br><small class="text-muted">
                                    @if($soNgayConLai > 0)
                                        Còn {{ $soNgayConLai }} ngày
                                    @elseif($soNgayConLai == 0)
                                        <span class="text-danger fw-bold">Hôm nay!</span>
                                    @else
                                        <span class="text-danger">Quá {{ abs($soNgayConLai) }} ngày</span>
                                    @endif
                                </small>
                                @endif
                            </td>
                            <td>
                                @if($tuoiThai && $item->trang_thai === 'Đang theo dõi')
                                    <span class="badge bg-info">
                                        {{ $tuoiThai['tuan'] }} tuần {{ $tuoiThai['ngay'] }} ngày
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $item->loai_thai === 'Đơn thai' ? 'primary' : 'warning' }}">
                                    {{ $item->loai_thai }}
                                </span>
                            </td>
                            <td>
                                @if($item->trang_thai === 'Đang theo dõi')
                                <span class="badge bg-success">{{ $item->trang_thai }}</span>
                                @elseif($item->trang_thai === 'Đã sinh')
                                <span class="badge bg-primary">{{ $item->trang_thai }}</span>
                                @else
                                <span class="badge bg-secondary">{{ $item->trang_thai }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('doctor.theo-doi-thai-ky.show', $item->id) }}" 
                                   class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('doctor.theo-doi-thai-ky.edit', $item->id) }}" 
                                   class="btn btn-sm btn-outline-secondary" title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $theoDoiList->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-heart fa-4x text-muted mb-3"></i>
                <p class="text-muted">Chưa có hồ sơ theo dõi thai kỳ nào</p>
                <a href="{{ route('doctor.theo-doi-thai-ky.create') }}" class="btn vc-btn-primary">
                    <i class="fas fa-plus me-2"></i>Tạo hồ sơ đầu tiên
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
