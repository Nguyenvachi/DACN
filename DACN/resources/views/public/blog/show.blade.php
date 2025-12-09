<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Meta Title --}}
    @if($baiViet->meta_title)
        <title>{{ $baiViet->meta_title }}</title>
        <meta property="og:title" content="{{ $baiViet->meta_title }}">
        <meta name="twitter:title" content="{{ $baiViet->meta_title }}">
    @else
        <title>{{ $baiViet->title }} - VietCare</title>
        <meta property="og:title" content="{{ $baiViet->title }}">
        <meta name="twitter:title" content="{{ $baiViet->title }}">
    @endif

    {{-- Meta Description --}}
    @if($baiViet->meta_description)
        <meta name="description" content="{{ $baiViet->meta_description }}">
        <meta property="og:description" content="{{ $baiViet->meta_description }}">
        <meta name="twitter:description" content="{{ $baiViet->meta_description }}">
    @elseif($baiViet->excerpt)
        <meta name="description" content="{{ $baiViet->excerpt }}">
        <meta property="og:description" content="{{ $baiViet->excerpt }}">
        <meta name="twitter:description" content="{{ $baiViet->excerpt }}">
    @endif

    {{-- OG Image --}}
    @if($baiViet->thumbnail)
        <meta property="og:image" content="{{ url($baiViet->thumbnail) }}">
        <meta name="twitter:image" content="{{ url($baiViet->thumbnail) }}">
        <meta name="twitter:card" content="summary_large_image">
    @endif

    {{-- Canonical URL --}}
    <link rel="canonical" href="{{ route('blog.show', $baiViet->slug) }}">
    <meta property="og:url" content="{{ route('blog.show', $baiViet->slug) }}">

    {{-- Additional OG --}}
    <meta property="og:type" content="article">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="article:published_time" content="{{ optional($baiViet->published_at)->toIso8601String() }}">
    <meta property="article:modified_time" content="{{ $baiViet->updated_at->toIso8601String() }}">
    @if($baiViet->author)
        <meta property="article:author" content="{{ $baiViet->author->name }}">
    @endif

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

    <!-- BREADCRUMB -->
    <section class="py-3 bg-light">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('homepage') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('blog.index') }}">Tin tức</a></li>
                    @if($baiViet->danhMuc)
                        <li class="breadcrumb-item"><a href="{{ route('blog.category', $baiViet->danhMuc) }}">{{ $baiViet->danhMuc->name }}</a></li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($baiViet->title, 50) }}</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- ARTICLE CONTENT -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <!-- Article Header -->
                    <article>
                        <h1 class="fw-bold mb-4" style="font-size: 2.2rem; line-height: 1.3; color: #1f2937;">
                            {{ $baiViet->title }}
                        </h1>

                        <div class="d-flex align-items-center gap-3 mb-4 pb-4 border-bottom">
                            <div class="d-flex align-items-center text-muted">
                                <i class="far fa-calendar me-2"></i>
                                <small>{{ optional($baiViet->published_at ?? $baiViet->created_at)->format('d/m/Y H:i') }}</small>
                            </div>
                            @if($baiViet->danhMuc)
                                <a href="{{ route('blog.category', $baiViet->danhMuc) }}"
                                   class="badge bg-success text-decoration-none">
                                    <i class="fas fa-folder me-1"></i>{{ $baiViet->danhMuc->name }}
                                </a>
                            @endif
                        </div>

                        <!-- Featured Image -->
                        @if($baiViet->thumbnail)
                            <div class="mb-4" style="border-radius: 16px; overflow: hidden; box-shadow: 0 4px 14px rgba(0,0,0,0.1);">
                                <img src="{{ $baiViet->thumbnail }}" alt="{{ $baiViet->title }}"
                                     class="img-fluid w-100" style="max-height: 500px; object-fit: cover;">
                            </div>
                        @endif

                        <!-- Article Content -->
                        <div class="article-content mb-5" style="font-size: 1.05rem; line-height: 1.8; color: #374151;">
                            {!! $baiViet->content !!}
                        </div>

                        <!-- Tags -->
                        @if($baiViet->tags->count() > 0)
                            <div class="mb-4 pb-4 border-bottom">
                                <strong class="text-muted d-block mb-2">Tags:</strong>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($baiViet->tags as $tag)
                                        <a href="{{ route('blog.tag', $tag) }}"
                                           class="badge bg-light text-dark text-decoration-none"
                                           style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                                            <i class="fas fa-tag me-1"></i>#{{ $tag->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </article>

                    <!-- Related Posts -->
                    @if($related->count() > 0)
                        <div class="mt-5 pt-5 border-top">
                            <h3 class="fw-bold mb-4">
                                <i class="fas fa-newspaper text-success me-2"></i>Bài Viết Liên Quan
                            </h3>
                            <div class="row g-3">
                                @foreach($related as $r)
                                    <div class="col-md-6">
                                        <div class="vc-card p-3 h-100" style="transition: .3s;">
                                            <a href="{{ route('blog.show', $r) }}"
                                               class="text-decoration-none text-dark hover-green d-flex flex-column h-100">
                                                <h6 class="fw-bold mb-2">{{ $r->title }}</h6>
                                                <p class="text-muted small mb-0 mt-auto">
                                                    <i class="far fa-calendar me-1"></i>
                                                    {{ optional($r->published_at ?? $r->created_at)->format('d/m/Y') }}
                                                </p>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
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
        .article-content img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            margin: 1.5rem 0;
        }
        .article-content h2, .article-content h3 {
            margin-top: 2rem;
            margin-bottom: 1rem;
            font-weight: 700;
            color: #1f2937;
        }
        .article-content p {
            margin-bottom: 1rem;
        }
        .article-content a {
            color: #10b981;
            text-decoration: underline;
        }
        .article-content a:hover {
            color: #059669;
        }
    </style>
</body>
</html>
