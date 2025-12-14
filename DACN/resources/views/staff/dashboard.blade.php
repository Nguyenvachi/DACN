@extends('layouts.staff')

@section('page-title', 'Dashboard Nhân viên')

@section('content')
{{-- ENHANCED: Modern dashboard with VietCare design (Parent: staff/dashboard.blade.php) --}}

    @if (!$nhanVien)
        <x-alert type="warning" title="Chưa có hồ sơ nhân viên" :dismissible="false">
            Tài khoản của bạn chưa được liên kết với hồ sơ nhân viên. Vui lòng liên hệ quản trị viên.
        </x-alert>
    @else
        @php
            $roleLabels = [
                'admin' => 'Quản trị',
                'doctor' => 'Bác sĩ',
                'staff' => 'Nhân viên',
                'patient' => 'Bệnh nhân',
            ];
            $currentRole = optional($nhanVien->user)->role;
            $currentRoleLabel = $roleLabels[$currentRole ?? ''] ?? ($currentRole ? ucfirst($currentRole) : 'Nhân viên');
        @endphp
        {{-- Welcome Section --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <div class="card-body text-white p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="mb-1 fw-bold">👋 Xin chào, {{ $nhanVien->ho_ten }}!</h2>
                                <p class="mb-0 opacity-90">{{ $currentRoleLabel }} • {{ now()->isoFormat('dddd, D MMMM YYYY') }}</p>
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
                                <p class="text-white text-opacity-75 mb-1 small fw-medium">Lịch hẹn hôm nay</p>
                                <h3 class="mb-0 fw-bold">{{ $statistics['lich_hen_hom_nay'] ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 h-100" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);">
                    <div class="card-body text-white p-4">
                        <a href="{{ route('staff.donthuoc.dang-cho') }}" class="text-white text-decoration-none">
                            <div class="d-flex align-items-center">
                                <div class="p-3 rounded-circle bg-white bg-opacity-25">
                                    <i class="bi bi-prescription2 fs-2"></i>
                                </div>
                                <div class="ms-3">
                                    <p class="text-white text-opacity-75 mb-1 small fw-medium">Đơn thuốc chờ cấp</p>
                                    <h3 class="mb-0 fw-bold">{{ \App\Models\DonThuoc::whereNull('ngay_cap_thuoc')->count() }}</h3>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 h-100" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4);">
                    <div class="card-body text-white p-4">
                        <div class="d-flex align-items-center">
                            <div class="p-3 rounded-circle bg-white bg-opacity-25">
                                <i class="bi bi-exclamation-triangle fs-2"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-white text-opacity-75 mb-1 small fw-medium">HĐ chưa thanh toán</p>
                                <h3 class="mb-0 fw-bold">{{ $statistics['hoa_don_chua_thanh_toan'] ?? 0 }}</h3>
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
                                <i class="bi bi-check-circle fs-2"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-white text-opacity-75 mb-1 small fw-medium">Đã cấp hôm nay</p>
                                <h3 class="mb-0 fw-bold">{{ \App\Models\DonThuoc::whereDate('ngay_cap_thuoc', today())->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content Row --}}
        <div class="row">
            {{-- Bệnh án cần tạo hóa đơn --}}
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold">
                                <i class="bi bi-receipt text-warning me-2"></i>Bệnh án cần tạo hóa đơn
                            </h5>
                            <span class="badge bg-warning">{{ $benhAnCanXuLy->count() }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($benhAnCanXuLy->isEmpty())
                            <x-empty-state
                                icon="bi-check-circle"
                                title="Không có bệnh án cần xử lý"
                                description="Tất cả bệnh án đã hoàn thành đều có hóa đơn!"
                            />
                        @else
                            <div class="list-group list-group-flush">
                                @foreach ($benhAnCanXuLy as $ba)
                                    <div class="list-group-item px-0">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">
                                                    <i class="bi bi-person-circle text-primary me-1"></i>
                                                    {{ $ba->benhNhan->name ?? 'N/A' }}
                                                </h6>
                                                <p class="mb-1 small text-muted">
                                                    <i class="bi bi-calendar3 me-1"></i>{{ $ba->ngay_kham }}
                                                    <span class="mx-2">•</span>
                                                    <i class="bi bi-person-badge me-1"></i>{{ $ba->bacSi->ten ?? 'N/A' }}
                                                </p>
                                                <p class="mb-0 small">
                                                    <span class="badge bg-success">Hoàn thành khám</span>
                                                </p>
                                            </div>
                                            <div class="btn-group">
                                                <a href="{{ route('staff.benhan.toa-thuoc', $ba) }}" 
                                                   class="btn btn-sm btn-info" title="Xem toa thuốc">
                                                    <i class="bi bi-prescription2"></i> Toa
                                                </a>
                                                <a href="{{ route('staff.hoadon.create-from-benh-an', $ba) }}" 
                                                   class="btn btn-sm btn-warning" title="Tạo hóa đơn">
                                                    <i class="bi bi-receipt"></i> HĐ
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if($statistics['benh_an_can_tao_hoa_don'] > 5)
                                <div class="text-center mt-3">
                                    <a href="{{ route('admin.benhan.index') }}" class="btn btn-sm btn-outline-primary">
                                        Xem tất cả {{ $statistics['benh_an_can_tao_hoa_don'] }} bệnh án
                                    </a>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

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
        </div>

        {{-- Quick Actions --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 pt-4 pb-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-lightning-charge text-warning me-2"></i>Thao tác nhanh
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <a href="{{ route('staff.checkin.index') }}" class="btn btn-outline-primary w-100 py-3">
                                    <i class="bi bi-person-check-fill d-block fs-3 mb-2"></i>
                                    Check-in bệnh nhân
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('staff.hoadon.index') }}" class="btn btn-outline-success w-100 py-3">
                                    <i class="bi bi-receipt d-block fs-3 mb-2"></i>
                                    Quản lý hóa đơn
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('admin.benhan.index') }}" class="btn btn-outline-info w-100 py-3">
                                    <i class="bi bi-journal-medical d-block fs-3 mb-2"></i>
                                    Danh sách bệnh án
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('staff.queue.index') }}" class="btn btn-outline-warning w-100 py-3">
                                    <i class="bi bi-list-ol d-block fs-3 mb-2"></i>
                                    Hàng đợi khám
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Thông tin cá nhân --}}
        <div class="row mb-4">
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
                                        <strong>{{ $currentRoleLabel }}</strong>
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
