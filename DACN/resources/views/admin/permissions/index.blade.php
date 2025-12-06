@extends('layouts.admin')

@section('content')
    <style>
        .card-custom {
            border-radius: 12px;
            border: none;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        }

        /* FIX Bootstrap pagination bị Tailwind reset */
        .pagination {
            display: flex !important;
            gap: 4px;
        }

        .pagination .page-item {
            list-style: none !important;
        }

        .pagination .page-link {
            padding: 6px 12px !important;
            border: 1px solid #dee2e6 !important;
            color: #0d6efd !important;
            border-radius: 4px !important;
            background: #fff !important;
        }

        .pagination .page-item.active .page-link {
            background: #0d6efd !important;
            color: white !important;
            border-color: #0d6efd !important;
        }

        .pagination .page-item.disabled .page-link {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>

    <div class="container-fluid py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-shield-lock-fill text-primary me-2"></i>
                Quản lý Quyền (Permissions)
            </h2>

            <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Thêm quyền mới
            </a>
        </div>

        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card card-custom">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="permissionsTable" class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tên quyền</th>
                                <th>Guard</th>
                                <th>Ngày tạo</th>
                                <th style="width: 120px" class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse ($permissions as $permission)
                                <tr>
                                    <td>{{ $permission->name }}</td>
                                    <td>{{ $permission->guard_name }}</td>
                                    <td>{{ $permission->created_at->format('d/m/Y') }}</td>

                                    <td class="text-center">
                                        <form method="POST" action="{{ route('admin.permissions.destroy', $permission) }}"
                                            class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa quyền này?')">
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
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="bi bi-inboxes fs-2 d-block mb-2"></i>
                                        Chưa có quyền nào
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-between align-items-center">
                <div class="small text-muted">
                    @if ($permissions->total())
                        Hiển thị {{ $permissions->firstItem() }} - {{ $permissions->lastItem() }}
                        / {{ $permissions->total() }}
                    @else
                        Không có kết quả
                    @endif
                </div>

                <div>
                    {{ $permissions->links() }}
                </div>
            </div>
        </div>

    </div>
@endsection

{{-- DataTables Script --}}
<x-datatable-script tableId="permissionsTable" />

{{-- DataTables Script --}}
<x-datatable-script tableId="permissionsTable" />
