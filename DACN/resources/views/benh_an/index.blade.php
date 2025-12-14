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
    <div class="container-fluid px-4 py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">
                    <i class="fas fa-file-medical text-primary me-2"></i>Hồ sơ bệnh án
                </h2>
                <p class="text-muted mb-0">Quản lý và theo dõi hồ sơ khám bệnh của bạn</p>
            </div>
            @can('create', App\Models\BenhAn::class)
                <a href="{{ route(auth()->user()->role . '.benhan.create') }}" class="btn btn-success px-4 py-2 shadow-sm">
                    <i class="fas fa-plus me-2"></i>Thêm bệnh án
                </a>
            @endcan
        </div>

        {{-- FLASH MESSAGE --}}
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm border-0">
                <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- BỘ LỌC --}}
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px; overflow: hidden;">
            <div class="card-body p-4">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small mb-2">
                            <i class="fas fa-search me-1 text-primary"></i>Tìm kiếm
                        </label>
                        <input class="form-control" type="text" name="q" value="{{ request('q') }}"
                            placeholder="Tiêu đề, triệu chứng, chẩn đoán...">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold small mb-2">
                            <i class="fas fa-calendar-alt me-1 text-success"></i>Từ ngày
                        </label>
                        <input class="form-control" type="date" name="from" value="{{ request('from') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold small mb-2">
                            <i class="fas fa-calendar-check me-1 text-success"></i>Đến ngày
                        </label>
                        <input class="form-control" type="date" name="to" value="{{ request('to') }}">
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-primary w-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                            <i class="fas fa-search me-2"></i>Lọc
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="card shadow border-0" style="border-radius: 15px; overflow: hidden;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <tr>
                            <th class="border-0 py-3 ps-4">Ngày khám</th>
                            <th class="border-0 py-3">Tiêu đề</th>
                            <th class="border-0 py-3">Bệnh nhân</th>
                            <th class="border-0 py-3">Bác sĩ</th>
                            <th class="border-0 py-3 text-center pe-4">Hành động</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($records as $r)
                            <tr class="border-bottom">
                                <td class="py-3 ps-4">
                                    <span class="fw-semibold">{{ $r->ngay_kham->format('d/m/Y') }}</span>
                                </td>
                                <td class="py-3">
                                    <div class="fw-semibold">{{ $r->tieu_de }}</div>
                                </td>
                                <td class="py-3">
                                    {{ $r->benhNhan->name ?? 'N/A' }}
                                </td>
                                <td class="py-3">
                                    {{ $r->bacSi->ho_ten ?? 'N/A' }}
                                </td>

                                <td class="text-center py-3 pe-4">
                                    <div class="btn-group" role="group">
                                        {{-- Xem --}}
                                        <a href="{{ route(auth()->user()->role . '.benhan.show', $r) }}"
                                            class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        {{-- Sửa --}}
                                        @if (in_array(auth()->user()->role, ['admin', 'doctor']))
                                            <a href="{{ route(auth()->user()->role . '.benhan.edit', $r) }}"
                                                class="btn btn-sm btn-outline-success" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="fas fa-folder-open fa-3x text-muted mb-3 d-block"></i>
                                    <h5 class="text-muted">Không có hồ sơ bệnh án</h5>
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

    @push('styles')
    <style>
        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: linear-gradient(90deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
            transform: translateX(5px);
        }

        .btn-group .btn {
            transition: all 0.2s ease;
        }

        .btn-group .btn:hover {
            transform: translateY(-2px);
        }
    </style>
    @endpush
@endsection

