@extends('layouts.staff')

@section('page-title', 'Dashboard Nhân viên')

@section('content')
{{-- ENHANCED: Modern dashboard with VietCare design (Parent: staff/dashboard.blade.php) --}}

    @if (!$nhanVien)
        <x-alert type="warning" title="Chưa có hồ sơ nhân viên" :dismissible="false">
            Tài khoản của bạn chưa được liên kết với hồ sơ nhân viên. Vui lòng liên hệ quản trị viên.
        </x-alert>
    @else
        {{-- Welcome Section --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <div class="card-body text-white p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="mb-1 fw-bold">👋 Xin chào, {{ $nhanVien->ho_ten }}!</h2>
                                <p class="mb-0 opacity-90">{{ $nhanVien->chuc_vu ?? 'Nhân viên' }} • {{ now()->isoFormat('dddd, D MMMM YYYY') }}</p>
                            </div>
                            <div class="text-end d-none d-md-block">
                                <div class="fs-1 fw-bold">{{ now()->format('H:i') }}</div>
                                <small class="opacity-90">Thời gian hiện tại</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Stats --}}
        <div class="row mb-4 g-4">
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
                    <div class="card-body text-white p-4">
                        <div class="d-flex align-items-center">
                            <div class="p-3 rounded-circle bg-white bg-opacity-25">
                                <i class="bi bi-calendar-check fs-2"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-white text-opacity-75 mb-1 small fw-medium">Ca hôm nay</p>
                                <h3 class="mb-0 fw-bold">{{ $caHomNay->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 h-100" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);">
                    <div class="card-body text-white p-4">
                        <div class="d-flex align-items-center">
                            <div class="p-3 rounded-circle bg-white bg-opacity-25">
                                <i class="bi bi-calendar-week fs-2"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-white text-opacity-75 mb-1 small fw-medium">Ca tuần này</p>
                                <h3 class="mb-0 fw-bold">{{ $caTuanNay->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 h-100" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);">
                    <div class="card-body text-white p-4">
                        <div class="d-flex align-items-center">
                            <div class="p-3 rounded-circle bg-white bg-opacity-25">
                                <i class="bi bi-person-check fs-2"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-white text-opacity-75 mb-1 small fw-medium">Trạng thái</p>
                                <h3 class="mb-0 fw-bold">{{ $nhanVien->trang_thai === 'active' ? 'Hoạt động' : 'Nghỉ' }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 h-100" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);">
                    <div class="card-body text-white p-4">
                        <div class="d-flex align-items-center">
                            <div class="p-3 rounded-circle bg-white bg-opacity-25">
                                <i class="bi bi-clock-history fs-2"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-white text-opacity-75 mb-1 small fw-medium">Tổng giờ tuần</p>
                                <h3 class="mb-0 fw-bold">{{ $caTuanNay->sum(function($ca) {
                                    return \Carbon\Carbon::parse($ca->bat_dau)->diffInHours(\Carbon\Carbon::parse($ca->ket_thuc));
                                }) }} giờ</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content Row --}}
        <div class="row">
            {{-- Ca làm việc hôm nay --}}
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 pt-4 pb-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-calendar-check text-primary me-2"></i>Ca làm việc hôm nay
                        </h5>
                        <small class="text-muted">{{ now()->format('d/m/Y') }}</small>
                    </div>
                    <div class="card-body">
                        @if ($caHomNay->isEmpty())
                            <x-empty-state
                                icon="bi-calendar-x"
                                title="Không có ca làm việc"
                                description="Bạn không có ca làm việc hôm nay!"
                            />
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th><i class="bi bi-clock me-1"></i>Giờ bắt đầu</th>
                                            <th><i class="bi bi-clock-fill me-1"></i>Giờ kết thúc</th>
                                            <th><i class="bi bi-sticky me-1"></i>Ghi chú</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($caHomNay as $ca)
                                            <tr>
                                                <td><span class="badge bg-primary">{{ \Carbon\Carbon::parse($ca->bat_dau)->format('H:i') }}</span></td>
                                                <td><span class="badge bg-success">{{ \Carbon\Carbon::parse($ca->ket_thuc)->format('H:i') }}</span></td>
                                                <td>{{ $ca->ghi_chu ?? '—' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Thông tin cá nhân --}}
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 pt-4 pb-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-person-badge text-success me-2"></i>Thông tin cá nhân
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <i class="bi bi-person-circle fs-3 text-primary me-3"></i>
                                    <div>
                                        <small class="text-muted d-block">Họ tên</small>
                                        <strong>{{ $nhanVien->ho_ten }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <i class="bi bi-briefcase fs-3 text-success me-3"></i>
                                    <div>
                                        <small class="text-muted d-block">Chức vụ</small>
                                        <strong>{{ $nhanVien->chuc_vu ?? 'Chưa cập nhật' }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <i class="bi bi-envelope fs-4 text-info me-3"></i>
                                    <div>
                                        <small class="text-muted d-block">Email</small>
                                        <strong class="small">{{ $nhanVien->email_cong_viec }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <i class="bi bi-telephone fs-4 text-warning me-3"></i>
                                    <div>
                                        <small class="text-muted d-block">Điện thoại</small>
                                        <strong>{{ $nhanVien->so_dien_thoai ?? 'Chưa cập nhật' }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Lịch tuần này --}}
        <div class="card border-0 shadow-sm" id="lich">
            <div class="card-header bg-white border-0 pt-4 pb-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-calendar3 text-info me-2"></i>Lịch làm việc tuần này
                </h5>
                <small class="text-muted">Từ {{ \Carbon\Carbon::now()->startOfWeek()->format('d/m') }} đến {{ \Carbon\Carbon::now()->endOfWeek()->format('d/m/Y') }}</small>
            </div>
            <div class="card-body">
                @if ($caTuanNay->isEmpty())
                    <x-empty-state
                        icon="bi-calendar2-x"
                        title="Không có lịch"
                        description="Không có ca làm việc nào trong tuần này."
                    />
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th><i class="bi bi-calendar3 me-1"></i>Ngày</th>
                                    <th><i class="bi bi-calendar-day me-1"></i>Thứ</th>
                                    <th><i class="bi bi-clock me-1"></i>Giờ làm việc</th>
                                    <th><i class="bi bi-sticky me-1"></i>Ghi chú</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($caTuanNay as $ca)
                                    <tr class="{{ \Carbon\Carbon::parse($ca->ngay)->isToday() ? 'table-warning' : '' }}">
                                        <td>
                                            <strong>{{ \Carbon\Carbon::parse($ca->ngay)->format('d/m/Y') }}</strong>
                                            @if(\Carbon\Carbon::parse($ca->ngay)->isToday())
                                                <span class="badge bg-warning ms-2">Hôm nay</span>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($ca->ngay)->isoFormat('dddd') }}</td>
                                        <td>
                                            <span class="badge bg-primary">{{ \Carbon\Carbon::parse($ca->bat_dau)->format('H:i') }}</span>
                                            <i class="bi bi-arrow-right mx-1"></i>
                                            <span class="badge bg-success">{{ \Carbon\Carbon::parse($ca->ket_thuc)->format('H:i') }}</span>
                                        </td>
                                        <td>{{ $ca->ghi_chu ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    @endif
@endsection

<style>
.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.card:hover {
    transform: translateY(-2px);
}
</style>
