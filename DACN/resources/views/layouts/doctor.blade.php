<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bác sĩ - {{ config('app.name') }}</title>

    {{-- Bootstrap + Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    @stack('meta')
    @stack('styles')

    <style>
        body {
            background: #f5f6fa;
            font-family: "Segoe UI", sans-serif;
        }

        /* Sidebar bác sĩ */
        .doctor-sidebar {
            width: 220px;
            height: 100vh;
            background: #ffffff;
            border-right: 1px solid #ddd;
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
        }

        .doctor-sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .doctor-sidebar li {
            margin-bottom: 10px;
        }

        .doctor-sidebar a {
            display: block;
            padding: 9px 12px;
            border-radius: 6px;
            text-decoration: none;
            color: #333;
            font-weight: 500;
        }

        .doctor-sidebar a:hover {
            background: #e9ecef;
        }

        .doctor-sidebar a.active {
            background: #0d6efd;
            color: #fff;
        }

        /* Nội dung */
        main {
            margin-left: 240px;
            padding: 20px;
        }
    </style>
</head>

<body>

    {{-- SIDEBAR --}}
    <nav class="doctor-sidebar">
        <h5 class="fw-bold mb-3"><i class="fas fa-user-md me-2"></i>Bác sĩ</h5>

        <ul>
            <li><a href="{{ route('doctor.calendar.index') }}">📅 Lịch làm việc</a></li>
            <li><a href="{{ route('doctor.benhan.index') }}">📋 Bệnh án</a></li>
            <li><a href="{{ route('lichhen.my') }}">📥 Lịch hẹn của tôi</a></li>
            <li><a href="{{ route('profile.edit') }}">⚙️ Hồ sơ</a></li>

            <hr>

            <li><a href="{{ route('home') }}">🏠 Trang chủ</a></li>

            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-link p-0" type="submit">🔒 Đăng xuất</button>
                </form>
            </li>
        </ul>
    </nav>

    {{-- HEADER GIỐNG ADMIN + PATIENT --}}
    @if (View::hasSection('header'))
        <header class="bg-white shadow-sm mb-3">
            <div class="max-w-7xl mx-auto py-3 px-4">
                @yield('header')
            </div>
        </header>
    @endif

    {{-- MAIN CONTENT --}}
    <main>
        <div class="max-w-7xl mx-auto">
            @yield('content')
        </div>
    </main>

    {{-- JS --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')

    {{-- Active menu --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.doctor-sidebar');
            if (!sidebar) return;

            const links = sidebar.querySelectorAll('a');
            const normalize = href => {
                const a = document.createElement('a');
                a.href = href;
                return a.pathname.replace(/\/$/, '');
            };
            const current = normalize(window.location.href);

            links.forEach(link => {
                const path = normalize(link.href);
                if (current === path || current.startsWith(path + '/')) {
                    link.classList.add('active');
                }
            });
        });
    </script>

</body>

</html>
