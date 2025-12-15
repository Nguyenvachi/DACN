@php
    $role = auth()->check() ? auth()->user()->roleKey() : 'patient';
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

        <div class="d-flex align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-pencil-square text-warning me-2"></i>
                Sửa bệnh án
            </h2>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-5">
            @php
                $benhAn = $record;
                $actionButtons = '';
                if (auth()->check()) {
                    if (auth()->user()->isDoctor() && $benhAn->lichHen) {
                        if ($benhAn->lichHen->canCompleteExam()) {
                            $actionButtons .= '<form method="POST" action="' . route('doctor.lichhen.complete', $benhAn->lichHen->id) . '" class="d-inline me-2">' . csrf_field() . '<button class="btn btn-danger">Hoàn tất khám</button></form>';
                        }
                    }
                    if (auth()->user()->isAdmin() || auth()->user()->isDoctor()) {
                        $actionButtons .= '<a href="' . route(auth()->user()->isAdmin() ? 'admin.benhan.audit' : 'doctor.benhan.audit', $benhAn) . '" class="btn btn-secondary">Lịch sử</a>';
                    }
                }
            @endphp
            @include('benh_an._header', ['actionButtons' => $actionButtons, 'benhAn' => $benhAn])

            {{-- Hiển thị lỗi --}}
            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <strong>Vui lòng kiểm tra lại:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" enctype="multipart/form-data"
                action="{{ route(auth()->user()->isAdmin() ? 'admin.benhan.update' : 'doctor.benhan.update', $record) }}">
                @csrf
                @method('PUT')

                {{-- FORM FIELDS --}}
                @include('benh_an._form', ['record' => $record])

                {{-- Buttons --}}
                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route(auth()->user()->isAdmin() ? 'admin.benhan.index' : 'doctor.benhan.index') }}"
                        class="btn btn-light">
                        <i class="bi bi-arrow-left"></i> Quay lại
                    </a>

                    <button type="submit" class="btn btn-warning px-4">
                        <i class="bi bi-save"></i> Cập nhật bệnh án
                    </button>
                </div>
            </form>

        </div>

    </div>
@endsection
