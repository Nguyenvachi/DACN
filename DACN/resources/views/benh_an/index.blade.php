@php
    $role = auth()->user()->role ?? 'patient';

    // Quy tắc: mỗi role có layout riêng — mapping rõ ràng, không phụ thuộc route
    $layout = match ($role) {
        'admin' => 'layouts.admin',
        'doctor' => 'layouts.doctor',
        'staff' => 'layouts.staff',
        'patient' => 'layouts.patient',
        default => 'layouts.app',
    };
@endphp

@extends($layout)


@section('content')
    <div class="container-fluid py-4">

        <h3 class="mb-4">
            📄 Hồ sơ bệnh án
        </h3>

        {{-- FLASH MESSAGE --}}
        @if (session('status'))
            <div class="alert alert-success mb-4">
                {{ session('status') }}
            </div>
        @endif

        {{-- BỘ LỌC --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">

                    <div class="col-md-4">
                        <input class="form-control" type="text" name="q" value="{{ request('q') }}"
                            placeholder="Tìm tiêu đề, triệu chứng, chẩn đoán...">
                    </div>

                    <div class="col-md-2">
                        <input class="form-control" type="date" name="from" value="{{ request('from') }}">
                    </div>

                    <div class="col-md-2">
                        <input class="form-control" type="date" name="to" value="{{ request('to') }}">
                    </div>

                    @if (auth()->user()->role === 'admin')
                        <div class="col-md-2">
                            <input class="form-control" type="number" name="patient_id" value="{{ request('patient_id') }}"
                                placeholder="ID bệnh nhân">
                        </div>
                    @endif

                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">Lọc</button>
                    </div>

                </form>
            </div>
        </div>

        {{-- NÚT THÊM --}}
        @can('create', App\Models\BenhAn::class)
            <a href="{{ route(auth()->user()->role . '.benhan.create') }}" class="btn btn-success mb-3">
                <i class="bi bi-plus-circle"></i> Thêm bệnh án
            </a>
        @endcan

        {{-- TABLE --}}
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Ngày khám</th>
                            <th>Tiêu đề</th>
                            <th>Bệnh nhân</th>
                            <th>Bác sĩ</th>
                            <th class="text-end">Hành động</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($records as $r)
                            <tr>
                                <td>{{ $r->ngay_kham->format('d/m/Y') }}</td>
                                <td>{{ $r->tieu_de }}</td>
                                <td>{{ $r->benhNhan->name ?? 'N/A' }}</td>
                                <td>{{ $r->bacSi->ho_ten ?? 'N/A' }}</td>

                                <td class="text-end">

                                    {{-- Xem --}}
                                    <a href="{{ route(auth()->user()->role . '.benhan.show', $r) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> Xem
                                    </a>

                                    {{-- Sửa --}}
                                    @if (in_array(auth()->user()->role, ['admin', 'doctor']))
                                        <a href="{{ route(auth()->user()->role . '.benhan.edit', $r) }}"
                                            class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-pencil"></i> Sửa
                                        </a>
                                    @endif

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-3 text-muted">
                                    Không có hồ sơ bệnh án
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

        {{-- PHÂN TRANG --}}
        <div class="mt-4">
            {{ $records->links() }}
        </div>

    </div>
@endsection
