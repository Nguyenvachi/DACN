<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phòng Khám Đa Khoa - Chăm Sóc Sức Khỏe Toàn Diện</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: #1f2937;
            overflow-x: hidden;
        }

        /* Header */
        .navbar {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #10b981 !important;
        }

        .navbar-brand i {
            color: #059669;
        }

        .nav-link {
            font-weight: 500;
            color: #4b5563 !important;
            margin: 0 0.5rem;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: #10b981 !important;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #10b981, #059669);
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            color: white;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            padding: 100px 0 80px;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,144C960,149,1056,139,1152,128C1248,117,1344,107,1392,101.3L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            opacity: 0.3;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            color: white;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .hero-subtitle {
            font-size: 1.3rem;
            color: rgba(255, 255, 255, 0.95);
            margin-bottom: 2rem;
            font-weight: 400;
        }

        .hero-stats {
            display: flex;
            gap: 3rem;
            margin-top: 3rem;
        }

        .stat-item {
            text-align: center;
            color: white;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            display: block;
        }

        .stat-label {
            font-size: 0.95rem;
            opacity: 0.9;
            margin-top: 0.5rem;
        }

        /* Feature Cards */
        .features-section {
            padding: 80px 0;
            background: #f9fafb;
        }

        .feature-card {
            background: white;
            border-radius: 16px;
            padding: 2.5rem;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            border: 2px solid transparent;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(16, 185, 129, 0.15);
            border-color: #10b981;
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #10b981, #059669);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: white;
        }

        .feature-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1rem;
        }

        .feature-description {
            color: #6b7280;
            line-height: 1.7;
            font-size: 1rem;
        }

        /* Doctor Section */
        .doctors-section {
            padding: 80px 0;
            background: white;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            text-align: center;
            margin-bottom: 1rem;
            color: #1f2937;
        }

        .section-subtitle {
            text-align: center;
            color: #6b7280;
            font-size: 1.1rem;
            margin-bottom: 4rem;
        }

        .doctor-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            border: 1px solid #e5e7eb;
        }

        .doctor-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .doctor-image {
            width: 100%;
            height: 280px;
            object-fit: cover;
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .doctor-info {
            padding: 1.5rem;
        }

        .doctor-name {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .doctor-specialty {
            color: #10b981;
            font-weight: 600;
            margin-bottom: 0.8rem;
        }

        .doctor-description {
            color: #6b7280;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        /* Services Section */
        .services-section {
            padding: 80px 0;
            background: #f9fafb;
        }

        .service-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            border-left: 4px solid #10b981;
            transition: all 0.3s;
            height: 100%;
        }

        .service-card:hover {
            transform: translateX(10px);
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.1);
        }

        .service-icon {
            font-size: 2rem;
            color: #10b981;
            margin-bottom: 1rem;
        }

        .service-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.8rem;
        }

        .service-description {
            color: #6b7280;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        /* Testimonials Section */
        .testimonials-section {
            background: linear-gradient(135deg, #1f2937, #111827);
            padding: 80px 0;
        }

        .testimonial-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 2rem;
            height: 100%;
            transition: all 0.3s;
        }

        .testimonial-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.08);
        }

        .testimonial-text {
            color: #e5e7eb;
            font-style: italic;
            line-height: 1.8;
            margin-bottom: 1.5rem;
        }

        .avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #10b981, #059669);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.3rem;
        }

        .testimonial-card h6 {
            color: white;
            font-weight: 600;
        }

        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, #10b981, #059669);
            padding: 80px 0;
            color: white;
            text-align: center;
        }

        .cta-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
        }

        .cta-description {
            font-size: 1.2rem;
            margin-bottom: 2.5rem;
            opacity: 0.95;
        }

        .btn-white-custom {
            background: white;
            color: #10b981;
            padding: 0.8rem 2.5rem;
            border-radius: 8px;
            font-weight: 700;
            border: none;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .btn-white-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            color: #059669;
        }

        /* Footer */
        footer {
            background: #1f2937;
            color: white;
            padding: 60px 0 30px;
        }

        .footer-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #10b981;
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 0.8rem;
        }

        .footer-links a {
            color: #d1d5db;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: #10b981;
        }

        .footer-bottom {
            border-top: 1px solid #374151;
            margin-top: 3rem;
            padding-top: 2rem;
            text-align: center;
            color: #9ca3af;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.1rem;
            }

            .hero-stats {
                flex-direction: column;
                gap: 1.5rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .feature-card {
                margin-bottom: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-heartbeat"></i> Phòng Khám Đa Khoa
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Dịch Vụ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#doctors">Bác Sĩ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#services">Chuyên Khoa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('public.blog.index') }}">Tin Tức</a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">
                                <i class="fas fa-user-circle me-1"></i> {{ auth()->user()->name }}
                            </a>
                        </li>
                    @else
                        <li class="nav-item ms-2">
                            <a href="{{ route('login') }}" class="btn btn-primary-custom">
                                <i class="fas fa-sign-in-alt me-2"></i>Đăng Nhập
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content">
                    <h1 class="hero-title">
                        Chăm Sóc Sức Khỏe<br>Toàn Diện
                    </h1>
                    <p class="hero-subtitle">
                        Đội ngũ bác sĩ chuyên nghiệp, trang thiết bị hiện đại, dịch vụ tận tâm.
                        Đặt lịch khám online nhanh chóng, tiện lợi.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('public.bacsi.index') }}" class="btn btn-white-custom btn-lg">
                            <i class="fas fa-calendar-check me-2"></i>Đặt Lịch Ngay
                        </a>
                        <a href="#services" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-info-circle me-2"></i>Tìm Hiểu Thêm
                        </a>
                    </div>
                    <div class="hero-stats">
                        @php
                            $doctorCount = \App\Models\BacSi::where('trang_thai', 'Đang hoạt động')->count();
                            $patientCount = \App\Models\User::where('role', 'patient')->count();
                            $appointmentCount = \App\Models\LichHen::count();
                        @endphp
                        <div class="stat-item">
                            <span class="stat-number">{{ $doctorCount }}+</span>
                            <span class="stat-label">Bác Sĩ Chuyên Khoa</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">{{ number_format($patientCount) }}+</span>
                            <span class="stat-label">Bệnh Nhân Tin Tưởng</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">{{ number_format($appointmentCount) }}+</span>
                            <span class="stat-label">Lượt Khám</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 text-center d-none d-lg-block">
                    <i class="fas fa-hospital-user" style="font-size: 20rem; color: rgba(255,255,255,0.2);"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section - Dịch vụ công khai cho tất cả khách -->
    <section id="features" class="features-section">
        <div class="container">
            <h2 class="section-title">CAM KẾT ĐIỀU TRỊ DỨT ĐIỂM CÁC BỆNH LÝ TOÀN DIỆN</h2>
            <p class="section-subtitle">Trải nghiệm dịch vụ y tế hiện đại, chuyên nghiệp</p>

            <div class="row justify-content-center">
                <!-- Đăng ký khám - Link đến danh sách bác sĩ -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <a href="{{ route('public.bacsi.index') }}" class="text-decoration-none">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-eye"></i>
                            </div>
                            <h3 class="feature-title">ĐĂNG KÝ KHÁM</h3>
                            <p class="feature-description">
                                Đặt lịch khám online nhanh chóng, chọn bác sĩ và giờ khám phù hợp.
                                Nhận xác nhận tức thì qua email.
                            </p>
                        </div>
                    </a>
                </div>

                <!-- Phẫu thuật - Link đến danh sách dịch vụ -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <a href="{{ route('public.dichvu.index') }}" class="text-decoration-none">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-heartbeat"></i>
                            </div>
                            <h3 class="feature-title">PHẪU THUẬT</h3>
                            <p class="feature-description">
                                Dịch vụ phẫu thuật chuyên sâu với đội ngũ bác sĩ giàu kinh nghiệm,
                                trang thiết bị hiện đại, an toàn tuyệt đối.
                            </p>
                        </div>
                    </a>
                </div>

                <!-- Xét nghiệm - Link đến danh sách dịch vụ -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <a href="{{ route('public.dichvu.index') }}" class="text-decoration-none">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-key"></i>
                            </div>
                            <h3 class="feature-title">XÉT NGHIỆM</h3>
                            <p class="feature-description">
                                Xét nghiệm chính xác, kết quả nhanh chóng.
                                Hệ thống máy móc hiện đại, đội ngũ kỹ thuật viên chuyên nghiệp.
                            </p>
                        </div>
                    </a>
                </div>

                <!-- Viêm khám - Link đến cửa hàng thuốc -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <a href="{{ auth()->check() && auth()->user()->role === 'patient' ? route('patient.shop.index') : route('login') }}" class="text-decoration-none">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-notes-medical"></i>
                            </div>
                            <h3 class="feature-title">VIÊM KHÁM</h3>
                            <p class="feature-description">
                                Mua thuốc online tiện lợi, đầy đủ chủng loại.
                                Giao hàng tận nơi, tư vấn miễn phí từ dược sĩ.
                            </p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Process Section - Quy trình điều trị -->
    <section class="py-5" style="background: white;">
        <div class="container">
            <h2 class="section-title">QUY TRÌNH ĐIỀU TRỊ TẠI VIỆT CARE</h2>
            <p class="section-subtitle">Phòng khám Việt Care cam kết mang đến trải nghiệm tốt nhất</p>

            <div class="row mt-5">
                <div class="col-lg-2 col-md-4 col-6 mb-4 text-center">
                    <div class="process-icon mb-3">
                        <i class="fas fa-heartbeat" style="font-size: 3rem; color: #10b981;"></i>
                    </div>
                    <h5 class="fw-bold" style="color: #1f2937; font-size: 0.9rem;">Ưu tiên khám</h5>
                </div>
                <div class="col-lg-2 col-md-4 col-6 mb-4 text-center">
                    <div class="process-icon mb-3">
                        <i class="fas fa-laptop-medical" style="font-size: 3rem; color: #10b981;"></i>
                    </div>
                    <h5 class="fw-bold" style="color: #1f2937; font-size: 0.9rem;">Hẹp pháp</h5>
                </div>
                <div class="col-lg-2 col-md-4 col-6 mb-4 text-center">
                    <div class="process-icon mb-3">
                        <i class="fas fa-hands-helping" style="font-size: 3rem; color: #10b981;"></i>
                    </div>
                    <h5 class="fw-bold" style="color: #1f2937; font-size: 0.9rem;">Chăm sóc an dân</h5>
                </div>
                <div class="col-lg-2 col-md-4 col-6 mb-4 text-center">
                    <div class="process-icon mb-3">
                        <i class="fas fa-phone-alt" style="font-size: 3rem; color: #10b981;"></i>
                    </div>
                    <h5 class="fw-bold" style="color: #1f2937; font-size: 0.9rem;">Tiện lợi</h5>
                </div>
                <div class="col-lg-2 col-md-4 col-6 mb-4 text-center">
                    <div class="process-icon mb-3">
                        <i class="fas fa-user-check" style="font-size: 3rem; color: #10b981;"></i>
                    </div>
                    <h5 class="fw-bold" style="color: #1f2937; font-size: 0.9rem;">Nguồn nhân viên <br>chất lượng cao</h5>
                </div>
            </div>
        </div>
    </section>

    <!-- Achievement Stats -->
    <section class="py-5" style="background: #f0fdf4;">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4 mb-4">
                    <div class="stat-box">
                        <div class="stat-icon mb-3">
                            <i class="fas fa-users" style="font-size: 3.5rem; color: #10b981;"></i>
                        </div>
                        <h2 class="fw-bold" style="color: #10b981;">500+</h2>
                        <p class="text-muted">Khách hàng đáng <br>điều trị</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="stat-box">
                        <div class="stat-icon mb-3">
                            <i class="fas fa-procedures" style="font-size: 3.5rem; color: #10b981;"></i>
                        </div>
                        <h2 class="fw-bold" style="color: #10b981;">7.000+</h2>
                        <p class="text-muted">Khách hàng hài <br>lòng</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="stat-box">
                        <div class="stat-icon mb-3">
                            <i class="fas fa-check-circle" style="font-size: 3.5rem; color: #10b981;"></i>
                        </div>
                        <h2 class="fw-bold" style="color: #10b981;">99%</h2>
                        <p class="text-muted">Khách hàng hài lòng <br>về dịch vụ</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Doctors Section -->
    <section id="doctors" class="doctors-section">
        <div class="container">
            <h2 class="section-title">Đội Ngũ Bác Sĩ</h2>
            <p class="section-subtitle">Đội ngũ chuyên gia y tế hàng đầu</p>

            <div class="row">
                @php
                    $doctors = \App\Models\BacSi::where('trang_thai', 'Đang hoạt động')
                        ->with('chuyenKhoas')
                        ->limit(3)
                        ->get();
                @endphp

                @forelse($doctors as $doctor)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="doctor-card">
                        <div class="doctor-image">
                            @if($doctor->anh_dai_dien)
                                <img src="{{ asset('storage/' . $doctor->anh_dai_dien) }}" alt="{{ $doctor->ho_ten }}" class="w-100 h-100" style="object-fit: cover;">
                            @else
                                <div class="d-flex align-items-center justify-content-center h-100">
                                    <i class="fas fa-user-md text-white" style="font-size: 5rem;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="doctor-info">
                            <h3 class="doctor-name">{{ $doctor->ho_ten }}</h3>
                            <p class="doctor-specialty">
                                @if($doctor->chuyenKhoas->count() > 0)
                                    {{ $doctor->chuyenKhoas->pluck('ten_chuyen_khoa')->join(', ') }}
                                @else
                                    Bác sĩ đa khoa
                                @endif
                            </p>
                            <p class="doctor-description">
                                {{ Str::limit($doctor->mo_ta ?? 'Bác sĩ giàu kinh nghiệm, tận tâm với bệnh nhân.', 100) }}
                            </p>
                            <a href="{{ route('lichhen.create', $doctor->id) }}" class="btn btn-primary-custom w-100 mt-3">
                                <i class="fas fa-calendar-plus me-2"></i>Đặt Lịch Khám
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center">
                    <p class="text-muted">Đang cập nhật thông tin đội ngũ bác sĩ...</p>
                </div>
                @endforelse
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('public.bacsi.index') }}" class="btn btn-primary-custom btn-lg">
                    <i class="fas fa-users me-2"></i>Xem Tất Cả Bác Sĩ
                </a>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="services-section">
        <div class="container">
            <h2 class="section-title">Chuyên Khoa</h2>
            <p class="section-subtitle">Đa dạng các chuyên khoa điều trị</p>

            <div class="row">
                @php
                    $specialties = \App\Models\ChuyenKhoa::withCount('bacSis')
                        ->having('bac_sis_count', '>', 0)
                        ->limit(6)
                        ->get();

                    $specialtyIcons = [
                        'Tim Mạch' => 'fa-heart-pulse',
                        'Thần Kinh' => 'fa-brain',
                        'Cơ Xương Khớp' => 'fa-bone',
                        'Hô Hấp' => 'fa-lungs',
                        'Tiêu Hóa' => 'fa-stomach',
                        'Nhi Khoa' => 'fa-baby',
                        'Sản Phụ Khoa' => 'fa-person-pregnant',
                        'Da Liễu' => 'fa-spa',
                        'Tai Mũi Họng' => 'fa-ear-listen',
                        'Mắt' => 'fa-eye',
                        'Răng Hàm Mặt' => 'fa-tooth',
                        'default' => 'fa-stethoscope'
                    ];
                @endphp

                @forelse($specialties as $specialty)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="service-card">
                        <div class="service-icon">
                            @php
                                $icon = 'fa-stethoscope';
                                foreach($specialtyIcons as $key => $val) {
                                    if(str_contains($specialty->ten_chuyen_khoa, $key)) {
                                        $icon = $val;
                                        break;
                                    }
                                }
                            @endphp
                            <i class="fas {{ $icon }}"></i>
                        </div>
                        <h3 class="service-title">{{ $specialty->ten_chuyen_khoa }}</h3>
                        <p class="service-description">
                            {{ $specialty->mo_ta ?? 'Chuyên khoa ' . strtolower($specialty->ten_chuyen_khoa) . ' với đội ngũ bác sĩ giàu kinh nghiệm.' }}
                        </p>
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="fas fa-user-doctor me-1"></i>{{ $specialty->bac_sis_count }} bác sĩ
                            </small>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center">
                    <p class="text-muted">Đang cập nhật thông tin chuyên khoa...</p>
                </div>
                @endforelse
            </div>

            <div class="text-center mt-5">
                <a href="{{ route('public.bacsi.index') }}" class="btn btn-primary-custom btn-lg">
                    <i class="fas fa-search me-2"></i>Tìm Bác Sĩ Theo Chuyên Khoa
                </a>
            </div>
        </div>
    </section>

    <!-- Contact Form Section - Nhận tư vấn miễn phí -->
    <section class="py-5" style="background: linear-gradient(135deg, #f0fdf4, #dcfce7);">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="https://via.placeholder.com/600x400" alt="Tư vấn miễn phí" class="img-fluid rounded shadow" style="width: 100%; height: auto; object-fit: cover;">
                </div>
                <div class="col-lg-6">
                    <h2 class="fw-bold mb-4" style="color: #10b981; font-size: 2rem;">NHẬN Tư VẤN MIỄN PHÍ</h2>
                    <form action="#" method="POST" class="consultation-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input type="text" name="ho" class="form-control" placeholder="Họ*" required style="padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="text" name="ten" class="form-control" placeholder="Tên*" required style="padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <select name="tinh" class="form-select" style="padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
                                    <option value="">Tỉnh*</option>
                                    <option value="TP.HCM">TP. Hồ Chí Minh</option>
                                    <option value="Hanoi">Hà Nội</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <select name="huyen" class="form-select" style="padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
                                    <option value="">Quận/Huyện*</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <select name="phuong" class="form-select" style="padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
                                    <option value="">Phường/Xã*</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="dia_chi" class="form-control" placeholder="Địa chỉ cụ thể*" required style="padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input type="tel" name="so_dien_thoai" class="form-control" placeholder="Số điện thoại*" required style="padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="email" name="email" class="form-control" placeholder="Email theo vẫn*" style="padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
                            </div>
                        </div>
                        <div class="mb-3">
                            <textarea name="ghi_chu" class="form-control" rows="3" placeholder="Ghi chú" style="padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;"></textarea>
                        </div>
                        <button type="submit" class="btn w-100" style="background: #10b981; color: white; padding: 14px; font-weight: 600; border-radius: 8px; border: none;">
                            <i class="fas fa-paper-plane me-2"></i>GỬI
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <div class="container">
            <h2 class="section-title text-white">Đánh Giá Từ Bệnh Nhân</h2>
            <p class="section-subtitle text-white-50">Trải nghiệm thực tế của những người đã sử dụng dịch vụ</p>

            <div class="row">
                @php
                    $reviews = \App\Models\DanhGia::with(['user', 'bacSi'])
                        ->where('rating', '>=', 4)
                        ->where('trang_thai', 'approved')
                        ->latest()
                        ->limit(3)
                        ->get();
                @endphp

                @forelse($reviews as $review)
                <div class="col-lg-4 mb-4">
                    <div class="testimonial-card">
                        <div class="stars mb-3">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                            @endfor
                        </div>
                        <p class="testimonial-text">"{{ Str::limit($review->noi_dung, 150) }}"</p>
                        <div class="d-flex align-items-center mt-3">
                            <div class="avatar me-3">
                                {{ substr($review->user->name ?? 'U', 0, 1) }}
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $review->user->name ?? 'Bệnh nhân' }}</h6>
                                <small class="text-muted">Khám bệnh với {{ $review->bacSi->ho_ten ?? 'Bác sĩ' }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center">
                    <p class="text-white-50">Chưa có đánh giá nào</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2 class="cta-title">Sẵn Sàng Chăm Sóc Sức Khỏe?</h2>
            <p class="cta-description">
                Đặt lịch khám ngay hôm nay để được tư vấn và điều trị bởi đội ngũ chuyên gia
            </p>
            <a href="{{ route('public.bacsi.index') }}" class="btn btn-white-custom btn-lg">
                <i class="fas fa-calendar-check me-2"></i>Đặt Lịch Ngay
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h3 class="footer-title">
                        <i class="fas fa-heartbeat me-2"></i>Phòng Khám Đa Khoa
                    </h3>
                    <p class="text-light">
                        Chăm sóc sức khỏe toàn diện với đội ngũ bác sĩ chuyên nghiệp
                        và trang thiết bị hiện đại.
                    </p>
                    <div class="mt-3">
                        <a href="#" class="text-light me-3"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-youtube fa-lg"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="footer-title">Liên Kết</h5>
                    <ul class="footer-links">
                        <li><a href="#features">Dịch Vụ</a></li>
                        <li><a href="#doctors">Bác Sĩ</a></li>
                        <li><a href="#services">Chuyên Khoa</a></li>
                        <li><a href="{{ route('public.blog.index') }}">Tin Tức</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="footer-title">Dịch Vụ</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('public.bacsi.index') }}">Đặt Lịch Khám</a></li>
                        <li><a href="{{ route('public.dichvu.index') }}">Danh Sách Dịch Vụ</a></li>
                        <li><a href="#">Mua Thuốc Online</a></li>
                        <li><a href="#">Tư Vấn Online</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="footer-title">Liên Hệ</h5>
                    <ul class="footer-links">
                        <li><i class="fas fa-map-marker-alt me-2 text-primary"></i>123 Đường ABC, Quận 1, TP.HCM</li>
                        <li><i class="fas fa-phone me-2 text-primary"></i>1900 xxxx</li>
                        <li><i class="fas fa-envelope me-2 text-primary"></i>contact@phongkham.vn</li>
                        <li><i class="fas fa-clock me-2 text-primary"></i>24/7</li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p class="mb-0">&copy; {{ date('Y') }} Phòng Khám Đa Khoa. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
