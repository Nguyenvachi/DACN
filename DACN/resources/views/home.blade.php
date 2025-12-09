<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>VietCare - Chăm Sóc Sức Khỏe Toàn Diện</title>

    <!-- Bootstrap (vẫn dùng) + FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- VietCare site css (tách riêng để đồng bộ) -->
    <link rel="stylesheet" href="{{ asset('css/vietcare.css') }}">

    <!-- ADDED: Unified design system for consistent pages -->
    <link rel="stylesheet" href="{{ asset('css/design-system-unified.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <!-- NAVBAR -->
    <nav class="vc-navbar navbar navbar-expand-lg sticky-top">
        <div class="container">

            {{-- Logo + Brand --}}
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('homepage') }}">
                <span class="vc-logo rounded-circle d-inline-flex align-items-center justify-content-center">
                    <i class="fas fa-heartbeat"></i>
                </span>
                <span class="vc-brand">VietCare</span>
            </a>

            {{-- Mobile toggle --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#vcNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            {{-- MENU --}}
            <div class="collapse navbar-collapse" id="vcNavbar">
                <ul class="navbar-nav ms-auto align-items-center">

                    {{-- Public pages --}}
                    <li class="nav-item"><a class="nav-link" href="#features">Dịch Vụ</a></li>
                    <li class="nav-item"><a class="nav-link" href="#doctors">Bác Sĩ</a></li>
                    <li class="nav-item"><a class="nav-link" href="#services">Chuyên Khoa</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('public.blog.index') }}">Tin Tức</a></li>

                    @auth
                        {{-- USER NAME --}}
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center" href="{{ route('home') }}">
                                <i class="fas fa-user-circle me-1"></i> {{ auth()->user()->name }}
                            </a>
                        </li>

                        {{-- PATIENT QUICK LINKS --}}
                        @if (auth()->user()->role === 'patient')
                            <li class="nav-item"><a class="nav-link" href="{{ route('patient.shop.index') }}">Cửa Hàng</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('patient.shop.cart') }}">Giỏ Hàng</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('patient.shop.orders') }}">Đơn Hàng</a>
                            </li>
                        @endif

                        {{-- LOGOUT --}}
                        <li class="nav-item ms-2">
                            <form action="{{ route('logout') }}" method="POST">@csrf
                                <button class="btn btn-outline-danger btn-sm">
                                    <i class="fas fa-sign-out-alt me-1"></i>Đăng Xuất
                                </button>
                            </form>
                        </li>
                    @else
                        {{-- REGISTER --}}
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="modal" data-bs-target="#authModal"
                                    data-tab="register" style="background: none; border: none;">
                                Đăng Ký
                            </button>
                        </li>

                        {{-- LOGIN --}}
                        <li class="nav-item ms-2">
                            <button class="btn vc-btn-primary" data-bs-toggle="modal" data-bs-target="#authModal">
                                <i class="fas fa-sign-in-alt me-2"></i>Đăng Nhập
                            </button>
                        </li>
                    @endauth
                </ul>
            </div>

        </div>
    </nav>

    <!-- HERO -->
    <section class="vc-hero position-relative">
        {{-- Decorative Background Shapes (Premium style) --}}
        <div class="position-absolute top-0 end-0 opacity-10 d-none d-lg-block"
            style="font-size: 15rem; margin-right:-50px;">
            <i class="fas fa-stethoscope text-white"></i>
        </div>

        <div class="container position-relative">
            <div class="row align-items-center py-5">

                {{-- LEFT CONTENT --}}
                <div class="col-lg-6 vc-hero-content">

                    <h1 class="vc-hero-title">
                        Chăm Sóc Sức Khỏe<br>
                        Toàn Diện cùng <span class="text-white">VietCare</span>
                    </h1>

                    <p class="vc-hero-sub">
                        Đội ngũ bác sĩ chuyên nghiệp – Trang thiết bị hiện đại –
                        Dịch vụ y tế uy tín chuẩn quốc tế.
                        Đặt lịch khám online nhanh chóng và tiện lợi.
                    </p>

                    {{-- ACTION BUTTONS --}}
                    <div class="d-flex flex-wrap gap-3 mb-4">
                        <a href="{{ route('public.bacsi.index') }}" class="btn vc-btn-white-lg">
                            <i class="fas fa-calendar-check me-2"></i>Đặt Lịch Ngay
                        </a>

                        <a href="#services" class="btn vc-btn-outline-lg">
                            <i class="fas fa-info-circle me-2"></i>Tìm Hiểu Thêm
                        </a>

                        {{-- Public Calendar --}}
                        @if (Route::has('public.lichhen.calendar'))
                            <a href="{{ route('public.lichhen.calendar') }}" class="btn vc-btn-outline-lg">
                                <i class="fas fa-calendar-days me-2"></i>Lịch Khám
                            </a>
                        @endif
                    </div>

                    {{-- STATS --}}
                    <div class="vc-hero-stats d-flex gap-4 flex-wrap">

                        @php
                            $doctorCount = \App\Models\BacSi::where('trang_thai', 'Đang hoạt động')->count();
                            $patientCount = \App\Models\User::where('role', 'patient')->count();
                            $appointmentCount = \App\Models\LichHen::count();
                        @endphp

                        <div class="vc-stat">
                            <span class="vc-stat-number" data-target="{{ $doctorCount }}">0+</span>
                            <span class="vc-stat-label">Bác Sĩ Chuyên Khoa</span>
                        </div>

                        <div class="vc-stat">
                            <span class="vc-stat-number" data-target="{{ $patientCount }}">0+</span>
                            <span class="vc-stat-label">Bệnh Nhân Tin Tưởng</span>
                        </div>

                        <div class="vc-stat">
                            <span class="vc-stat-number" data-target="{{ $appointmentCount }}">0+</span>
                            <span class="vc-stat-label">Lượt Khám</span>
                        </div>
                    </div>
                </div>

                {{-- RIGHT SIDE ILLUSTRATION --}}
                <div class="col-lg-6 text-center d-none d-lg-block">
                    <i class="fas fa-user-doctor vc-hero-illustration"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES (Điểm nổi bật / Dịch vụ) -->
    <section id="features" class="vc-features py-5">
        <div class="container">

            {{-- SECTION TITLE --}}
            <div class="text-center mb-4">
                <h2 class="vc-section-title">CAM KẾT ĐIỀU TRỊ DỨT ĐIỂM CÁC BỆNH LÝ TOÀN DIỆN</h2>
                <p class="vc-section-sub">Trải nghiệm dịch vụ y tế hiện đại – an toàn – tận tâm</p>
            </div>

            <div class="row mt-4">

                {{-- FEATURE ITEM 1: Đăng ký khám --}}
                <div class="col-lg-3 col-md-6 mb-4">
                    <a href="{{ route('public.bacsi.index') }}" class="text-decoration-none">
                        <div class="vc-card feature-card h-100 text-center p-4">
                            <div class="vc-feature-icon mb-3">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <h5 class="fw-bold">Đăng Ký Khám</h5>
                            <p class="text-muted small">
                                Đặt lịch online nhanh chóng, chọn bác sĩ và giờ phù hợp.
                            </p>
                        </div>
                    </a>
                </div>

                {{-- FEATURE ITEM 2: Phẫu thuật --}}
                <div class="col-lg-3 col-md-6 mb-4">
                    <a href="{{ route('public.dichvu.index') }}" class="text-decoration-none">
                        <div class="vc-card feature-card h-100 text-center p-4">
                            <div class="vc-feature-icon mb-3">
                                <i class="fas fa-heartbeat"></i>
                            </div>
                            <h5 class="fw-bold">Phẫu Thuật</h5>
                            <p class="text-muted small">
                                Phẫu thuật chuyên sâu với đội ngũ giàu kinh nghiệm.
                            </p>
                        </div>
                    </a>
                </div>

                {{-- FEATURE ITEM 3: Xét nghiệm --}}
                <div class="col-lg-3 col-md-6 mb-4">
                    <a href="{{ route('public.dichvu.index') }}" class="text-decoration-none">
                        <div class="vc-card feature-card h-100 text-center p-4">
                            <div class="vc-feature-icon mb-3">
                                <i class="fas fa-vial"></i>
                            </div>
                            <h5 class="fw-bold">Xét Nghiệm</h5>
                            <p class="text-muted small">
                                Xét nghiệm chính xác – trả nhanh – hỗ trợ điều trị hiệu quả.
                            </p>
                        </div>
                    </a>
                </div>

                {{-- FEATURE ITEM 4: Mua thuốc online --}}
                <div class="col-lg-3 col-md-6 mb-4">
                    <a href="{{ auth()->check() && auth()->user()->role === 'patient' ? route('patient.shop.index') : route('login') }}"
                        class="text-decoration-none">
                        <div class="vc-card feature-card h-100 text-center p-4">
                            <div class="vc-feature-icon mb-3">
                                <i class="fas fa-prescription-bottle-medical"></i>
                            </div>
                            <h5 class="fw-bold">Mua Thuốc Online</h5>
                            <p class="text-muted small">
                                Giao thuốc tận nơi – tư vấn dược sĩ – tiện lợi & an toàn.
                            </p>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- QUICK BOOKING FORM -->
    <section class="vc-quick-booking py-5 bg-white">
        <div class="container">
            <div class="row align-items-center">
                {{-- LEFT: Info --}}
                <div class="col-lg-5 mb-4 mb-lg-0">
                    <h2 class="fw-bold mb-3" style="font-size: 2rem; color: #1f2937;">
                        Đặt Lịch Khám Nhanh
                    </h2>
                    <p class="text-muted mb-4" style="font-size: 1.05rem;">
                        Chỉ cần 3 bước đơn giản để đặt lịch khám với bác sĩ chuyên khoa của chúng tôi.
                        Không cần chờ đợi, không cần gọi điện.
                    </p>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-start gap-3">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-success d-flex align-items-center justify-content-center"
                                     style="width: 40px; height: 40px;">
                                    <i class="fas fa-user-md text-white"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Chọn Bác Sĩ & Chuyên Khoa</h6>
                                <small class="text-muted">Đội ngũ bác sĩ giàu kinh nghiệm</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-start gap-3">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-success d-flex align-items-center justify-content-center"
                                     style="width: 40px; height: 40px;">
                                    <i class="fas fa-calendar-check text-white"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Chọn Ngày & Giờ Phù Hợp</h6>
                                <small class="text-muted">Linh hoạt với lịch làm việc của bạn</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-start gap-3">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-success d-flex align-items-center justify-content-center"
                                     style="width: 40px; height: 40px;">
                                    <i class="fas fa-check-circle text-white"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Xác Nhận & Nhận Thông Báo</h6>
                                <small class="text-muted">Nhận thông báo qua email/SMS</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT: Quick Booking Form --}}
                <div class="col-lg-7">
                    <div class="vc-card p-4 shadow-lg" style="border-radius: 20px;">
                        <form action="{{ route('lichhen.create', ['bacSi' => 'select']) }}" method="GET" id="quickBookingForm">
                            <div class="row g-3">
                                {{-- Chuyên Khoa --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-hospital me-1 text-success"></i>Chuyên Khoa
                                    </label>
                                    <select class="form-select" name="chuyen_khoa" id="quickChuyenKhoa" required>
                                        <option value="">Chọn chuyên khoa...</option>
                                        @php
                                            $chuyenKhoas = \App\Models\ChuyenKhoa::withCount('bacSis')
                                                ->having('bac_sis_count', '>', 0)
                                                ->orderBy('ten')
                                                ->get();
                                        @endphp
                                        @foreach($chuyenKhoas as $ck)
                                            <option value="{{ $ck->id }}">{{ $ck->ten }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Bác Sĩ --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-user-md me-1 text-success"></i>Bác Sĩ
                                    </label>
                                    <select class="form-select" name="bac_si" id="quickBacSi" required disabled>
                                        <option value="">Chọn chuyên khoa trước...</option>
                                    </select>
                                </div>

                                {{-- Ngày Khám --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-calendar me-1 text-success"></i>Ngày Khám
                                    </label>
                                    <input type="date" class="form-control" name="ngay"
                                           min="{{ date('Y-m-d') }}" required>
                                </div>

                                {{-- Họ Tên --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-user me-1 text-success"></i>Họ Tên
                                    </label>
                                    <input type="text" class="form-control" name="ho_ten"
                                           placeholder="Nhập họ tên của bạn" required
                                           value="{{ auth()->check() ? auth()->user()->name : '' }}">
                                </div>

                                {{-- Số Điện Thoại --}}
                                <div class="col-md-12">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-phone me-1 text-success"></i>Số Điện Thoại
                                    </label>
                                    <input type="tel" class="form-control" name="so_dien_thoai"
                                           placeholder="Nhập số điện thoại" required
                                           value="{{ auth()->check() ? auth()->user()->so_dien_thoai : '' }}">
                                </div>

                                {{-- Lý Do Khám --}}
                                <div class="col-md-12">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-notes-medical me-1 text-success"></i>Lý Do Khám (Tùy chọn)
                                    </label>
                                    <textarea class="form-control" name="ly_do" rows="2"
                                              placeholder="Mô tả triệu chứng hoặc lý do khám..."></textarea>
                                </div>

                                {{-- Submit Button --}}
                                <div class="col-12">
                                    @auth
                                        <button type="button" class="btn vc-btn-primary w-100 py-3"
                                                onclick="handleQuickBooking()">
                                            <i class="fas fa-calendar-check me-2"></i>Đặt Lịch Ngay
                                        </button>
                                    @else
                                        <a href="{{ route('login') }}" class="btn vc-btn-primary w-100 py-3">
                                            <i class="fas fa-sign-in-alt me-2"></i>Đăng Nhập Để Đặt Lịch
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- VIDEO INTRODUCTION -->
    <section class="vc-video-intro py-5" style="background: linear-gradient(135deg, #f0fdf4, #ecfdf5);">
        <div class="container">
            {{-- TITLE --}}
            <div class="text-center mb-5">
                <h2 class="vc-section-title">Khám Phá VietCare</h2>
                <p class="vc-section-sub">Trải nghiệm cơ sở vật chất hiện đại và đội ngũ y bác sĩ tận tâm</p>
            </div>

            <div class="row g-4">
                {{-- Main Video --}}
                <div class="col-lg-8">
                    <div class="vc-card overflow-hidden" style="border-radius: 20px; height: 400px;">
                        {{-- Placeholder for video - Replace with actual video --}}
                        <div class="position-relative h-100 bg-dark d-flex align-items-center justify-content-center"
                             style="background: linear-gradient(135deg, rgba(16,185,129,0.8), rgba(5,150,105,0.8)), url('https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=800') center/cover;">
                            <button class="btn btn-light rounded-circle"
                                    style="width: 80px; height: 80px; font-size: 2rem;"
                                    data-bs-toggle="modal" data-bs-target="#videoModal">
                                <i class="fas fa-play text-success"></i>
                            </button>
                            <div class="position-absolute bottom-0 start-0 p-4 text-white">
                                <h4 class="fw-bold mb-2">Video Giới Thiệu VietCare</h4>
                                <p class="mb-0">Cùng khám phá không gian khám chữa bệnh hiện đại</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Side Videos --}}
                <div class="col-lg-4">
                    <div class="d-flex flex-column gap-3 h-100">
                        {{-- Virtual Tour --}}
                        <div class="vc-card overflow-hidden flex-fill" style="border-radius: 16px;">
                            <div class="position-relative h-100 bg-dark d-flex align-items-center justify-content-center"
                                 style="background: linear-gradient(135deg, rgba(16,185,129,0.7), rgba(5,150,105,0.7)), url('https://images.unsplash.com/photo-1538108149393-fbbd81895907?w=400') center/cover; min-height: 190px;">
                                <button class="btn btn-light btn-sm rounded-circle"
                                        style="width: 50px; height: 50px;"
                                        data-bs-toggle="modal" data-bs-target="#tourModal">
                                    <i class="fas fa-play text-success"></i>
                                </button>
                                <div class="position-absolute bottom-0 start-0 p-3 text-white w-100">
                                    <h6 class="fw-bold mb-1">Tour 360° Cơ Sở</h6>
                                    <small>Khám phá không gian phòng khám</small>
                                </div>
                            </div>
                        </div>

                        {{-- Doctor Interview --}}
                        <div class="vc-card overflow-hidden flex-fill" style="border-radius: 16px;">
                            <div class="position-relative h-100 bg-dark d-flex align-items-center justify-content-center"
                                 style="background: linear-gradient(135deg, rgba(16,185,129,0.7), rgba(5,150,105,0.7)), url('https://images.unsplash.com/photo-1622253692010-333f2da6031d?w=400') center/cover; min-height: 190px;">
                                <button class="btn btn-light btn-sm rounded-circle"
                                        style="width: 50px; height: 50px;"
                                        data-bs-toggle="modal" data-bs-target="#interviewModal">
                                    <i class="fas fa-play text-success"></i>
                                </button>
                                <div class="position-absolute bottom-0 start-0 p-3 text-white w-100">
                                    <h6 class="fw-bold mb-1">Phỏng Vấn Bác Sĩ</h6>
                                    <small>Chia sẻ từ đội ngũ chuyên gia</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Video Modals -->
    <div class="modal fade" id="videoModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content" style="border-radius: 20px; overflow: hidden;">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Video Giới Thiệu VietCare</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    {{-- Replace with actual video embed --}}
                    <div class="ratio ratio-16x9 bg-dark">
                        <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ"
                                allowfullscreen style="border: none;"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="tourModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content" style="border-radius: 20px; overflow: hidden;">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Tour 360° Cơ Sở VietCare</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="ratio ratio-16x9 bg-dark">
                        {{-- Replace with actual 360 tour embed --}}
                        <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ"
                                allowfullscreen style="border: none;"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="interviewModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content" style="border-radius: 20px; overflow: hidden;">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Phỏng Vấn Bác Sĩ VietCare</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="ratio ratio-16x9 bg-dark">
                        <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ"
                                allowfullscreen style="border: none;"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PROCESS / Quy trình -->
    <section class="vc-process py-5 bg-white">
        <div class="container">

            {{-- TITLE --}}
            <div class="text-center mb-4">
                <h2 class="vc-section-title">QUY TRÌNH ĐIỀU TRỊ TẠI VIETCARE</h2>
                <p class="vc-section-sub">Quy trình chuẩn hóa – nhanh chóng – an toàn cho bệnh nhân</p>
            </div>

            {{-- PROCESS STEPS --}}
            <div class="row mt-5 text-center justify-content-center">

                {{-- STEP 1 --}}
                <div class="col-lg-2 col-md-4 col-6 mb-4">
                    <div class="vc-process-item">
                        <div class="vc-process-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="fw-bold mt-2">Đặt lịch khám</div>
                    </div>
                </div>

                {{-- STEP 2 --}}
                <div class="col-lg-2 col-md-4 col-6 mb-4">
                    <div class="vc-process-item">
                        <div class="vc-process-icon">
                            <i class="fas fa-stethoscope"></i>
                        </div>
                        <div class="fw-bold mt-2">Khám & tư vấn</div>
                    </div>
                </div>

                {{-- STEP 3 --}}
                <div class="col-lg-2 col-md-4 col-6 mb-4">
                    <div class="vc-process-item">
                        <div class="vc-process-icon">
                            <i class="fas fa-vial-circle-check"></i>
                        </div>
                        <div class="fw-bold mt-2">Xét nghiệm / Chẩn đoán</div>
                    </div>
                </div>

                {{-- STEP 4 --}}
                <div class="col-lg-2 col-md-4 col-6 mb-4">
                    <div class="vc-process-item">
                        <div class="vc-process-icon">
                            <i class="fas fa-pills"></i>
                        </div>
                        <div class="fw-bold mt-2">Điều trị & Theo dõi</div>
                    </div>
                </div>

                {{-- STEP 5 --}}
                <div class="col-lg-2 col-md-4 col-6 mb-4">
                    <div class="vc-process-item">
                        <div class="vc-process-icon">
                            <i class="fas fa-hands-holding-heart"></i>
                        </div>
                        <div class="fw-bold mt-2">Chăm sóc sau điều trị</div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ACHIEVEMENTS -->
    <!-- ACHIEVEMENTS -->
    <section class="vc-achievements py-5" style="background:#f0fdf4;">
        <div class="container">

            {{-- TITLE --}}
            <div class="text-center mb-5">
                <h2 class="vc-section-title">Thành Tựu & Niềm Tin</h2>
                <p class="vc-section-sub">VietCare đồng hành cùng hàng ngàn bệnh nhân mỗi ngày</p>
            </div>

            {{-- ACHIEVEMENT CARDS --}}
            <div class="row text-center g-4">

                {{-- ACTIVE PATIENTS --}}
                <div class="col-md-4">
                    <div class="vc-card p-4 h-100">
                        <i class="fas fa-users vc-achieve-icon mb-3"></i>
                        <h3 class="fw-bold vc-achieve-number">500+</h3>
                        <p class="text-muted">Khách hàng đang điều trị</p>
                    </div>
                </div>

                {{-- HAPPY PATIENTS --}}
                <div class="col-md-4">
                    <div class="vc-card p-4 h-100">
                        <i class="fas fa-smile-beam vc-achieve-icon mb-3"></i>
                        <h3 class="fw-bold vc-achieve-number">7.000+</h3>
                        <p class="text-muted">Khách hàng hài lòng</p>
                    </div>
                </div>

                {{-- SERVICE QUALITY --}}
                <div class="col-md-4">
                    <div class="vc-card p-4 h-100">
                        <i class="fas fa-check-circle vc-achieve-icon mb-3"></i>
                        <h3 class="fw-bold vc-achieve-number">99%</h3>
                        <p class="text-muted">Mức độ hài lòng dịch vụ</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- DOCTORS -->
    <section id="doctors" class="vc-doctors py-5 bg-white">
        <div class="container">

            {{-- Title --}}
            <h2 class="vc-section-title">Đội Ngũ Bác Sĩ Chuyên Khoa</h2>
            <p class="vc-section-sub">Kinh nghiệm – Tận tâm – Luôn đồng hành cùng bạn</p>

            {{-- Query 6 doctors --}}
            @php
                $doctors = \App\Models\BacSi::where('trang_thai', 'Đang hoạt động')
                    ->with('chuyenKhoas')
                    ->latest()
                    ->limit(6)
                    ->get();
            @endphp

            <div class="row mt-4">
                @forelse($doctors as $doctor)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="vc-card doctor-card h-100">

                            {{-- Doctor Image --}}
                            <div class="doctor-image">
                                @if ($doctor->anh_dai_dien)
                                    <img src="{{ asset('storage/' . $doctor->anh_dai_dien) }}"
                                        alt="{{ $doctor->ho_ten }}" class="w-100 h-100" loading="lazy"
                                        style="object-fit: cover;">
                                @else
                                    <div class="d-flex align-items-center justify-content-center h-100">
                                        <i class="fas fa-user-md fa-5x text-white"></i>
                                    </div>
                                @endif
                            </div>

                            {{-- Doctor Info --}}
                            <div class="p-4">
                                <h5 class="fw-bold">{{ $doctor->ho_ten }}</h5>

                                <div class="text-success small mb-2">
                                    @if ($doctor->chuyenKhoas->count())
                                        {{ $doctor->chuyenKhoas->pluck('ten_chuyen_khoa')->join(', ') }}
                                    @else
                                        Bác sĩ đa khoa
                                    @endif
                                </div>

                                <p class="text-muted small mb-3">
                                    {{ Str::limit($doctor->mo_ta ?? 'Bác sĩ luôn tận tâm với bệnh nhân.', 110) }}
                                </p>

                                {{-- Action Buttons --}}
                                <div class="d-grid gap-2">

                                    {{-- Đặt lịch khám --}}
                                    <a href="{{ route('lichhen.create', $doctor->id) }}" class="btn vc-btn-primary">
                                        <i class="fas fa-calendar-plus me-2"></i>Đặt Lịch Khám
                                    </a>

                                    {{-- Xem chi tiết bác sĩ --}}
                                    <a href="{{ route('public.bacsi.index') . '?id=' . $doctor->id }}"
                                        class="btn btn-outline-primary">
                                        <i class="fas fa-user-circle me-2"></i>Hồ Sơ Bác Sĩ
                                    </a>

                                    {{-- Xem lịch rảnh --}}
                                    @if (Route::has('public.bacsi.schedule'))
                                        <a href="{{ route('public.bacsi.schedule', $doctor->id) }}"
                                            class="btn btn-outline-secondary">
                                            <i class="fas fa-calendar-week me-2"></i>Lịch Rảnh
                                        </a>
                                    @endif

                                    {{-- Tư vấn online --}}
                                    @auth
                                        @if (auth()->user()->role === 'patient')
                                            <a href="{{ route('patient.chat.create', $doctor->id) }}"
                                                class="btn btn-outline-success">
                                                <i class="fas fa-comments me-2"></i>Tư Vấn Online
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-outline-success">
                                            <i class="fas fa-comments me-2"></i>Đăng nhập để tư vấn
                                        </a>
                                    @endauth

                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p class="text-muted">Đang cập nhật đội ngũ bác sĩ...</p>
                    </div>
                @endforelse
            </div>

            {{-- View all doctors --}}
            <div class="text-center mt-3">
                <a href="{{ route('public.bacsi.index') }}" class="btn vc-btn-outline-lg">
                    <i class="fas fa-users me-2"></i>Xem Tất Cả Bác Sĩ
                </a>
            </div>

        </div>
    </section>

    <!-- SERVICES (Chuyên khoa) -->
    <section id="services" class="vc-services py-5" style="background:#f9fafb;">
        <div class="container">

            {{-- Title --}}
            <h2 class="vc-section-title">Chuyên Khoa Điều Trị</h2>
            <p class="vc-section-sub">Hệ thống chuyên khoa đa dạng – đáp ứng mọi nhu cầu khám chữa bệnh</p>

            @php
                // Lấy danh sách chuyên khoa có bác sĩ
                $specialties = \App\Models\ChuyenKhoa::withCount('bacSis')
                    ->having('bac_sis_count', '>', 0)
                    ->orderBy('ten')
                    ->limit(6)
                    ->get();

                // Icon map theo tên chuyên khoa
                $specialtyIcons = [
                    'Tim' => 'fa-heart-pulse',
                    'Thần Kinh' => 'fa-brain',
                    'Cơ Xương' => 'fa-bone',
                    'Hô Hấp' => 'fa-lungs',
                    'Tiêu Hóa' => 'fa-stomach',
                    'Nhi' => 'fa-baby',
                    'Sản' => 'fa-person-pregnant',
                    'Da Liễu' => 'fa-spa',
                    'Tai Mũi Họng' => 'fa-ear-listen',
                    'Mắt' => 'fa-eye',
                    'Răng' => 'fa-tooth',
                    'default' => 'fa-stethoscope',
                ];
            @endphp

            {{-- List specialties --}}
            <div class="row mt-4">
                @forelse ($specialties as $specialty)
                    @php
                        // auto detect icon
                        $icon = 'fa-stethoscope';
                        foreach ($specialtyIcons as $key => $val) {
                            if (str_contains($specialty->ten_chuyen_khoa, $key)) {
                                $icon = $val;
                                break;
                            }
                        }
                    @endphp

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="vc-card service-card h-100 p-4">

                            {{-- Icon --}}
                            <div class="service-icon mb-3">
                                <i class="fas {{ $icon }}"></i>
                            </div>

                            {{-- Name --}}
                            <h5 class="fw-bold">{{ $specialty->ten_chuyen_khoa }}</h5>

                            {{-- Description --}}
                            <p class="text-muted small">
                                {{ Str::limit($specialty->mo_ta ?? 'Chuyên khoa điều trị chất lượng cao.', 120) }}
                            </p>

                            {{-- Stats --}}
                            <small class="text-muted d-block">
                                <i class="fas fa-user-doctor me-1"></i>
                                {{ $specialty->bac_sis_count }} bác sĩ
                            </small>

                            {{-- Buttons --}}
                            <div class="mt-3 d-grid gap-2">
                                {{-- Xem bác sĩ thuộc chuyên khoa (đúng route thực tế) --}}
                                <a href="{{ route('public.bacsi.index') . '?chuyen_khoa=' . urlencode($specialty->ten_chuyen_khoa) }}"
                                    class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-search me-1"></i>Xem bác sĩ
                                </a>

                                {{-- Xem danh sách dịch vụ --}}
                                <a href="{{ route('public.dichvu.index') }}"
                                    class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-list me-1"></i>Dịch vụ liên quan
                                </a>
                            </div>

                        </div>
                    </div>

                @empty
                    <div class="col-12 text-center">
                        <p class="text-muted">Hiện chưa có chuyên khoa nào khả dụng.</p>
                    </div>
                @endforelse
            </div>

            {{-- View all --}}
            <div class="text-center mt-4">
                <a href="{{ route('public.dichvu.index') }}" class="btn vc-btn-primary">
                    <i class="fas fa-layer-group me-2"></i>Xem Tất Cả Chuyên Khoa
                </a>
            </div>

        </div>
    </section>

    <!-- TESTIMONIALS / REVIEWS -->
    <section class="vc-testimonials py-5 bg-white">
        <div class="container">
            {{-- TITLE --}}
            <div class="text-center mb-5">
                <h2 class="vc-section-title">Bệnh Nhân Nói Gì Về VietCare</h2>
                <p class="vc-section-sub">Những đánh giá chân thực từ bệnh nhân đã tin tưởng sử dụng dịch vụ</p>
            </div>

            @php
                // Lấy 6 đánh giá được duyệt (approved), có rating cao, mới nhất
                $reviews = \App\Models\DanhGia::with(['user', 'bacSi'])
                    ->where('trang_thai', 'approved')
                    ->whereNotNull('noi_dung')
                    ->orderBy('rating', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->limit(6)
                    ->get();
            @endphp

            <div class="row g-4">
                @forelse($reviews as $review)
                    <div class="col-lg-4 col-md-6">
                        <div class="vc-card h-100 p-4">
                            {{-- Rating Stars --}}
                            <div class="mb-3">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star" style="color: {{ $i <= $review->rating ? '#fbbf24' : '#e5e7eb' }};"></i>
                                @endfor
                            </div>

                            {{-- Review Content --}}
                            <p class="text-muted mb-3" style="font-size: 0.95rem; line-height: 1.6;">
                                "{{ Str::limit($review->noi_dung, 150) }}"
                            </p>

                            {{-- User Info --}}
                            <div class="d-flex align-items-center gap-3 pt-3 border-top">
                                <div class="rounded-circle bg-success d-flex align-items-center justify-content-center"
                                     style="width: 48px; height: 48px; flex-shrink: 0;">
                                    @if($review->user && $review->user->anh_dai_dien)
                                        <img src="{{ asset('storage/' . $review->user->anh_dai_dien) }}"
                                             alt="{{ $review->user->name }}"
                                             class="rounded-circle w-100 h-100" style="object-fit: cover;">
                                    @else
                                        <i class="fas fa-user text-white"></i>
                                    @endif
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $review->user->name ?? 'Bệnh nhân' }}</div>
                                    <small class="text-muted">
                                        Khám với {{ $review->bacSi->ho_ten ?? 'Bác sĩ' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-comments text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                        <p class="text-muted mt-3">Chưa có đánh giá nào.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- BLOG HIGHLIGHTS -->
    <section class="vc-blog-highlights py-5" style="background: #f9fafb;">
        <div class="container">
            {{-- TITLE --}}
            <div class="text-center mb-4">
                <h2 class="vc-section-title">Kiến Thức Y Khoa Mới Nhất</h2>
                <p class="vc-section-sub">Cập nhật thông tin sức khỏe hữu ích từ đội ngũ chuyên gia</p>
            </div>

            @php
                // Lấy 3 bài viết mới nhất đã published
                $latestPosts = \App\Models\BaiViet::with(['danhMuc', 'author'])
                    ->where('status', 'published')
                    ->where(function($q) {
                        $q->whereNull('published_at')
                          ->orWhere('published_at', '<=', now());
                    })
                    ->orderBy('published_at', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->limit(3)
                    ->get();
            @endphp

            <div class="row g-4">
                @forelse($latestPosts as $post)
                    <div class="col-lg-4 col-md-6">
                        <article class="vc-card h-100" style="overflow: hidden;">
                            {{-- Thumbnail --}}
                            @if($post->thumbnail)
                                <div style="height: 200px; overflow: hidden;">
                                    <img src="{{ $post->thumbnail }}" alt="{{ $post->title }}"
                                         class="w-100 h-100" style="object-fit: cover;">
                                </div>
                            @else
                                <div style="height: 200px; background: linear-gradient(135deg, #10b981, #059669); display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-newspaper text-white" style="font-size: 3rem; opacity: 0.3;"></i>
                                </div>
                            @endif

                            {{-- Content --}}
                            <div class="p-4">
                                @if($post->danhMuc)
                                    <span class="badge bg-success mb-2">{{ $post->danhMuc->name }}</span>
                                @endif

                                <h5 class="fw-bold mb-2" style="line-height: 1.4;">
                                    <a href="{{ route('blog.show', $post->slug) }}"
                                       class="text-decoration-none text-dark hover-green">
                                        {{ $post->title }}
                                    </a>
                                </h5>

                                <p class="text-muted small mb-3">
                                    {{ Str::limit($post->excerpt, 100) }}
                                </p>

                                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                    <small class="text-muted">
                                        <i class="far fa-calendar me-1"></i>
                                        {{ optional($post->published_at ?? $post->created_at)->format('d/m/Y') }}
                                    </small>
                                    <a href="{{ route('blog.show', $post->slug) }}"
                                       class="btn btn-sm btn-outline-success">
                                        Đọc thêm <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-newspaper text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                        <p class="text-muted mt-3">Chưa có bài viết nào.</p>
                    </div>
                @endforelse
            </div>

            {{-- View All Button --}}
            @if($latestPosts->count() > 0)
                <div class="text-center mt-4">
                    <a href="{{ route('public.blog.index') }}" class="btn vc-btn-primary">
                        <i class="fas fa-book-open me-2"></i>Xem Tất Cả Bài Viết
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- FAQ SECTION -->
    <section class="vc-faq py-5 bg-white">
        <div class="container">
            {{-- TITLE --}}
            <div class="text-center mb-5">
                <h2 class="vc-section-title">Câu Hỏi Thường Gặp</h2>
                <p class="vc-section-sub">Giải đáp những thắc mắc phổ biến về dịch vụ y tế tại VietCare</p>
            </div>

            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="accordion" id="faqAccordion">

                        {{-- FAQ 1 --}}
                        <div class="accordion-item border-0 mb-3 vc-card">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-bold" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#faq1">
                                    <i class="fas fa-question-circle text-success me-2"></i>
                                    Làm thế nào để đặt lịch khám tại VietCare?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Bạn có thể đặt lịch khám online dễ dàng bằng cách: <br>
                                    1. Chọn chuyên khoa và bác sĩ phù hợp<br>
                                    2. Chọn khung giờ khám<br>
                                    3. Điền thông tin và xác nhận<br>
                                    Hoặc gọi hotline <strong>1900 xxxx</strong> để được hỗ trợ trực tiếp.
                                </div>
                            </div>
                        </div>

                        {{-- FAQ 2 --}}
                        <div class="accordion-item border-0 mb-3 vc-card">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#faq2">
                                    <i class="fas fa-question-circle text-success me-2"></i>
                                    Chi phí khám chữa bệnh như thế nào?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Chi phí khám tùy thuộc vào chuyên khoa và bác sĩ. Bạn có thể xem bảng giá chi tiết
                                    tại mục <a href="{{ route('public.dichvu.index') }}">Dịch vụ</a>.
                                    VietCare chấp nhận thanh toán qua VNPay, MoMo và tiền mặt.
                                </div>
                            </div>
                        </div>

                        {{-- FAQ 3 --}}
                        <div class="accordion-item border-0 mb-3 vc-card">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#faq3">
                                    <i class="fas fa-question-circle text-success me-2"></i>
                                    VietCare có chấp nhận bảo hiểm y tế không?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Có, VietCare chấp nhận bảo hiểm y tế và liên kết với các công ty bảo hiểm lớn.
                                    Vui lòng mang theo thẻ BHYT khi đến khám để được hỗ trợ thanh toán.
                                </div>
                            </div>
                        </div>

                        {{-- FAQ 4 --}}
                        <div class="accordion-item border-0 mb-3 vc-card">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#faq4">
                                    <i class="fas fa-question-circle text-success me-2"></i>
                                    Tôi có thể hủy lịch hẹn không?
                                </button>
                            </h2>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Có, bạn có thể hủy lịch hẹn trước giờ khám ít nhất 2 tiếng.
                                    Vui lòng liên hệ hotline hoặc hủy trực tiếp trên hệ thống để được hoàn tiền (nếu đã thanh toán).
                                </div>
                            </div>
                        </div>

                        {{-- FAQ 5 --}}
                        <div class="accordion-item border-0 mb-3 vc-card">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#faq5">
                                    <i class="fas fa-question-circle text-success me-2"></i>
                                    VietCare có dịch vụ khám tại nhà không?
                                </button>
                            </h2>
                            <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Hiện tại VietCare đang triển khai dịch vụ khám tại nhà cho một số chuyên khoa.
                                    Vui lòng liên hệ hotline <strong>1900 xxxx</strong> để biết thêm chi tiết và đăng ký.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- PARTNERSHIPS / CERTIFICATIONS -->
    <section class="vc-partnerships py-5" style="background: #f9fafb;">
        <div class="container">
            {{-- TITLE --}}
            <div class="text-center mb-5">
                <h2 class="vc-section-title">Đối Tác & Chứng Nhận</h2>
                <p class="vc-section-sub">VietCare tự hào là đối tác của các tổ chức y tế uy tín</p>
            </div>

            <div class="row g-4 align-items-center justify-content-center text-center">
                {{-- Placeholder for partner logos --}}
                <div class="col-lg-2 col-md-3 col-4">
                    <div class="vc-card p-4 h-100 d-flex align-items-center justify-content-center"
                         style="min-height: 100px; opacity: 0.7;">
                        <i class="fas fa-hospital fa-3x text-success"></i>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-4">
                    <div class="vc-card p-4 h-100 d-flex align-items-center justify-content-center"
                         style="min-height: 100px; opacity: 0.7;">
                        <i class="fas fa-shield-alt fa-3x text-success"></i>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-4">
                    <div class="vc-card p-4 h-100 d-flex align-items-center justify-content-center"
                         style="min-height: 100px; opacity: 0.7;">
                        <i class="fas fa-certificate fa-3x text-success"></i>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-4">
                    <div class="vc-card p-4 h-100 d-flex align-items-center justify-content-center"
                         style="min-height: 100px; opacity: 0.7;">
                        <i class="fas fa-award fa-3x text-success"></i>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-4">
                    <div class="vc-card p-4 h-100 d-flex align-items-center justify-content-center"
                         style="min-height: 100px; opacity: 0.7;">
                        <i class="fas fa-medal fa-3x text-success"></i>
                    </div>
                </div>
            </div>

            {{-- Certifications Text --}}
            <div class="row mt-5">
                <div class="col-lg-8 mx-auto text-center">
                    <div class="d-flex flex-wrap justify-content-center gap-4">
                        <div>
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <span class="fw-bold">ISO 9001:2015</span>
                        </div>
                        <div>
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <span class="fw-bold">Chuẩn Bộ Y Tế</span>
                        </div>
                        <div>
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <span class="fw-bold">JCI Accredited</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CONSULT / CTA -->
    <section class="vc-cta py-5">
        <div class="container text-center">

            {{-- Title --}}
            <h2 class="vc-cta-title">Sẵn Sàng Chăm Sóc Sức Khỏe?</h2>
            <p class="vc-cta-sub">
                Đặt lịch khám ngay để được tư vấn và điều trị bởi đội ngũ chuyên gia hàng đầu của VietCare
            </p>

            {{-- CTA Buttons --}}
            <div class="d-flex justify-content-center gap-3 flex-wrap mt-4">

                {{-- Đặt lịch khám luôn khả dụng cho mọi vai trò --}}
                <a href="{{ route('public.bacsi.index') }}" class="btn vc-btn-white-lg">
                    <i class="fas fa-calendar-check me-2"></i>Đặt Lịch Ngay
                </a>

                {{-- Tư vấn online: chỉ dành cho bệnh nhân --}}
                @auth
                    @if (auth()->user()->role === 'patient')
                        <a href="{{ route('patient.chat.index') }}" class="btn vc-btn-primary">
                            <i class="fas fa-comments me-2"></i>Tư Vấn Online
                        </a>
                    @else
                        {{-- Nếu không phải patient (doctor/staff/admin) --}}
                        <a href="{{ route('login') }}" class="btn btn-outline-light">
                            <i class="fas fa-user-lock me-2"></i>Dành Cho Bệnh Nhân
                        </a>
                    @endif
                @else
                    {{-- Nếu chưa đăng nhập --}}
                    <button class="btn vc-btn-primary" data-bs-toggle="modal" data-bs-target="#authModal">
                        <i class="fas fa-sign-in-alt me-2"></i>Đăng Nhập để Tư Vấn
                    </button>
                @endauth

            </div>

        </div>
    </section>

    <!-- AUTH MODAL -->
    <x-auth-modal />

    <!-- FOOTER -->
    <!-- FOOTER -->
    <footer class="vc-footer">
        <div class="container py-5">

            <div class="row gy-4">

                <!-- Column 1: Logo + giới thiệu -->
                <div class="col-lg-4 col-md-6 footer-column">
                    <h4 class="vc-footer-brand">
                        <i class="fas fa-heartbeat me-2"></i>VietCare
                    </h4>

                    <p>
                        Dịch vụ chăm sóc sức khỏe toàn diện với đội ngũ bác sĩ chuyên nghiệp
                        và hệ thống điều trị hiện đại, an toàn và tận tâm.
                    </p>

                    <div class="d-flex gap-3 mt-3">
                        <a href="#" aria-label="facebook"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" aria-label="instagram"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" aria-label="twitter"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" aria-label="youtube"><i class="fab fa-youtube fa-lg"></i></a>
                    </div>
                </div>

                <!-- Column 2: Liên kết nhanh -->
                <div class="col-lg-2 col-md-6 footer-column">
                    <h5 class="vc-footer-title">Liên Kết</h5>
                    <ul class="list-unstyled">
                        <li><a href="#features">Dịch Vụ</a></li>
                        <li><a href="#doctors">Bác Sĩ</a></li>
                        <li><a href="#services">Chuyên Khoa</a></li>
                        <li><a href="{{ route('public.blog.index') }}">Tin Tức</a></li>
                        <li><a href="{{ route('sitemap') }}">Sitemap</a></li>
                    </ul>
                </div>

                <!-- Column 3: Dịch vụ chính -->
                <div class="col-lg-3 col-md-6 footer-column">
                    <h5 class="vc-footer-title">Dịch Vụ</h5>
                    <ul class="list-unstyled">

                        <li>
                            <a href="{{ route('public.bacsi.index') }}">Đặt Lịch Khám</a>
                        </li>

                        <li>
                            <a href="{{ route('public.dichvu.index') }}">Danh Sách Dịch Vụ</a>
                        </li>

                        {{-- Mua thuốc online --}}
                        <li>
                            @auth
                                @if (auth()->user()->role === 'patient')
                                    <a href="{{ route('patient.shop.index') }}">Mua Thuốc Online</a>
                                @else
                                    <a href="{{ route('login') }}">Mua Thuốc Online</a>
                                @endif
                            @else
                                <a href="{{ route('login') }}">Mua Thuốc Online</a>
                            @endauth
                        </li>

                        {{-- Tư vấn online --}}
                        <li>
                            @auth
                                @if (auth()->user()->role === 'patient')
                                    <a href="{{ route('patient.chat.index') }}">Tư Vấn Online</a>
                                @else
                                    <a href="{{ route('login') }}">Tư Vấn Online</a>
                                @endif
                            @else
                                <a href="{{ route('login') }}">Tư Vấn Online</a>
                            @endauth
                        </li>

                    </ul>
                </div>

                <!-- Column 4: Liên hệ -->
                <div class="col-lg-3 col-md-6 footer-column">
                    <h5 class="vc-footer-title">Liên Hệ</h5>
                    <ul class="list-unstyled">
                        <li>
                            <i class="fas fa-map-marker-alt me-2"></i>
                            123 Đường ABC, Quận 1, TP.HCM
                        </li>
                        <li>
                            <i class="fas fa-phone me-2"></i>
                            <a href="tel:1900xxxx">1900 xxxx</a>
                        </li>
                        <li>
                            <i class="fas fa-envelope me-2"></i>
                            <a href="mailto:contact@vietcare.vn">contact@vietcare.vn</a>
                        </li>
                        <li>
                            <i class="fas fa-clock me-2"></i>Hoạt động 24/7
                        </li>
                    </ul>
                </div>

            </div>

            <!-- Bottom Line -->
            <div class="vc-footer-bottom mt-4 text-center">
                &copy; {{ date('Y') }} VietCare. All rights reserved.
            </div>

        </div>
    </footer>

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Small JS helpers --}}
    <script>
        // CSRF for future ajax (if needed)
        window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Optional: open schedule in new window (if route exists)
        function openDoctorSchedule(doctorId) {
            const url = "{{ url('/bac-si') }}/" + doctorId + "/lich-ranh";
            window.open(url, '_blank');
        }

        // ========== ANIMATED COUNTER ==========
        // Counter animation for statistics
        function animateCounter(element, target) {
            const duration = 2000; // 2 seconds
            const start = 0;
            const increment = target / (duration / 16); // 60fps
            let current = start;

            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    element.textContent = target + '+';
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current) + '+';
                }
            }, 16);
        }

        // Run counter animation when stats are in viewport
        document.addEventListener('DOMContentLoaded', function() {
            const stats = document.querySelectorAll('.vc-stat-number');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !entry.target.classList.contains('animated')) {
                        entry.target.classList.add('animated');
                        const target = parseInt(entry.target.dataset.target);
                        animateCounter(entry.target, target);
                    }
                });
            }, { threshold: 0.5 });

            stats.forEach(stat => observer.observe(stat));
        });

        // ========== SMOOTH SCROLL ==========
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href !== '#' && href.length > 1) {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }
            });
        });

        // ========== AUTH MODAL TAB SWITCHING ==========
        // Switch to register tab when button has data-tab="register"
        document.querySelectorAll('[data-bs-toggle="modal"][data-tab]').forEach(button => {
            button.addEventListener('click', function() {
                const tab = this.dataset.tab;
                const authModal = document.getElementById('authModal');

                authModal.addEventListener('shown.bs.modal', function() {
                    if (tab === 'register') {
                        const registerTabBtn = document.getElementById('register-tab');
                        if (registerTabBtn) {
                            const bsTab = new bootstrap.Tab(registerTabBtn);
                            bsTab.show();
                        }
                    }
                }, { once: true });
            });
        });

        // ========== QUICK BOOKING FORM ==========
        // Load bác sĩ theo chuyên khoa
        const quickChuyenKhoaSelect = document.getElementById('quickChuyenKhoa');
        const quickBacSiSelect = document.getElementById('quickBacSi');

        if (quickChuyenKhoaSelect) {
            quickChuyenKhoaSelect.addEventListener('change', function() {
                const chuyenKhoaId = this.value;

                if (!chuyenKhoaId) {
                    quickBacSiSelect.disabled = true;
                    quickBacSiSelect.innerHTML = '<option value="">Chọn chuyên khoa trước...</option>';
                    return;
                }

                // Load bác sĩ qua AJAX
                fetch(`/ajax/bac-si-by-chuyen-khoa?chuyen_khoa_id=${chuyenKhoaId}`)
                    .then(response => response.json())
                    .then(data => {
                        quickBacSiSelect.disabled = false;
                        quickBacSiSelect.innerHTML = '<option value="">Chọn bác sĩ...</option>';

                        if (data.length > 0) {
                            data.forEach(bacSi => {
                                const option = document.createElement('option');
                                option.value = bacSi.id;
                                option.textContent = bacSi.ho_ten;
                                quickBacSiSelect.appendChild(option);
                            });
                        } else {
                            quickBacSiSelect.innerHTML = '<option value="">Không có bác sĩ</option>';
                        }
                    })
                    .catch(error => {
                        console.error('Error loading doctors:', error);
                        quickBacSiSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
                    });
            });
        }

        // Handle quick booking submission
        function handleQuickBooking() {
            const form = document.getElementById('quickBookingForm');
            const bacSiId = document.getElementById('quickBacSi').value;

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            if (!bacSiId) {
                alert('Vui lòng chọn bác sĩ');
                return;
            }

            // Redirect to booking page with pre-filled data
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);
            window.location.href = `/dat-lich/${bacSiId}?${params.toString()}`;
        }
    </script>

    {{-- Auto-open modal if login required --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tự động mở modal nếu URL có ?login=required
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('login') === 'required') {
                const authModal = new bootstrap.Modal(document.getElementById('authModal'));
                authModal.show();

                // Xóa query parameter khỏi URL
                window.history.replaceState({}, '', window.location.pathname);
            }
        });
    </script>
</body>

</html>
