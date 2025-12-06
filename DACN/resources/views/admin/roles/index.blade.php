@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="fas fa-user-shield me-2"></i>
                Quản lý Vai trò (Roles)
            </h2>

            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Thêm vai trò mới
            </a>
        </div>

        {{-- FLASH MESSAGE --}}
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- MAIN CARD --}}
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">

                <div class="table-responsive">
                    <table id="rolesTable" class="table table-bordered table-hover align-middle mb-0">

                        <thead class="table-light">
                            <tr>
                                <th>Tên vai trò</th>
                                <th>Số quyền</th>
                                <th>Ngày tạo</th>
                                <th style="width: 150px" class="text-center">Hành động</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($roles as $role)
                                <tr>
                                    <td class="fw-semibold">
                                        <i class="fas fa-id-badge text-primary me-1"></i>
                                        {{ $role->name }}
                                    </td>

                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $role->permissions->count() }} quyền
                                        </span>
                                    </td>

                                    <td>{{ $role->created_at->format('d/m/Y') }}</td>

                                    <td class="text-center">

                                        {{-- nút sửa --}}
                                        <a href="{{ route('admin.roles.edit', $role) }}"
                                            class="btn btn-sm btn-outline-secondary me-1"
                                            title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        {{-- nút xóa --}}
                                        <form method="POST" action="{{ route('admin.roles.destroy', $role) }}"
                                            class="d-inline" onsubmit="return confirm('Xác nhận xóa vai trò này?')">
                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-sm btn-outline-danger" title="Xóa">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="fas fa-info-circle fs-3 d-block mb-2"></i>
                                        Chưa có vai trò nào.
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>
                </div>

            </div>

            {{-- FOOTER: PAGINATION --}}
            <div class="card-footer d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    @if ($roles->total() > 0)
                        Hiển thị {{ $roles->firstItem() }} - {{ $roles->lastItem() }} trong
                        {{ $roles->total() }} vai trò
                    @else
                        Không có dữ liệu
                    @endif
                </div>

                <div>
                    {{ $roles->links() }}
                </div>
            </div>

        </div>

    </div>
@endsection

{{-- DataTables Script --}}
<x-datatable-script tableId="rolesTable" />

{{-- DataTables Script --}}
<x-datatable-script tableId="rolesTable" />
