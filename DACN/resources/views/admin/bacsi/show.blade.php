@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-person-badge text-primary me-2"></i>
            Thông tin Bác sĩ
        </h2>
        <a href="{{ route('admin.bac-si.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- Thông tin cá nhân --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center">
                    @if ($bacSi->avatar_url)
                        <img src="{{ $bacSi->avatar_url }}"
                             alt="{{ $bacSi->ho_ten }}"
                             class="rounded-circle mb-3"
                             style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                             style="width: 150px; height: 150px;">
                            <i class="bi bi-person-circle fs-1 text-muted"></i>
                        </div>
                    @endif

                    <h4 class="fw-bold mb-1">{{ $bacSi->ho_ten }}</h4>
                    <p class="text-muted mb-3">{{ $bacSi->chuyen_khoa }}</p>

                    @if ($bacSi->trang_thai === 'Đang hoạt động')
                        <span class="badge bg-success fs-6">Đang hoạt động</span>
                    @else
                        <span class="badge bg-danger fs-6">Ngừng hoạt động</span>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Thao tác</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.bac-si.edit', $bacSi->id) }}"
                       class="btn btn-primary w-100 mb-2">
                        <i class="bi bi-pencil"></i> Chỉnh sửa
                    </a>
                    <form action="{{ route('admin.bac-si.destroy', $bacSi->id) }}"
                          method="POST"
                          onsubmit="return confirm('Bạn có chắc muốn xóa bác sĩ này?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger w-100" type="submit">
                            <i class="bi bi-trash"></i> Xóa
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Chi tiết thông tin --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Thông tin chi tiết</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong>Email:</strong>
                            <p class="mb-0">{{ $bacSi->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Số điện thoại:</strong>
                            <p class="mb-0">{{ $bacSi->so_dien_thoai }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Chuyên khoa:</strong>
                            <p class="mb-0">{{ $bacSi->chuyen_khoa }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Kinh nghiệm:</strong>
                            <p class="mb-0">{{ $bacSi->kinh_nghiem ?? 0 }} năm</p>
                        </div>
                        <div class="col-12">
                            <strong>Địa chỉ:</strong>
                            <p class="mb-0">{{ $bacSi->dia_chi ?? 'Chưa cập nhật' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Mô tả</h5>
                </div>
                <div class="card-body">
                    <p>{{ $bacSi->mo_ta ?? 'Chưa có mô tả' }}</p>
                </div>
            </div>

            {{-- Thống kê đánh giá --}}
            @php
                $avgRating = \App\Models\DanhGia::getAverageRating($bacSi->id);
                $totalReviews = \App\Models\DanhGia::getTotalReviews($bacSi->id);
            @endphp

            @if ($totalReviews > 0)
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-star-fill text-warning"></i>
                            Đánh giá
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="display-4 fw-bold text-warning me-3">
                                {{ number_format($avgRating, 1) }}
                            </div>
                            <div>
                                <div class="mb-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= round($avgRating))
                                            <i class="bi bi-star-fill text-warning"></i>
                                        @else
                                            <i class="bi bi-star text-muted"></i>
                                        @endif
                                    @endfor
                                </div>
                                <small class="text-muted">{{ $totalReviews }} đánh giá</small>
                            </div>
                        </div>

                        <a href="{{ route('admin.danhgia.index', ['bac_si_id' => $bacSi->id]) }}"
                           class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-list"></i> Xem tất cả đánh giá
                        </a>
                    </div>
                </div>
            @endif

            {{-- Lịch làm việc --}}
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Lịch làm việc</h5>
                </div>
                <div class="card-body">
                    @php
                        $lichLamViecs = $bacSi->lichLamViecs()->latest()->take(5)->get();
                    @endphp

                    @if ($lichLamViecs->count() > 0)
                        <div class="list-group">
                            @foreach ($lichLamViecs as $lich)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ \Carbon\Carbon::parse($lich->ngay_lam_viec)->format('d/m/Y') }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                {{ $lich->thoi_gian_bat_dau }} - {{ $lich->thoi_gian_ket_thuc }}
                                            </small>
                                        </div>
                                        @if ($lich->phong)
                                            <span class="badge bg-info">{{ $lich->phong->ten_phong }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('admin.lichlamviec.index', $bacSi->id) }}"
                               class="btn btn-sm btn-outline-primary">
                                Xem tất cả lịch làm việc
                            </a>
                        </div>
                    @else
                        <p class="text-muted mb-0">Chưa có lịch làm việc</p>
                    @endif
                </div>
            </div>

            {{-- Lịch hẹn gần đây --}}
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Lịch hẹn gần đây</h5>
                </div>
                <div class="card-body">
                    @php
                        $lichHens = $bacSi->lichHens()->with('user', 'dichVu')->latest()->take(5)->get();
                    @endphp

                    @if ($lichHens->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Ngày</th>
                                        <th>Bệnh nhân</th>
                                        <th>Dịch vụ</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lichHens as $lich)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($lich->ngay_hen)->format('d/m/Y') }}</td>
                                            <td>{{ $lich->user->name }}</td>
                                            <td>{{ $lich->dichVu->ten_dich_vu ?? 'N/A' }}</td>
                                            <td>
                                                @if ($lich->trang_thai === \App\Models\LichHen::STATUS_PENDING_VN)
                                                    <span class="badge bg-warning text-dark">{{ \App\Models\LichHen::STATUS_PENDING_VN }}</span>
                                                @elseif ($lich->trang_thai === \App\Models\LichHen::STATUS_CONFIRMED_VN)
                                                    <span class="badge bg-success">{{ \App\Models\LichHen::STATUS_CONFIRMED_VN }}</span>
                                                @elseif ($lich->trang_thai === \App\Models\LichHen::STATUS_COMPLETED_VN)
                                                    <span class="badge bg-info">{{ \App\Models\LichHen::STATUS_COMPLETED_VN }}</span>
                                                @else
                                                    <span class="badge bg-danger">{{ $lich->trang_thai }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">Chưa có lịch hẹn</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
