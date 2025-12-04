@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">

        {{-- ============================
         🔥 HEADER
    ============================= --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard Thống kê
            </h2>
        </div>


        {{-- ============================
         🔥 BỘ LỌC NGÀY
    ============================= --}} 
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <form id="rangeForm" class="row g-3 align-items-end">

                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">Từ ngày</label>
                        <input type="date" name="from" class="form-control" value="{{ $summary['from'] }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">Đến ngày</label>
                        <input type="date" name="to" class="form-control" value="{{ $summary['to'] }}">
                    </div>

                    <div class="col-md-6 d-flex align-items-end gap-2 flex-wrap">

                        {{-- Quick Range Buttons --}}
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-range="7">7 ngày</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-range="30">30 ngày</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-range="90">3 tháng</button>
                        </div>

                        <button class="btn btn-primary btn-sm px-3" type="submit">
                            <i class="bi bi-funnel me-1"></i>Lọc
                        </button>

                        {{-- Nút thủ công gửi nhắc lịch – giữ nguyên logic cũ --}}
                        <form action="{{ route('admin.tools.reminders.tomorrow') }}" method="POST">
                            @csrf
                            <input type="hidden" name="force" value="1">
                            <button type="submit" class="btn btn-warning btn-sm">
                                <i class="bi bi-bell me-1"></i>Nhắc lịch ngày mai
                            </button>
                        </form>

                        <form action="{{ route('admin.tools.reminders.next3h') }}" method="POST">
                            @csrf
                            <input type="hidden" name="force" value="1">
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="bi bi-alarm me-1"></i>Nhắc lịch 3 giờ tới
                            </button>
                        </form>

                        <a href="{{ route('admin.tools.test-mail') }}" class="btn btn-info btn-sm">
                            <i class="bi bi-envelope-check me-1"></i>Test Mail
                        </a>
                    </div>

                </form>
            </div>
        </div>


        {{-- ============================
         🔥 KPI CARDS (MỞ RỘNG: Hiển thị % thay đổi thực - Parent: resources/views/admin/dashboard/index.blade.php)
    ============================= --}}
        <div class="row g-4 mb-4">

            {{-- Tổng lịch hẹn --}}
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 card-hover">
                    <div class="card-body d-flex">
                        <div class="avatar bg-primary bg-opacity-10 text-primary me-3 rounded-3">
                            <i class="bi bi-calendar-check fs-3"></i>
                        </div>
                        <div>
                            <h6 class="text-muted small mb-1">Tổng lịch hẹn</h6>
                            <h3 class="fw-bold">{{ number_format($summary['appointments']) }}</h3>
                            @php
                                $change = $comparison['changes']['appointments'] ?? 0;
                                $color = $change >= 0 ? 'success' : 'danger';
                                $icon = $change >= 0 ? 'arrow-up' : 'arrow-down';
                            @endphp
                            <small class="text-{{ $color }}">
                                <i class="bi bi-{{ $icon }}"></i> {{ abs($change) }}% so với kỳ trước
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Doanh thu --}}
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 card-hover">
                    <div class="card-body d-flex">
                        <div class="avatar bg-success bg-opacity-10 text-success me-3 rounded-3">
                            <i class="bi bi-currency-dollar fs-3"></i>
                        </div>
                        <div>
                            <h6 class="text-muted small mb-1">Doanh thu</h6>
                            <h3 class="fw-bold">{{ number_format($summary['revenue'], 0, ',', '.') }}đ</h3>
                            @php
                                $change = $comparison['changes']['revenue'] ?? 0;
                                $color = $change >= 0 ? 'success' : 'danger';
                                $icon = $change >= 0 ? 'arrow-up' : 'arrow-down';
                            @endphp
                            <small class="text-{{ $color }}">
                                <i class="bi bi-{{ $icon }}"></i> {{ abs($change) }}% so với kỳ trước
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Hóa đơn --}}
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 card-hover">
                    <div class="card-body d-flex">
                        <div class="avatar bg-info bg-opacity-10 text-info me-3 rounded-3">
                            <i class="bi bi-receipt fs-3"></i>
                        </div>
                        <div>
                            <h6 class="text-muted small mb-1">Hóa đơn đã thanh toán</h6>
                            <h3 class="fw-bold">{{ number_format($summary['paid_invoices']) }}</h3>
                            @php
                                $change = $comparison['changes']['paid_invoices'] ?? 0;
                                $color = $change >= 0 ? 'success' : 'danger';
                                $icon = $change >= 0 ? 'arrow-up' : 'arrow-down';
                            @endphp
                            <small class="text-{{ $color }}">
                                <i class="bi bi-{{ $icon }}"></i> {{ abs($change) }}% so với kỳ trước
                            </small>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        {{-- ============================
         🔥 CHARTS LAYER 1
    ============================= --}}
        <div class="row g-4 mb-4">

            {{-- Pie: trạng thái lịch hẹn --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="fw-semibold"><i class="bi bi-pie-chart me-2 text-primary"></i>Trạng thái lịch hẹn
                            </h5>
                            <div id="statusLoader" class="spinner-border spinner-border-sm text-primary d-none"></div>
                        </div>
                        <canvas id="statusChart" height="180"></canvas>
                    </div>
                </div>
            </div>

            {{-- Line: lịch từng ngày --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="fw-semibold"><i class="bi bi-graph-up me-2 text-primary"></i>Lịch theo ngày</h5>
                            <div id="dailyLoader" class="spinner-border spinner-border-sm text-primary d-none"></div>
                        </div>
                        <canvas id="dailyChart" height="180"></canvas>
                    </div>
                </div>
            </div>

        </div>


        {{-- ============================
         🔥 CHARTS LAYER 2
    ============================= --}}
        <div class="row g-4 mb-4">

            {{-- Revenue theo dịch vụ --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="fw-semibold"><i class="bi bi-bar-chart me-2 text-success"></i>Doanh thu theo dịch
                                vụ</h5>
                            <div id="serviceLoader" class="spinner-border spinner-border-sm text-primary d-none"></div>
                        </div>
                        <canvas id="serviceChart" height="180"></canvas>
                    </div>
                </div>
            </div>

            {{-- Revenue theo bác sĩ --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="fw-semibold"><i class="bi bi-person-badge me-2 text-info"></i>Doanh thu theo bác sĩ
                            </h5>
                            <div id="doctorLoader" class="spinner-border spinner-border-sm text-primary d-none"></div>
                        </div>
                        <canvas id="doctorChart" height="180"></canvas>
                    </div>
                </div>
            </div>

        </div>


        {{-- ============================
         🔥 CHARTS LAYER 3
    ============================= --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="fw-semibold"><i class="bi bi-credit-card me-2 text-warning"></i>Doanh thu theo cổng thanh
                        toán</h5>
                    <div id="gatewayLoader" class="spinner-border spinner-border-sm text-primary d-none"></div>
                </div>
                <canvas id="gatewayChart" height="120"></canvas>
            </div>
        </div>


        {{-- ============================
         🔥 CHARTS LAYER 4: MỞ RỘNG (Parent file: resources/views/admin/dashboard/index.blade.php)
    ============================= --}}
        <div class="row g-4 mb-4">

            {{-- Bệnh nhân mới theo tháng --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="fw-semibold"><i class="bi bi-person-plus me-2 text-primary"></i>Bệnh nhân mới theo tháng
                            </h5>
                            <div id="newPatientsLoader" class="spinner-border spinner-border-sm text-primary d-none"></div>
                        </div>
                        <canvas id="newPatientsChart" height="180"></canvas>
                    </div>
                </div>
            </div>

            {{-- Top dịch vụ được sử dụng nhiều nhất --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="fw-semibold"><i class="bi bi-trophy me-2 text-warning"></i>Top 10 dịch vụ phổ biến
                            </h5>
                            <div id="topServicesLoader" class="spinner-border spinner-border-sm text-primary d-none"></div>
                        </div>
                        <canvas id="topServicesChart" height="180"></canvas>
                    </div>
                </div>
            </div>

        </div>


        {{-- ============================
         🔥 CHARTS LAYER 5: Thuốc & Hoàn tiền (Parent file: resources/views/admin/dashboard/index.blade.php)
    ============================= --}}
        <div class="row g-4 mb-4">

            {{-- Thống kê hoàn tiền --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="fw-semibold"><i class="bi bi-arrow-return-left me-2 text-danger"></i>Thống kê hoàn tiền
                            </h5>
                            <div id="refundsLoader" class="spinner-border spinner-border-sm text-primary d-none"></div>
                        </div>
                        <div id="refundsStats">
                            <div class="row text-center">
                                <div class="col-4">
                                    <h6 class="text-muted small">Tổng số lượt</h6>
                                    <h4 class="fw-bold" id="refundCount">-</h4>
                                </div>
                                <div class="col-4">
                                    <h6 class="text-muted small">Tổng tiền</h6>
                                    <h4 class="fw-bold text-danger" id="refundAmount">-</h4>
                                </div>
                                <div class="col-4">
                                    <h6 class="text-muted small">Trung bình</h6>
                                    <h4 class="fw-bold" id="refundAvg">-</h4>
                                </div>
                            </div>
                            <hr>
                            <canvas id="refundsChart" height="150"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Top thuốc bán chạy --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="fw-semibold"><i class="bi bi-capsule me-2 text-success"></i>Top 10 thuốc bán chạy
                            </h5>
                            <div id="medicinesLoader" class="spinner-border spinner-border-sm text-primary d-none"></div>
                        </div>
                        <div class="mb-3 text-center">
                            <h6 class="text-muted small">Tổng doanh thu thuốc</h6>
                            <h3 class="fw-bold text-success" id="medicineTotalRevenue">-</h3>
                        </div>
                        <canvas id="medicinesChart" height="180"></canvas>
                    </div>
                </div>
            </div>

        </div>


        {{-- ============================
         🔥 EXPORT CSV SECTION (Parent file: resources/views/admin/dashboard/index.blade.php)
    ============================= --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="fw-semibold mb-3"><i class="bi bi-download me-2 text-primary"></i>Xuất báo cáo CSV</h5>
                <div class="d-flex flex-wrap gap-2">
                    <a href="#" class="btn btn-outline-primary btn-sm" data-export="summary">
                        <i class="bi bi-file-earmark-text me-1"></i>Tổng hợp
                    </a>
                    <a href="#" class="btn btn-outline-success btn-sm" data-export="appointments">
                        <i class="bi bi-calendar-check me-1"></i>Lịch hẹn
                    </a>
                    <a href="#" class="btn btn-outline-info btn-sm" data-export="revenue_service">
                        <i class="bi bi-bar-chart me-1"></i>Doanh thu dịch vụ
                    </a>
                    <a href="#" class="btn btn-outline-warning btn-sm" data-export="revenue_doctor">
                        <i class="bi bi-person-badge me-1"></i>Doanh thu bác sĩ
                    </a>
                    <a href="#" class="btn btn-outline-danger btn-sm" data-export="medicines">
                        <i class="bi bi-capsule me-1"></i>Thuốc
                    </a>
                    <a href="#" class="btn btn-outline-secondary btn-sm" data-export="refunds">
                        <i class="bi bi-arrow-return-left me-1"></i>Hoàn tiền
                    </a>
                    <a href="#" class="btn btn-outline-dark btn-sm" data-export="new_patients">
                        <i class="bi bi-person-plus me-1"></i>Bệnh nhân mới
                    </a>
                </div>
            </div>
        </div>

    </div>


    {{-- ============================
     🔥 CSS riêng cho Dashboard
============================ --}}
    @push('styles')
        <style>
            .card-hover {
                transition: 0.25s;
            }

            .card-hover:hover {
                transform: translateY(-4px);
            }

            .avatar {
                width: 58px;
                height: 58px;
                display: flex;
                justify-content: center;
                align-items: center;
                border-radius: 12px;
            }
        </style>
    @endpush

    {{-- ============================
     🔥 SCRIPT BIỂU ĐỒ + LỌC
============================ --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            (function() {
                const form = document.getElementById('rangeForm');
                const fromInput = form.querySelector('[name="from"]');
                const toInput = form.querySelector('[name="to"]');

                // Quick date range buttons
                document.querySelectorAll('[data-range]').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const days = parseInt(this.dataset.range);
                        const to = new Date();
                        const from = new Date();
                        from.setDate(from.getDate() - days);

                        toInput.value = to.toISOString().split('T')[0];
                        fromInput.value = from.toISOString().split('T')[0];
                        form.dispatchEvent(new Event('submit'));
                    });
                });

                const qs = () => {
                    const fd = new FormData(form);
                    return '?' + new URLSearchParams(fd).toString();
                };

                const showLoader = (id) => document.getElementById(id).classList.remove('d-none');
                const hideLoader = (id) => document.getElementById(id).classList.add('d-none');

                const colors = {
                    primary: '#0d6efd',
                    success: '#198754',
                    info: '#0dcaf0',
                    warning: '#ffc107',
                    danger: '#dc3545',
                    purple: '#6f42c1',
                    pink: '#d63384',
                    orange: '#fd7e14'
                };

                const chartColors = [colors.primary, colors.success, colors.info, colors.warning, colors.danger, colors
                    .purple, colors.pink, colors.orange
                ];

                const defaultOptions = {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                font: {
                                    size: 12
                                }
                            }
                        }
                    },
                    animation: {
                        duration: 1000,
                        easing: 'easeInOutQuart'
                    }
                };

                const ctxStatus = new Chart(document.getElementById('statusChart'), {
                    type: 'doughnut',
                    data: {
                        labels: [],
                        datasets: [{
                            data: [],
                            backgroundColor: chartColors,
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        ...defaultOptions,
                        cutout: '60%'
                    }
                });

                const ctxDaily = new Chart(document.getElementById('dailyChart'), {
                    type: 'line',
                    data: {
                        labels: [],
                        datasets: [{
                            label: 'Lịch hẹn',
                            data: [],
                            borderColor: colors.primary,
                            backgroundColor: colors.primary + '20',
                            tension: 0.4,
                            fill: true,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: defaultOptions
                });

                const ctxService = new Chart(document.getElementById('serviceChart'), {
                    type: 'bar',
                    data: {
                        labels: [],
                        datasets: [{
                            label: 'Doanh thu (đ)',
                            data: [],
                            backgroundColor: colors.success,
                            borderRadius: 8,
                            borderSkipped: false
                        }]
                    },
                    options: {
                        ...defaultOptions,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: v => v.toLocaleString('vi-VN') + 'đ'
                                }
                            }
                        }
                    }
                });

                const ctxDoctor = new Chart(document.getElementById('doctorChart'), {
                    type: 'bar',
                    data: {
                        labels: [],
                        datasets: [{
                            label: 'Doanh thu (đ)',
                            data: [],
                            backgroundColor: colors.info,
                            borderRadius: 8,
                            borderSkipped: false
                        }]
                    },
                    options: {
                        ...defaultOptions,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: v => v.toLocaleString('vi-VN') + 'đ'
                                }
                            }
                        }
                    }
                });

                const ctxGateway = new Chart(document.getElementById('gatewayChart'), {
                    type: 'bar',
                    data: {
                        labels: [],
                        datasets: [{
                            label: 'Doanh thu (đ)',
                            data: [],
                            backgroundColor: colors.warning,
                            borderRadius: 8,
                            borderSkipped: false
                        }]
                    },
                    options: {
                        ...defaultOptions,
                        indexAxis: 'y',
                        scales: {
                            x: {
                                beginAtZero: true,
                                ticks: {
                                    callback: v => v.toLocaleString('vi-VN') + 'đ'
                                }
                            }
                        }
                    }
                });

                // ==================== MỞ RỘNG: CHARTS MỚI (Parent: resources/views/admin/dashboard/index.blade.php) ====================

                const ctxNewPatients = new Chart(document.getElementById('newPatientsChart'), {
                    type: 'line',
                    data: {
                        labels: [],
                        datasets: [{
                            label: 'Bệnh nhân mới',
                            data: [],
                            borderColor: colors.primary,
                            backgroundColor: colors.primary + '20',
                            tension: 0.4,
                            fill: true,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: defaultOptions
                });

                const ctxTopServices = new Chart(document.getElementById('topServicesChart'), {
                    type: 'bar',
                    data: {
                        labels: [],
                        datasets: [{
                            label: 'Số lượt sử dụng',
                            data: [],
                            backgroundColor: colors.warning,
                            borderRadius: 8,
                            borderSkipped: false
                        }]
                    },
                    options: {
                        ...defaultOptions,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

                const ctxRefunds = new Chart(document.getElementById('refundsChart'), {
                    type: 'pie',
                    data: {
                        labels: [],
                        datasets: [{
                            data: [],
                            backgroundColor: chartColors,
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: defaultOptions
                });

                const ctxMedicines = new Chart(document.getElementById('medicinesChart'), {
                    type: 'bar',
                    data: {
                        labels: [],
                        datasets: [{
                            label: 'Doanh thu (đ)',
                            data: [],
                            backgroundColor: colors.success,
                            borderRadius: 8,
                            borderSkipped: false
                        }]
                    },
                    options: {
                        ...defaultOptions,
                        indexAxis: 'y',
                        scales: {
                            x: {
                                beginAtZero: true,
                                ticks: {
                                    callback: v => v.toLocaleString('vi-VN') + 'đ'
                                }
                            }
                        }
                    }
                });

                async function loadAll() {
                    const loaders = ['statusLoader', 'dailyLoader', 'serviceLoader', 'doctorLoader', 'gatewayLoader',
                        'newPatientsLoader', 'topServicesLoader', 'refundsLoader', 'medicinesLoader'];
                    loaders.forEach(showLoader);

                    try {
                        const [statusData, dailyData, serviceData, doctorData, gatewayData,
                               newPatientsData, topServicesData, refundsData, medicinesData] = await Promise.all([
                            fetch('{{ route('admin.reports.appointments.status') }}' + qs()).then(r => r.json()),
                            fetch('{{ route('admin.reports.appointments.daily') }}' + qs()).then(r => r.json()),
                            fetch('{{ route('admin.reports.revenue.service') }}' + qs()).then(r => r.json()),
                            fetch('{{ route('admin.reports.revenue.doctor') }}' + qs()).then(r => r.json()),
                            fetch('{{ route('admin.reports.revenue.gateway') }}' + qs()).then(r => r.json()),
                            fetch('{{ route('admin.reports.new_patients_monthly') }}' + qs()).then(r => r.json()),
                            fetch('{{ route('admin.reports.top_services') }}' + qs()).then(r => r.json()),
                            fetch('{{ route('admin.reports.refunds') }}' + qs()).then(r => r.json()),
                            fetch('{{ route('admin.reports.medicine_sales') }}' + qs()).then(r => r.json()),
                        ]);

                        // Update status chart
                        ctxStatus.data.labels = Object.keys(statusData);
                        ctxStatus.data.datasets[0].data = Object.values(statusData);
                        ctxStatus.update();
                        hideLoader('statusLoader');

                        // Update daily chart
                        ctxDaily.data.labels = Object.keys(dailyData);
                        ctxDaily.data.datasets[0].data = Object.values(dailyData);
                        ctxDaily.update();
                        hideLoader('dailyLoader');

                        // Update service chart
                        ctxService.data.labels = serviceData.map(r => r.label);
                        ctxService.data.datasets[0].data = serviceData.map(r => r.total);
                        ctxService.update();
                        hideLoader('serviceLoader');

                        // Update doctor chart
                        ctxDoctor.data.labels = doctorData.map(r => r.label);
                        ctxDoctor.data.datasets[0].data = doctorData.map(r => r.total);
                        ctxDoctor.update();
                        hideLoader('doctorLoader');

                        // Update gateway chart
                        ctxGateway.data.labels = gatewayData.map(r => r.label);
                        ctxGateway.data.datasets[0].data = gatewayData.map(r => r.total);
                        ctxGateway.update();
                        hideLoader('gatewayLoader');

                        // ==================== UPDATE CHARTS MỚI (Parent: resources/views/admin/dashboard/index.blade.php) ====================

                        // Update new patients chart
                        ctxNewPatients.data.labels = Object.keys(newPatientsData);
                        ctxNewPatients.data.datasets[0].data = Object.values(newPatientsData);
                        ctxNewPatients.update();
                        hideLoader('newPatientsLoader');

                        // Update top services chart
                        ctxTopServices.data.labels = topServicesData.map(r => r.label);
                        ctxTopServices.data.datasets[0].data = topServicesData.map(r => r.count);
                        ctxTopServices.update();
                        hideLoader('topServicesLoader');

                        // Update refunds stats
                        document.getElementById('refundCount').textContent = refundsData.total_count || 0;
                        document.getElementById('refundAmount').textContent =
                            (refundsData.total_amount || 0).toLocaleString('vi-VN') + 'đ';
                        document.getElementById('refundAvg').textContent =
                            Math.round(refundsData.avg_amount || 0).toLocaleString('vi-VN') + 'đ';

                        // Update refunds chart (by status)
                        const refundLabels = Object.keys(refundsData.by_status || {});
                        const refundCounts = refundLabels.map(status => refundsData.by_status[status].count);
                        ctxRefunds.data.labels = refundLabels;
                        ctxRefunds.data.datasets[0].data = refundCounts;
                        ctxRefunds.update();
                        hideLoader('refundsLoader');

                        // Update medicines stats
                        document.getElementById('medicineTotalRevenue').textContent =
                            (medicinesData.total_revenue || 0).toLocaleString('vi-VN') + 'đ';

                        // Update medicines chart
                        ctxMedicines.data.labels = medicinesData.top_medicines.map(r => r.label);
                        ctxMedicines.data.datasets[0].data = medicinesData.top_medicines.map(r => r.revenue);
                        ctxMedicines.update();
                        hideLoader('medicinesLoader');

                    } catch (error) {
                        console.error('Error loading charts:', error);
                        loaders.forEach(hideLoader);
                        alert('Không thể tải dữ liệu biểu đồ. Vui lòng thử lại!');
                    }
                }

                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    loadAll();
                });

                // ==================== EXPORT CSV HANDLERS (Parent: resources/views/admin/dashboard/index.blade.php) ====================
                document.querySelectorAll('[data-export]').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        const type = this.dataset.export;
                        const url = '{{ route('admin.reports.export_csv') }}' + qs() + '&type=' + type;
                        window.location.href = url;
                    });
                });

                loadAll();
            })();
        </script>
    @endpush
@endsection
