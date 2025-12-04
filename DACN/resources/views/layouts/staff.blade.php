<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhân viên - {{ config('app.name') }}</title>

    {{-- Bootstrap + Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    {{-- Vite: đồng bộ toàn hệ thống --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

    <style>
        body {
            background: #f5f6fa;
            font-family: "Segoe UI", sans-serif;
        }

        /* Navbar nâng cấp */
        .staff-navbar {
            background: #ffffff !important;
            border-bottom: 1px solid #e5e7eb;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .staff-navbar .nav-link {
            color: #333 !important;
            font-weight: 500;
            padding-left: 12px !important;
            padding-right: 12px !important;
        }

        .staff-navbar .nav-link.active {
            color: #0d6efd !important;
            font-weight: 600;
        }

        footer {
            font-size: 14px;
        }
    </style>
</head>

<body>

    {{-- TOP NAV --}}
    <nav class="navbar navbar-expand-lg staff-navbar">
        <div class="container-fluid">

            {{-- Brand --}}
            <a class="navbar-brand fw-bold" href="{{ route('staff.dashboard') }}">
                <i class="fa-solid fa-user-tie me-1"></i> Nhân viên Phòng khám
            </a>

            {{-- Toggle --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            {{-- Menu --}}
            <div class="collapse navbar-collapse" id="navbarNav">

                <ul class="navbar-nav me-auto">

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}"
                            href="{{ route('staff.dashboard') }}">
                            <i class="fa-solid fa-chart-line me-1"></i> Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('staff.schedule') ? 'active' : '' }}"
                            href="{{ route('staff.dashboard') }}#lich">
                            <i class="fa-solid fa-calendar-days me-1"></i> Lịch của tôi
                        </a>
                    </li>

                </ul>

                {{-- USER DROPDOWN --}}
                <ul class="navbar-nav">

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
                            data-bs-toggle="dropdown">
                            <i class="fa-solid fa-circle-user me-1"></i>
                            {{ auth()->user()->name }}
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end shadow">

                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fa-solid fa-user-gear me-1"></i> Hồ sơ của tôi
                                </a>
                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger fw-semibold">
                                        <i class="fa-solid fa-right-from-bracket me-1"></i> Đăng xuất
                                    </button>
                                </form>
                            </li>

                        </ul>
                    </li>

                </ul>

            </div>

        </div>
    </nav>

    {{-- MAIN CONTENT --}}
    <main class="py-4">

        @if (session('status'))
            <div class="container">
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @yield('content')

    </main>

    {{-- FOOTER --}}
    <footer class="bg-white text-center py-3 mt-4 border-top">
        <p class="mb-0 text-muted">
            © {{ date('Y') }} Phòng khám — Staff Portal
        </p>
    </footer>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

</body>

</html>
