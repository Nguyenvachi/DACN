@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">

        {{-- ============================
         🔥 HEADER
    ============================= --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="fas fa-folder-open me-2"></i> Danh mục
            </h2>
            <a href="{{ route('admin.danhmuc.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm danh mục
            </a>
        </div>

        {{-- ============================
         🔥 Thông báo
    ============================= --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif


        {{-- ============================
         🔥 BẢNG DANH MỤC
    ============================= --}}
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">

                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="8%">ID</th>
                                <th width="40%">Tên danh mục</th>
                                <th width="32%">Slug</th>
                                <th width="20%" class="text-center">Hành động</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($items as $item)
                                <tr>
                                    <td class="fw-bold">{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td><span class="badge bg-secondary">{{ $item->slug }}</span></td>

                                    <td class="text-center">

                                        {{-- Sửa --}}
                                        <a href="{{ route('admin.danhmuc.edit', $item) }}"
                                            class="btn btn-sm btn-warning me-1"
                                            title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        {{-- Xóa --}}
                                        <form action="{{ route('admin.danhmuc.destroy', $item) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <i class="fas fa-folder-open fa-2x text-muted mb-2"></i>
                                        <p class="text-muted mb-0">Chưa có danh mục nào.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        {{-- PHÂN TRANG --}}
        <div class="mt-3">
            {{ $items->links() }}
        </div>

    </div>
@endsection
