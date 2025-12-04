@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">

        {{-- =============================
         🔥 Header trang
    ============================== --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-stethoscope me-2"></i> Quản lý Chuyên khoa
            </h2>

            <a href="{{ route('admin.chuyenkhoa.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i> Thêm Chuyên khoa
            </a>
        </div>


        {{-- =============================
         🔥 Thông báo
    ============================== --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif


        {{-- =============================
         🔥 Bảng chuyên khoa
    ============================== --}}
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">

                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="35%">Tên chuyên khoa</th>
                                <th width="25%">Slug</th>
                                <th width="15%">Số bác sĩ</th>
                                <th width="25%" class="text-center">Hành động</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($khoas as $k)
                                <tr>
                                    <td class="fw-semibold">{{ $k->ten }}</td>
                                    <td><span class="badge bg-secondary">{{ $k->slug }}</span></td>
                                    <td>
                                        <span class="badge bg-info text-dark px-3 py-2">
                                            {{ $k->bac_sis_count }}
                                        </span>
                                    </td>
                                    <td class="text-center text-nowrap">

                                        <a href="{{ route('admin.chuyenkhoa.edit', $k) }}"
                                            class="btn btn-sm btn-outline-primary me-1"
                                            title="Sửa">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <form action="{{ route('admin.chuyenkhoa.destroy', $k) }}" method="post"
                                            class="d-inline" onsubmit="return confirm('Xóa chuyên khoa?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" title="Xóa">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="bi bi-folder2-open fs-1 mb-2 d-block"></i>
                                        <p class="mb-0">Chưa có chuyên khoa</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        {{-- =============================
         🔥 Pagination
    ============================== --}}
        <div class="mt-3">
            {{ $khoas->links() }}
        </div>

    </div>

    {{-- =============================
     🔥 CSS bổ sung
============================= --}}
    <style>
        .table-hover tbody tr:hover {
            background: #f8f9fc;
        }

        td .badge {
            font-size: 13px;
        }
    </style>
@endsection
