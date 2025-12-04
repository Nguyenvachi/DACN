@extends('layouts.admin')

@section('content')
    <style>
        .card-custom {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
        }
    </style>

    <div class="container-fluid py-4">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-truck text-primary me-2"></i>
                Nhà cung cấp
            </h2>

            <div>
                <a href="{{ route('admin.ncc.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Thêm nhà cung cấp
                </a>

                <a href="{{ route('admin.kho.index') }}" class="btn btn-secondary btn-sm ms-1">
                    <i class="bi bi-arrow-left"></i> Kho
                </a>
            </div>
        </div>

        {{-- ALERT --}}
        @if (session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill me-1"></i>
                {{ session('success') }}
            </div>
        @endif

        {{-- TABLE --}}
        <div class="card card-custom">
            <div class="card-body p-0">

                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 20%">Tên</th>
                                <th style="width: 15%">Email</th>
                                <th style="width: 12%">Điện thoại</th>
                                <th style="width: 20%">Địa chỉ</th>
                                <th style="width: 8%" class="text-center">Thuốc</th>
                                <th style="width: 180px"></th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($nccs as $n)
                                <tr>
                                    <td class="fw-semibold">{{ $n->ten }}</td>
                                    <td>{{ $n->email }}</td>
                                    <td>{{ $n->so_dien_thoai }}</td>
                                    <td>{{ $n->dia_chi }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">{{ $n->thuocs_count ?? 0 }}</span>
                                    </td>

                                    <td class="text-nowrap">
                                        <a href="{{ route('admin.ncc.thuocs', $n) }}"
                                            class="btn btn-sm btn-outline-info me-1"
                                            title="Quản lý thuốc">
                                            <i class="bi bi-capsule"></i>
                                        </a>

                                        <a href="{{ route('admin.ncc.edit', $n) }}"
                                            class="btn btn-sm btn-outline-primary me-1"
                                            title="Sửa">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <form action="{{ route('admin.ncc.destroy', $n) }}" method="post" class="d-inline"
                                            onsubmit="return confirm('Xóa nhà cung cấp này?');">
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
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-inboxes fs-1 d-block mb-3"></i>
                                        <p class="mb-0">Chưa có nhà cung cấp nào.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

            </div>
        </div>

        {{-- PAGINATION --}}
        @if ($nccs->hasPages())
            <div class="mt-3">
                {{ $nccs->links() }}
            </div>
        @endif

    </div>
@endsection
