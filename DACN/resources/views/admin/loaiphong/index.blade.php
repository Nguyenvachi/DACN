@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">

        {{-- Header trang --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-building me-2"></i> Quản lý Loại Phòng
            </h2>

            <a href="{{ route('admin.loaiphong.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i> Thêm Loại Phòng
            </a>
        </div>

        {{-- Thông báo --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Bảng loại phòng --}}
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">

                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="35%">Tên loại phòng</th>
                                <th width="25%">Slug</th>
                                <th width="15%">Số phòng</th>
                                <th width="25%" class="text-center">Hành động</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($loaiPhongs as $loai)
                                <tr>
                                    <td class="fw-semibold">{{ $loai->ten }}</td>
                                    <td><span class="badge bg-secondary">{{ $loai->slug }}</span></td>
                                    <td>
                                        <span class="badge bg-info text-dark px-3 py-2">
                                            {{ $loai->phongs_count }}
                                        </span>
                                    </td>
                                    <td class="text-center text-nowrap">

                                        <a href="{{ route('admin.loaiphong.edit', $loai) }}"
                                            class="btn btn-sm btn-outline-primary me-1" title="Sửa">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <form action="{{ route('admin.loaiphong.destroy', $loai) }}" method="post"
                                            class="d-inline" onsubmit="return confirm('Xóa loại phòng này?');">
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
                                        <p class="mb-0">Chưa có loại phòng</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $loaiPhongs->links() }}
        </div>

    </div>
@endsection
