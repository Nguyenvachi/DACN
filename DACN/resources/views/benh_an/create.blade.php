@php
    $role = auth()->user()->role ?? 'patient';

    // Quy tắc: mỗi role có layout riêng — mapping rõ ràng, không phụ thuộc route
    $layout = match ($role) {
        'admin' => 'layouts.admin',
        'doctor' => 'layouts.doctor',
        'staff' => 'layouts.staff',
        'patient' => 'layouts.patient-modern',
        default => 'layouts.app',
    };
@endphp

@extends($layout)
@section('content')
    <div class="container-fluid py-4">

        <h3 class="mb-4">
            <i class="bi bi-file-earmark-plus text-primary"></i>
            Thêm bệnh án
        </h3>

        {{-- HIỂN THỊ LỖI --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Vui lòng kiểm tra lại thông tin:</strong>
                <ul class="mt-2 mb-0">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">

                <form method="POST" enctype="multipart/form-data"
                    action="{{ route(auth()->user()->role === 'admin' ? 'admin.benhan.store' : 'doctor.benhan.store') }}">

                    @csrf

                    {{-- FORM FIELDS --}}
                    @include('benh_an._form')

                    {{-- BUTTONS --}}
                    <div class="mt-4 d-flex justify-content-end gap-2">

                        <a href="{{ route(auth()->user()->role === 'admin' ? 'admin.benhan.index' : 'doctor.benhan.index') }}"
                            class="btn btn-light">
                            <i class="bi bi-arrow-left"></i> Quay lại
                        </a>

                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-circle"></i> Lưu bệnh án
                        </button>

                    </div>

                </form>

            </div>
        </div>

    </div>
@endsection
