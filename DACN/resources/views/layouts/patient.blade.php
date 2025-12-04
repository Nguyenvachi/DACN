<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Giao diện Bệnh nhân - {{ config('app.name') }}</title>

    {{-- Bootstrap & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    {{-- Vite: đồng bộ với toàn hệ thống --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('meta')
    @stack('styles')

    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            background: #f7f8fb;
        }

        /* Topbar bệnh nhân */
        .patient-topbar {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        }

        .patient-topbar a {
            font-weight: 500;
            color: #333;
        }

        .patient-topbar a:hover {
            color: #0d6efd;
        }

        .patient-container {
            max-width: 1100px;
            margin: 32px auto;
            padding-left: 1rem;
            padding-right: 1rem;
        }
    </style>
</head>

<body>

    {{-- Topbar --}}
    <nav class="patient-topbar py-2">
        <div class="container d-flex justify-content-between align-items-center">

            {{-- Logo / Home --}}
            <a href="{{ route('dashboard') }}" class="h5 mb-0 text-decoration-none">
                {{ config('app.name') }}
            </a>

            {{-- User Menu --}}
            <div class="d-flex align-items-center">

                <a href="{{ route('profile.edit') }}" class="me-3 d-flex align-items-center">
                    <i class="fa-solid fa-user-circle me-1"></i>
                    {{ auth()->user()->name }}
                </a>

                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button class="btn btn-link p-0 text-danger fw-semibold">
                        <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
                    </button>
                </form>

            </div>

        </div>
    </nav>

    {{-- Main content --}}
    <main class="patient-container">
        @yield('content')
    </main>

    {{-- Script --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

</body>

</html>
