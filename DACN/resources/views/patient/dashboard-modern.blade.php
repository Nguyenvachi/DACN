@extends('layouts.patient-modern')

@section('title', 'Dashboard Bệnh Nhân')

@section('content')
    <div class="container-fluid px-4">

        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm welcome-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">

                            <div>
                                <h2 class="welcome-title mb-2">
                                    <span class="wave-emoji">👋</span> Xin chào, {{ auth()->user()->name }}!
                                </h2>
                                <p class="welcome-text mb-0">Chào mừng bạn quay trở lại hệ thống quản lý sức khỏe</p>
                            </div>

                            <div class="text-end d-none d-md-block">
                                <div class="welcome-date fs-5">{{ now()->format('d/m/Y') }}</div>
                                <div class="welcome-day">{{ now()->locale('vi')->isoFormat('dddd') }}</div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        {{-- ENHANCED: Modern gradient stat cards (Parent: patient/dashboard-modern.blade.php) --}}
        <div class="row mb-4 g-4">

            <div class="col-lg-3 col-md-6">
                <x-patient.stat-card title="Lịch Hẹn Sắp Tới" :value="$statistics['upcoming_appointments']" icon="fa-calendar-check" color="primary"
                    :route="route('patient.lichhen.index')" />
            </div>

            <div class="col-lg-3 col-md-6">
                <x-patient.stat-card title="Hồ Sơ Bệnh Án" :value="$statistics['total_medical_records']" icon="fa-file-medical" color="success"
                    :route="route('patient.benhan.index')" />
            </div>

            <div class="col-lg-3 col-md-6">
                <x-patient.stat-card title="Hóa Đơn Chưa Thanh Toán" :value="$statistics['unpaid_invoices']" icon="fa-file-invoice-dollar"
                    color="warning" :route="route('patient.hoadon.index')" />
            </div>

            <div class="col-lg-3 col-md-6">
                <x-patient.stat-card title="Xét Nghiệm" :value="$statistics['total_tests']" icon="fa-flask" color="info"
                    :route="route('patient.xetnghiem.index')" />
            </div>

            <div class="col-lg-3 col-md-6">
                <x-patient.stat-card title="Siêu Âm" :value="($statistics['total_ultrasounds'] ?? $statistics['total_tests'])" icon="fa-flask" color="info"
                    :route="route('patient.sieuam.index')" />
            </div>

            <div class="col-lg-3 col-md-6">
                <x-patient.stat-card title="X-Quang" :value="($statistics['total_xquangs'] ?? 0)" icon="fa-x-ray" color="info"
                    :route="route('patient.xquang.index')" />
            </div>

            <div class="col-lg-3 col-md-6">
                <x-patient.stat-card title="Nội soi" :value="($statistics['total_noisois'] ?? 0)" icon="fa-stethoscope" color="info"
                    :route="route('patient.noisoi.index')" />
            </div>

        </div>

        <!-- Main Content -->
        <div class="row">

            <!-- LEFT COLUMN -->
            <div class="col-lg-8 mb-4">

                <!-- Upcoming Appointments -->
                <div class="card border-0 shadow-sm mb-4 section-card">
                    <div class="card-header section-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="section-title">
                                <i class="fas fa-calendar-alt text-primary me-2"></i>Lịch Hẹn Sắp Tới
                            </h5>
                            <a href="{{ route('patient.lichhen.index') }}" class="btn btn-sm btn-outline-primary">Xem Tất
                                Cả</a>
                        </div>
                    </div>

                    <div class="card-body">
                        @forelse($upcomingAppointments as $appointment)
                            <div
                                class="d-flex align-items-center list-item-hover p-3 mb-2 border-start border-4 border-primary bg-light rounded">
                                <div class="flex-shrink-0 text-center">
                                    <div class="fw-bold text-primary fs-4">
                                        {{ \Carbon\Carbon::parse($appointment->ngay_hen)->format('d') }}
                                    </div>
                                    <div class="text-muted small">
                                        {{ \Carbon\Carbon::parse($appointment->ngay_hen)->format('M') }}</div>
                                </div>

                                <div class="flex-grow-1 ms-3">
                                    <div class="fw-semibold">{{ $appointment->bacSi->ho_ten }}</div>

                                    <div class="text-muted small">
                                        <i class="fas fa-clock me-1"></i>{{ $appointment->thoi_gian_hen }}
                                        |
                                        <i class="fas fa-stethoscope me-1"></i>{{ $appointment->dichVu->ten_dich_vu }}
                                    </div>
                                </div>

                                <div class="text-end">
                                    <x-appointment-status-badge :status="$appointment->trang_thai" class="px-3 py-2" />
                                </div>
                            </div>

                        @empty
                            <div class="text-center py-5 text-muted empty-block">
                                <i class="fas fa-calendar-times fa-3x mb-3 opacity-50"></i>
                                <p>Bạn chưa có lịch hẹn nào sắp tới</p>
                                <a href="{{ route('public.bacsi.index') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-plus me-2"></i>Đặt Lịch Ngay
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Appointment Chart -->
                <div class="card border-0 shadow-sm mb-4 chart-card">
                    <div class="card-header section-header">
                        <h5 class="section-title">
                            <i class="fas fa-chart-line text-success me-2"></i>Thống Kê Lịch Hẹn 6 Tháng
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="appointmentChart" height="80"></canvas>
                    </div>
                </div>

                <!-- Recent Medical Records -->
                <div class="card border-0 shadow-sm mb-4 section-card">
                    <div class="card-header section-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="section-title">
                                <i class="fas fa-notes-medical text-success me-2"></i>Bệnh Án Gần Đây
                            </h5>
                            <a href="{{ route('patient.benhan.index') }}" class="btn btn-sm btn-outline-success">Xem Tất
                                Cả</a>
                        </div>
                    </div>

                    <div class="card-body p-0">

                        @forelse($recentMedicalRecords as $record)
                            <div class="p-3 border-bottom list-item-hover">
                                <div class="d-flex align-items-start">

                                    <div class="flex-shrink-0">
                                        <div class="avatar rounded-circle bg-success bg-opacity-10 p-2">
                                            <i class="fas fa-stethoscope text-success"></i>
                                        </div>
                                    </div>

                                    <div class="flex-grow-1 ms-3">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="mb-1">{{ $record->dichVu->ten_dich_vu ?? 'Khám tổng quát' }}
                                                </h6>
                                                <p class="mb-1 text-muted small">BS. {{ $record->bacSi->ho_ten ?? 'N/A' }}
                                                </p>
                                                <p class="mb-0 small text-truncate">
                                                    {{ Str::limit($record->chuan_doan ?? 'Chưa có chẩn đoán', 100) }}</p>
                                            </div>
                                            <div class="text-end ms-3">
                                                <small class="text-muted d-block mb-2">
                                                    {{ \Carbon\Carbon::parse($record->ngay_kham)->format('d/m/Y') }}
                                                </small>
                                                <a href="{{ route('patient.benhan.show', $record->id) }}"
                                                    class="btn btn-sm btn-outline-success">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>

                        @empty
                            <div class="text-center py-4 text-muted empty-block">
                                <i class="fas fa-notes-medical fa-2x opacity-50"></i>
                                <p>Chưa có bệnh án nào</p>
                            </div>
                        @endforelse

                    </div>
                </div>

                <!-- Recent Tests -->
                @if ($recentTests->count() > 0)
                    <div class="card border-0 shadow-sm mb-4 section-card">
                        <div class="card-header section-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="section-title">
                                    <i class="fas fa-flask text-info me-2"></i>Xét Nghiệm Gần Đây
                                </h5>
                                <a href="{{ route('patient.xetnghiem.index') }}" class="btn btn-sm btn-outline-info">Xem
                                    Tất Cả</a>
                            </div>
                        </div>

                        <div class="card-body">
                            @foreach ($recentTests as $test)
                                <div class="d-flex align-items-center p-3 mb-2 bg-light rounded list-item-hover">
                                    <i class="fas fa-vial text-info fa-2x me-3"></i>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold">{{ $test->loai }}</div>
                                        <small class="text-muted">{{ $test->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                    <a href="{{ route('patient.xetnghiem.show', $test->id) }}"
                                        class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- My Reviews -->
                @if ($myReviews->count() > 0)
                    <div class="card border-0 shadow-sm section-card">
                        <div class="card-header section-header">
                            <div class="d-flex justify-content-between">
                                <h5 class="section-title"><i class="fas fa-star text-warning me-2"></i>Đánh Giá Của Tôi
                                </h5>
                                <a href="{{ route('patient.danhgia.index') }}" class="btn btn-sm btn-outline-warning">Xem
                                    Tất Cả</a>
                            </div>
                        </div>

                        <div class="card-body">
                            @foreach ($myReviews as $review)
                                <div class="p-3 mb-2 border rounded list-item-hover">
                                    <div class="d-flex justify-content-between">

                                        <div class="fw-semibold">BS. {{ $review->bacSi->ho_ten }}</div>

                                        <div>
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i
                                                    class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star text-warning"></i>
                                            @endfor
                                        </div>

                                    </div>

                                    <p class="mb-1 small text-muted">{{ Str::limit($review->noi_dung, 80) }}</p>
                                    <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            <!-- RIGHT COLUMN -->
            <div class="col-lg-4 mb-4">

                <!-- Health Stats -->
                @if ($profile)
                    <div class="card border-0 shadow-sm mb-4 section-card">
                        <div class="card-header section-header">
                            <h5 class="section-title">
                                <i class="fas fa-heartbeat text-danger me-2"></i>Chỉ Số Sức Khỏe
                            </h5>
                        </div>

                        <div class="card-body">
                            <div class="row g-3">

                                <div class="col-6">
                                    <div class="health-box text-center">
                                        <i class="fas fa-weight fa-2x text-primary mb-2"></i>
                                        <div class="fw-bold">{{ $healthStats['weight'] ?? '--' }} kg</div>
                                        <small class="text-muted">Cân nặng</small>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="health-box text-center">
                                        <i class="fas fa-ruler-vertical fa-2x text-success mb-2"></i>
                                        <div class="fw-bold">{{ $healthStats['height'] ?? '--' }} cm</div>
                                        <small class="text-muted">Chiều cao</small>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="health-box text-center">
                                        <i class="fas fa-calculator fa-2x text-warning mb-2"></i>
                                        <div class="fw-bold">{{ number_format($healthStats['bmi'] ?? 0, 1) }}</div>
                                        <small class="text-muted">BMI</small>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="health-box text-center">
                                        <i class="fas fa-tint fa-2x text-danger mb-2"></i>
                                        <div class="fw-bold">{{ $healthStats['blood_type'] ?? '--' }}</div>
                                        <small class="text-muted">Nhóm máu</small>
                                    </div>
                                </div>

                            </div>

                            @if ($healthStats['bmi'])
                                <div class="mt-3 bmi-box">
                                    <h6 class="mb-2"><i class="fas fa-info-circle me-2"></i>Phân Loại BMI</h6>
                                    <p class="mb-0 fw-semibold">{{ $healthStats['bmi_category'] }}</p>
                                </div>
                            @endif

                            @if ($healthStats['allergies_count'] > 0)
                                <div class="alert alert-warning mt-3 mb-0">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Bạn có {{ $healthStats['allergies_count'] }} dị ứng đã ghi nhận
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Unpaid Invoices -->
                @if ($unpaidInvoices->count() > 0)
                    <div class="card border-0 shadow-sm mb-4 invoice-card section-card">
                        <div class="card-header bg-warning bg-opacity-10 border-0 py-3">
                            <h5 class="section-title">
                                <i class="fas fa-exclamation-circle text-warning me-2"></i>Hóa Đơn Cần Thanh Toán
                            </h5>
                        </div>

                        <div class="card-body">
                            @foreach ($unpaidInvoices as $invoice)
                                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                    <div>
                                        <div class="fw-semibold">#{{ $invoice->ma_hoa_don ?? $invoice->id }}</div>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($invoice->created_at)->format('d/m/Y') }}
                                        </small>
                                    </div>

                                    <div class="text-end">
                                        <div class="price text-warning">{{ number_format($invoice->tong_tien) }}đ</div>

                                        <a href="{{ route('patient.hoadon.show', $invoice->id) }}"
                                            class="btn btn-sm btn-warning mt-1">
                                            Thanh toán
                                        </a>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Quick Actions -->
                <div class="card border-0 shadow-sm mb-4 section-card">
                    <div class="card-header section-header">
                        <h5 class="section-title">
                            <i class="fas fa-bolt text-warning me-2"></i>Thao Tác Nhanh
                        </h5>
                    </div>

                    <div class="card-body quick-actions">
                        <div class="d-grid gap-2">

                            <a href="{{ route('public.bacsi.index') }}" class="btn btn-primary">
                                <i class="fas fa-calendar-plus me-2"></i>Đặt Lịch Khám
                            </a>

                            <a href="{{ route('patient.benhan.index') }}" class="btn btn-outline-success">
                                <i class="fas fa-file-medical me-2"></i>Xem Bệnh Án
                            </a>

                            <a href="{{ route('patient.donthuoc.index') }}" class="btn btn-outline-info">
                                <i class="fas fa-pills me-2"></i>Đơn Thuốc
                            </a>

                            <a href="{{ route('patient.hoadon.index') }}" class="btn btn-outline-warning">
                                <i class="fas fa-file-invoice-dollar me-2"></i>Hóa Đơn
                            </a>

                            <a href="{{ route('patient.shop.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-shopping-cart me-2"></i>Mua Thuốc Online
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Notifications -->
                <div class="card border-0 shadow-sm section-card">
                    <div class="card-header section-header">
                        <div class="d-flex justify-content-between">
                            <h5 class="section-title"><i class="fas fa-bell text-info me-2"></i>Thông Báo Mới</h5>
                            <a href="{{ route('patient.notifications') }}" class="btn btn-sm btn-outline-info">Xem Tất
                                Cả</a>
                        </div>
                    </div>

                    <div class="card-body p-0">

                        @forelse($unreadNotifications as $notification)
                            <div class="p-3 border-bottom bg-light notification-item">
                                <div class="d-flex align-items-start">

                                    <i class="fas fa-circle text-primary me-2 mt-1" style="font-size: 8px;"></i>

                                    <div class="flex-grow-1">
                                        <div class="small">
                                            {{ $notification->data['message'] ?? 'Thông báo mới' }}
                                        </div>

                                        <div class="text-muted small">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </div>
                                    </div>

                                </div>
                            </div>

                        @empty
                            <div class="text-center py-4 text-muted empty-block">
                                <i class="fas fa-bell-slash opacity-50"></i>
                                <p class="mb-0">Chưa có thông báo</p>
                            </div>
                        @endforelse

                    </div>
                </div>

            </div>
        </div>
    </div>


    @push('styles')
        <style>
            /* ======================================================
                                        VIETCARE PATIENT DASHBOARD — PREMIUM MEDICAL UI 2025
                                    ======================================================= */

            /* ----------------------------------------
                                        WELCOME CARD
                                    ---------------------------------------- */
            .welcome-card {
                background: linear-gradient(135deg, #10b981, #059669);
                border-radius: 22px;
                padding: 1.8rem 2rem;
                box-shadow: var(--vc-shadow-lg);
                position: relative;
                overflow: hidden;
            }

            .welcome-card::before,
            .welcome-card::after {
                content: "";
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, .15);
                filter: blur(18px);
            }

            .welcome-card::before {
                width: 260px;
                height: 260px;
                top: -20%;
                right: -10%;
            }

            .welcome-card::after {
                width: 200px;
                height: 200px;
                bottom: -18%;
                left: -15%;
                opacity: .6;
            }

            .welcome-title {
                font-size: 2rem;
                font-weight: 800;
            }

            .welcome-text {
                font-size: 1.05rem;
                opacity: .95;
            }

            .wave-emoji {
                display: inline-block;
                animation: wave 1.4s infinite ease-in-out;
                transform-origin: 70% 70%;
            }

            @keyframes wave {
                0% {
                    transform: rotate(0);
                }

                25% {
                    transform: rotate(15deg);
                }

                50% {
                    transform: rotate(-10deg);
                }

                75% {
                    transform: rotate(12deg);
                }

                100% {
                    transform: rotate(0);
                }
            }


            /* ----------------------------------------
                                        STAT CARDS
                                    ---------------------------------------- */
            .stat-card {
                border-radius: 18px !important;
                transition: .25s ease;
                box-shadow: var(--vc-shadow-sm);
                overflow: hidden;
            }

            .stat-card:hover {
                transform: translateY(-6px);
                box-shadow: var(--vc-shadow-md);
            }


            /* ----------------------------------------
                                        SECTION CARD
                                    ---------------------------------------- */
            .section-card {
                border-radius: 20px !important;
                box-shadow: var(--vc-shadow-sm);
                overflow: hidden;
            }

            .section-header {
                background: #ffffff;
                padding: 1rem 1.1rem !important;
                border-bottom: 1px solid #f1f5f9 !important;
            }

            .section-title {
                margin: 0;
                font-weight: 700;
                font-size: 1.1rem;
                display: flex;
                align-items: center;
                gap: 6px;
            }


            /* ----------------------------------------
                                        LIST ELEMENTS — hover styles
                                    ---------------------------------------- */
            .list-item-hover {
                border-radius: 14px;
                transition: .25s ease;
                background: #fff;
            }

            .list-item-hover:hover {
                background: #f8fafc;
                transform: translateY(-3px);
            }


            /* ----------------------------------------
                                        HEALTH BOXES
                                    ---------------------------------------- */
            .health-box {
                padding: 1rem;
                border-radius: 14px;
                background: #f9fafb;
                transition: .25s ease;
                box-shadow: var(--vc-shadow-sm);
            }

            .health-box:hover {
                background: #eef2ff;
                transform: translateY(-3px);
            }

            .bmi-box {
                background: #e0f2fe;
                padding: 12px;
                border-radius: 12px;
                font-weight: 600;
            }


            /* ----------------------------------------
                                        INVOICE CARD
                                    ---------------------------------------- */
            .invoice-card .price {
                font-size: 1.3rem;
                font-weight: 800;
                color: #eab308;
            }

            .invoice-card .btn-warning {
                border-radius: 10px;
                font-weight: 600;
            }


            /* ----------------------------------------
                                        QUICK ACTIONS
                                    ---------------------------------------- */
            .quick-actions .btn {
                border-radius: 14px;
                padding: 11px 16px;
                font-weight: 600;
                transition: .25s ease;
                box-shadow: var(--vc-shadow-sm);
            }

            .quick-actions .btn:hover {
                transform: translateY(-3px);
                box-shadow: var(--vc-shadow-md);
            }


            /* ----------------------------------------
                                        NOTIFICATIONS
                                    ---------------------------------------- */
            .notification-item {
                transition: .25s ease;
            }

            .notification-item:hover {
                background: #eef6ff !important;
            }


            /* ----------------------------------------
                                        CHART CARD
                                    ---------------------------------------- */
            .chart-card {
                border-radius: 20px !important;
                box-shadow: var(--vc-shadow-sm);
            }

            #appointmentChart {
                max-height: 300px;
            }


            /* ----------------------------------------
                                        EMPTY BLOCKS
                                    ---------------------------------------- */
            .empty-block i {
                opacity: .4;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {

                const ctx = document.getElementById("appointmentChart");

                if (!ctx) return;

                new Chart(ctx, {
                    type: "line",
                    data: {
                        labels: @json($appointmentChartData['labels']),
                        datasets: [{
                                label: "Hoàn thành",
                                data: @json($appointmentChartData['completed']),
                                borderColor: "#10b981",
                                backgroundColor: "rgba(16,185,129,.15)",
                                tension: .4,
                                fill: true
                            },
                            {
                                label: "Đã hủy",
                                data: @json($appointmentChartData['cancelled']),
                                borderColor: "#ef4444",
                                backgroundColor: "rgba(239,68,68,.15)",
                                tension: .4,
                                fill: true
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: "top"
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });

            });
        </script>
    @endpush

@endsection
