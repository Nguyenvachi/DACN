<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- Vite (đảm bảo đồng bộ với app layout) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Meta + Styles --}}
    @stack('meta')
    @stack('styles')

    <!-- ADDED: Unified design system -->
    <link rel="stylesheet" href="{{ asset('css/design-system-unified.css') }}">

    <style>
        body {
            background: #f0f2f5;
            font-family: "Segoe UI", sans-serif;
        }

        .auth-card {
            max-width: 450px;
            margin: 40px auto;
            border-radius: 12px;
            transition: 0.25s ease;
        }

        .auth-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .auth-logo img {
            width: 95px;
            opacity: 0.95;
        }
    </style>
</head>

<body>

    <div class="container d-flex flex-column align-items-center justify-content-center min-vh-100">

        {{-- Logo --}}
        <div class="auth-logo mb-3">
            <a href="/" class="d-flex justify-content-center">
                <img src="{{ asset('images/logo.png') }}" alt="App Logo">
            </a>
        </div>

        {{-- Form Card --}}
        <div class="card shadow auth-card">
            <div class="card-body">
                @yield('content')
            </div>
        </div>

    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>
