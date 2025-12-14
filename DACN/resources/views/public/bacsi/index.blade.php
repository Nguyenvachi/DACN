@php
    $isPatient = auth()->check() && auth()->user()->role === 'patient';
@endphp

@if($isPatient)
    @extends('layouts.patient-modern')

    @section('title', 'Danh Sách Bác Sĩ')
    @section('page-title', 'Đội ngũ Bác sĩ')
    @section('page-subtitle', 'Chọn bác sĩ để đặt lịch khám')

    @section('content')
@else
    <!DOCTYPE html>
    <html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Danh Sách Bác Sĩ - VietCare</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    </head>
    <body>
        {{-- Simple Navigation --}}
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <a class="navbar-brand" href="{{ route('homepage') }}">
                    <i class="fas fa-hospital me-2"></i>VietCare
                </a>
                <div class="ms-auto">
                    <a href="{{ route('homepage') }}" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-home me-1"></i>Trang Chủ
                    </a>
                    @guest
                        <button class="btn btn-light btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#authModal">
                            <i class="fas fa-sign-in-alt me-1"></i>Đăng Nhập
                        </button>
                    @endguest
                </div>
            </div>
        </nav>
@endif
    <style>
        /* CSS RIÊNG CHO TRANG DANH SÁCH BÁC SĨ */
        .doctor-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 15px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .doctor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 50, 150, 0.15);
        }

        .doctor-img-wrapper {
            height: 250px;
            /* Chiều cao cố định cho ảnh */
            overflow: hidden;
            position: relative;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .doctor-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* Đảm bảo ảnh không bị méo */
            object-position: center top;
        }

        .doctor-img-placeholder {
            font-size: 80px;
            color: #dee2e6;
        }

        .badge-specialty {
            background-color: #e7f1ff;
            color: #0d6efd;
            font-weight: 500;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            display: inline-block;
            margin-bottom: 10px;
        }

        .info-item {
            font-size: 0.9rem;
            color: #555;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
        }

        .info-item i {
            width: 25px;
            color: #0d6efd;
            text-align: center;
            margin-right: 5px;
        }

        .section-header {
            position: relative;
            margin-bottom: 40px;
            text-align: center;
        }

        .section-header::after {
            content: "";
            display: block;
            width: 60px;
            height: 3px;
            background: #0d6efd;
            margin: 15px auto 0;
        }
    </style>

    <div class="container py-5">

        {{-- HEADER --}}
        <div class="section-header">
            <h2 class="fw-bold text-primary">Đội ngũ Bác sĩ</h2>
            <p class="text-muted">Các chuyên gia y tế hàng đầu sẵn sàng hỗ trợ bạn</p>
        </div>

        {{-- SEARCH / FILTER (Nếu sau này cần thêm thì đặt ở đây) --}}

        {{-- DANH SÁCH --}}
        <div class="row g-4"> {{-- g-4 để tạo khoảng cách đều giữa các cột --}}
            @forelse($bacSis as $bacSi)
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="card h-100 doctor-card">

                        {{-- 1. AVATAR BÁC SĨ (ưu tiên avatar lưu trên user) --}}
                        <div class="doctor-img-wrapper">
                            @php
                                // Use accessor `avatar_url` if available (returns full asset URL)
                                $avatarPath = $bacSi->avatar_url ?? null;
                            @endphp
                            @if ($avatarPath)
                                <img src="{{ $avatarPath }}" alt="{{ $bacSi->ho_ten ?? optional($bacSi->user)->name }}">
                            @else
                                {{-- Ảnh giả lập nếu không có avatar --}}
                                <div class="doctor-img-placeholder">
                                    <i class="fas fa-user-md"></i>
                                </div>
                            @endif
                        </div>

                        {{-- 2. THÔNG TIN --}}
                        <div class="card-body d-flex flex-col">
                            <div class="mb-2">
                                <span class="badge-specialty">
                                    @php
                                        // ưu tiên relation chuyenKhoas, nếu không có dùng cột chuyen_khoa
                                        $spec = optional($bacSi->chuyenKhoas->first())->ten ?? $bacSi->chuyen_khoa ?? 'Sản phụ khoa';
                                    @endphp
                                    {{ $spec }}
                                </span>
                            </div>

                            <h5 class="card-title fw-bold mb-3 text-dark">
                                {{ $bacSi->ho_ten ?? optional($bacSi->user)->name }}
                            </h5>

                            {{-- Rating Section --}}
                            @php
                                $avgRating = \App\Models\DanhGia::getAverageRating($bacSi->id);
                                $totalReviews = \App\Models\DanhGia::getTotalReviews($bacSi->id);
                            @endphp
                            @if ($totalReviews > 0)
                                <div class="mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= round($avgRating))
                                                    <i class="bi bi-star-fill text-warning"></i>
                                                @else
                                                    <i class="bi bi-star text-muted"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-muted small">
                                            {{ number_format($avgRating, 1) }} ({{ $totalReviews }} đánh giá)
                                        </span>
                                    </div>
                                </div>
                            @endif

                            <div class="mb-3">
                                {{-- Kinh nghiệm --}}
                                @if (!empty($bacSi->kinh_nghiem) && $bacSi->kinh_nghiem > 0)
                                    <div class="info-item" title="Kinh nghiệm">
                                        <i class="fas fa-star"></i>
                                        <span>{{ $bacSi->kinh_nghiem }} năm kinh nghiệm</span>
                                    </div>
                                @endif

                                {{-- Số điện thoại (Chỉ hiện nếu có) --}}
                                @if (!empty($bacSi->so_dien_thoai))
                                    <div class="info-item" title="Liên hệ">
                                        <i class="fas fa-phone-alt"></i>
                                        <span>{{ $bacSi->so_dien_thoai }}</span>
                                    </div>
                                @elseif(optional($bacSi->user)->phone)
                                    <div class="info-item" title="Liên hệ">
                                        <i class="fas fa-phone-alt"></i>
                                        <span>{{ $bacSi->user->phone }}</span>
                                    </div>
                                @endif

                                {{-- Email (nếu có) --}}
                                @if (!empty($bacSi->email))
                                    <div class="info-item" title="Email">
                                        <i class="fas fa-envelope"></i>
                                        <span>{{ $bacSi->email }}</span>
                                    </div>
                                @elseif(optional($bacSi->user)->email)
                                    <div class="info-item" title="Email">
                                        <i class="fas fa-envelope"></i>
                                        <span>{{ $bacSi->user->email }}</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Mô tả ngắn --}}
                            @if ($bacSi->mo_ta)
                                <p class="card-text text-muted small mb-4" style="line-height: 1.5;">
                                    {{ Str::limit($bacSi->mo_ta, 80) }}
                                </p>
                            @endif

                            {{-- 3. BUTTON ACTION --}}
                            <div class="mt-auto">
                                @auth
                                    <a href="{{ route('lichhen.create', $bacSi->id) }}"
                                        class="btn btn-primary w-100 rounded-pill fw-bold shadow-sm mb-2">
                                        <i class="fas fa-calendar-check me-1"></i> Đặt lịch ngay
                                    </a>
                                    <a href="{{ route('public.bacsi.schedule', $bacSi->id) }}"
                                        class="btn btn-outline-info w-100 rounded-pill fw-bold">
                                        <i class="fas fa-calendar-week me-1"></i> Xem lịch rảnh
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-outline-primary w-100 rounded-pill fw-bold">
                                        <i class="fas fa-sign-in-alt me-1"></i> Đăng nhập để đặt
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="100" alt="Empty"
                            class="mb-3 opacity-50">
                        <h5 class="text-muted">Chưa tìm thấy bác sĩ nào phù hợp.</h5>
                        <p class="text-muted small">Vui lòng quay lại sau hoặc liên hệ hotline để được hỗ trợ.</p>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- PHÂN TRANG (Nếu controller có paginate) --}}
        @if (method_exists($bacSis, 'links'))
            <div class="d-flex justify-content-center mt-5">
                {{ $bacSis->links() }}
            </div>
        @endif

    </div>

@if($isPatient)
    @endsection
@else
    {{-- Include Auth Modal for Guest Users --}}
    @include('components.auth-modal')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
@endif
