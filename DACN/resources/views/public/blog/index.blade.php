<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tin Tức - VietCare</title>
    <meta name="description" content="Tin tức và kiến thức y khoa từ VietCare">
    <meta property="og:title" content="Tin Tức - VietCare">
    <meta property="og:description" content="Tin tức và kiến thức y khoa từ VietCare">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/vietcare.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <!-- NAVBAR -->
    <nav class="vc-navbar navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('homepage') }}">
                <span class="vc-logo rounded-circle d-inline-flex align-items-center justify-content-center">
                    <i class="fas fa-heartbeat"></i>
                </span>
                <span class="vc-brand">VietCare</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#vcNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="vcNavbar">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="{{ route('homepage') }}#features">Dịch Vụ</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('homepage') }}#doctors">Bác Sĩ</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('homepage') }}#services">Chuyên Khoa</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('public.blog.index') }}">Tin Tức</a></li>

                    @auth
                        <li class="nav-item">
                            <span class="nav-link">Xin chào, {{ auth()->user()->name }}</span>
                        </li>
                        @if (auth()->user()->role === 'patient')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('patient.lichhen.index') }}">
                                    <i class="fas fa-calendar-alt me-1"></i>Lịch Hẹn
                                </a>
                            </li>
                        @endif
                        <li class="nav-item ms-2">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger">Đăng Xuất</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Đăng Ký</a>
                        </li>
                        <li class="nav-item ms-2">
                            <a class="btn btn-sm vc-btn-primary" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>Đăng Nhập
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="vc-hero position-relative" style="padding: 80px 0 60px;">
        <div class="container position-relative">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="vc-hero-title" style="font-size: 2.8rem;">
                        <i class="fas fa-newspaper me-3"></i>Tin Tức Y Khoa
                    </h1>
                    <p class="vc-hero-sub">
                        Cập nhật kiến thức sức khỏe mới nhất từ đội ngũ chuyên gia VietCare
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- BLOG CONTENT -->
    <section class="py-5 bg-white">
        <div class="container">
            <!-- Search Form -->
            <div class="row mb-4">
                <div class="col-lg-8 mx-auto">
                    <form method="get" class="d-flex gap-2">
                        <input type="text" name="q" value="{{ $q }}" placeholder="Tìm bài viết..."
                               class="form-control form-control-lg" style="border-radius: 12px;">
                        <button type="submit" class="btn vc-btn-primary px-4">
                            <i class="fas fa-search me-2"></i>Tìm
                        </button>
                    </form>
                </div>
            </div>

            <!-- Blog Posts Grid -->
            <div class="row g-4">
                @forelse($posts as $post)
                    <div class="col-lg-4 col-md-6">
                        <article class="vc-card h-100" style="overflow: hidden; transition: .3s;">
                            @if($post->thumbnail)
                                <div style="height: 220px; overflow: hidden; background: #f0fdf4;">
                                    <img src="{{ $post->thumbnail }}" class="w-100 h-100"
                                         style="object-fit: cover;" alt="{{ $post->title }}">
                                </div>
                            @else
                                <div style="height: 220px; background: linear-gradient(135deg, #10b981, #059669); display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-file-medical-alt text-white" style="font-size: 4rem; opacity: 0.3;"></i>
                                </div>
                            @endif

                            <div class="p-4">
                                <h5 class="fw-bold mb-3" style="line-height: 1.4;">
                                    <a href="{{ route('blog.show', $post) }}"
                                       class="text-decoration-none text-dark hover-green">
                                        {{ $post->title }}
                                    </a>
                                </h5>
                                <p class="text-muted mb-3" style="font-size: 0.95rem;">
                                    {{ Str::limit($post->excerpt, 120) }}
                                </p>

                                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                    <small class="text-muted">
                                        <i class="far fa-calendar me-1"></i>
                                        {{ optional($post->published_at ?? $post->created_at)->format('d/m/Y') }}
                                    </small>
                                    @if($post->danhMuc)
                                        <a href="{{ route('blog.category', $post->danhMuc) }}"
                                           class="badge bg-success text-white text-decoration-none">
                                            #{{ $post->danhMuc->name }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-inbox text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                        <p class="text-muted mt-3">Chưa có bài viết nào.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-5 d-flex justify-content-center">
                {{ $posts->links() }}
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .hover-green:hover {
            color: #10b981 !important;
        }
    </style>
</body>
</html>
