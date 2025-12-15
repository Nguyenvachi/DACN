@php
    $isPatient = auth()->check() && auth()->user()->isPatient();
@endphp

@if ($isPatient)
    @extends('layouts.patient-modern')

    @section('title', 'Đội ngũ Bác sĩ')
    @section('page-title', 'Đội ngũ chuyên gia')
    @section('page-subtitle', 'Đặt lịch khám với các bác sĩ hàng đầu')

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
            <style>
                body {
                    background-color: #f8f9fa;
                    font-family: 'Segoe UI', sans-serif;
                }

                .navbar {
                    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                }
            </style>
        </head>

        <body>
            {{-- Simple Navigation for Guest --}}
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
                <div class="container">
                    <a class="navbar-brand fw-bold" href="{{ route('homepage') }}">
                        <i class="fas fa-hospital-alt me-2"></i>VietCare
                    </a>
                    <div class="ms-auto">
                        <a href="{{ route('homepage') }}" class="btn btn-outline-light btn-sm rounded-pill px-3">
                            <i class="fas fa-home me-1"></i>Trang Chủ
                        </a>
                        @guest
                            <a href="{{ route('login') }}"
                                class="btn btn-light btn-sm ms-2 rounded-pill px-3 text-primary fw-bold">
                                <i class="fas fa-sign-in-alt me-1"></i>Đăng Nhập
                            </a>
                        @endguest
                    </div>
                </div>
            </nav>
            <div class="container py-5">
    @endif

    {{-- CSS RIÊNG (Scoped) --}}
    <style>
        .doctor-card {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            background: #fff;
            overflow: hidden;
        }

        .doctor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
            border-color: var(--bs-primary);
        }

        .doctor-avatar-container {
            position: relative;
            padding-top: 100%;
            /* 1:1 Aspect Ratio */
            background-color: #f1f3f5;
            overflow: hidden;
        }

        .doctor-avatar {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .doctor-card:hover .doctor-avatar {
            transform: scale(1.05);
        }

        .spec-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(255, 255, 255, 0.95);
            color: var(--bs-primary);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            z-index: 2;
        }

        .btn-booking {
            background-image: linear-gradient(45deg, var(--bs-primary), #0dcaf0);
            border: none;
            color: white;
        }

        .btn-booking:hover {
            background-image: linear-gradient(45deg, #0b5ed7, #0bacbe);
            color: white;
        }
    </style>

    {{-- SEARCH & FILTER BAR --}}
    <div class="card shadow-sm border-0 mb-5">
        <div class="card-body p-4">
            <form action="" method="GET">
                <div class="row g-3 align-items-center">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i
                                    class="fas fa-search text-muted"></i></span>
                            <input type="text" name="keyword" class="form-control border-start-0 ps-0"
                                placeholder="Tìm tên bác sĩ, chuyên khoa..." value="{{ request('keyword') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        @php
                            $chuyenKhoasList = $chuyenKhoas ?? \App\Models\ChuyenKhoa::all();
                            $selected = request('chuyen_khoa');
                        @endphp
                        <select class="form-select text-muted" name="chuyen_khoa">
                            <option value="">-- Chọn Chuyên Khoa --</option>
                            @foreach($chuyenKhoasList as $ck)
                                <option value="{{ $ck->slug ?? $ck->id }}" {{ $selected == ($ck->slug ?? $ck->id) ? 'selected' : '' }}>{{ $ck->ten }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100 fw-bold">
                            <i class="fas fa-filter me-1"></i>Lọc kết quả
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- LIST DOCTORS --}}
    <div class="row g-4">
        @forelse($bacSis as $bacSi)
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="doctor-card h-100 d-flex flex-column">
                    {{-- Avatar & Badge --}}
                    <div class="doctor-avatar-container">
                        @php
                            $spec = optional($bacSi->chuyenKhoas->first())->ten ?? ($bacSi->chuyen_khoa ?? 'Đa khoa');
                            $avatar = $bacSi->avatar_url ?? null; // Dùng accessor hoặc path
                        @endphp

                        <span class="spec-badge"><i class="fas fa-stethoscope me-1"></i>{{ $spec }}</span>

                        @if ($avatar)
                            <img src="{{ $avatar }}" class="doctor-avatar" alt="{{ $bacSi->ho_ten }}">
                        @else
                            <div
                                class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center text-secondary">
                                <i class="fas fa-user-md fa-4x opacity-25"></i>
                            </div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="card-body text-center d-flex flex-column flex-grow-1 p-4">
                        <h5 class="fw-bold text-dark mb-1">{{ $bacSi->ho_ten ?? optional($bacSi->user)->name }}</h5>

                        {{-- Rating Stars --}}
                        @php
                            $rating = \App\Models\DanhGia::getAverageRating($bacSi->id) ?? 0;
                            $reviews = \App\Models\DanhGia::getTotalReviews($bacSi->id) ?? 0;
                        @endphp
                        <div class="mb-3 text-warning small">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="{{ $i <= round($rating) ? 'fas' : 'far' }} fa-star"></i>
                            @endfor
                            <span class="text-muted ms-1">({{ $reviews }})</span>
                        </div>

                        {{-- Details --}}
                        <div class="mb-4 small text-muted">
                            @if ($bacSi->kinh_nghiem)
                                <div class="mb-1"><i
                                        class="fas fa-briefcase me-2 text-primary"></i>{{ $bacSi->kinh_nghiem }} năm kinh
                                    nghiệm</div>
                            @endif
                            @if ($bacSi->so_dien_thoai)
                                <div><i class="fas fa-phone me-2 text-success"></i>{{ $bacSi->so_dien_thoai }}</div>
                            @endif
                        </div>

                        {{-- Actions --}}
                        <div class="mt-auto d-grid gap-2">
                            @auth
                                <a href="{{ route('lichhen.create', $bacSi->id) }}"
                                    class="btn btn-booking rounded-pill shadow-sm py-2">
                                    <i class="fas fa-calendar-plus me-2"></i>Đặt lịch ngay
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill py-2">
                                    <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập để đặt
                                </a>
                            @endauth

                            {{-- Nút xem chi tiết / lịch rảnh --}}
                            <a href="{{ route('public.bacsi.schedule', $bacSi->id) }}"
                                class="btn btn-link text-decoration-none text-muted small">
                                Xem lịch làm việc <i class="fas fa-chevron-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="mb-3 opacity-50">
                    <i class="fas fa-user-md fa-4x text-muted"></i>
                </div>
                <h5 class="text-muted">Không tìm thấy bác sĩ nào phù hợp</h5>
                <p class="text-muted small">Vui lòng thử thay đổi điều kiện tìm kiếm.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if (method_exists($bacSis, 'links'))
        <div class="d-flex justify-content-center mt-5">
            {{ $bacSis->withQueryString()->links() }}
        </div>
    @endif

    @if (!$isPatient)
        </div> {{-- End container for guest --}}

        {{-- Footer giả lập cho Guest (nếu cần) --}}
        <footer class="bg-light py-4 mt-auto border-top">
            <div class="container text-center text-muted small">
                &copy; {{ date('Y') }} VietCare Clinic. All rights reserved.
            </div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        </body>

        </html>
    @else
    @endsection
@endif
