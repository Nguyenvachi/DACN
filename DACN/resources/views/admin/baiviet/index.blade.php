@extends('layouts.admin')

@section('content')

{{-- =============================
     🔥 Bổ sung: Header + nút thêm
============================= --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold">
        <i class="fas fa-newspaper me-2"></i> Danh sách Bài viết
    </h1>

    <a href="{{ route('admin.baiviet.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle"></i> Thêm bài viết
    </a>
</div>

{{-- =============================
     🔥 Bổ sung thông báo success
============================= --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- =============================
     🔥 Bổ sung: Khung card + table bootstrap
============================= --}}
<div class="card shadow-sm border-0">
    <div class="card-body">

        {{-- bảng cũ của bạn giữ nguyên, chỉ thêm class bootstrap --}}
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Tiêu đề</th>
                    <th>Danh mục</th>
                    <th>Trạng thái</th>
                    <th>Ngày xuất bản</th>
                    <th width="15%">Hành động</th>
                </tr>
            </thead>

            <tbody>
                @foreach($posts as $post)
                <tr>
                    <td>{{ $post->id }}</td>
                    <td class="fw-bold">{{ $post->title }}</td>
                    <td>
                        <span class="badge bg-info text-dark px-3 py-2">
                            {{ optional($post->danhMuc)->name ?? 'Không có' }}
                        </span>
                    </td>

                    {{-- trạng thái bài viết --}}
                    <td>
                        @if($post->status == 'published')
                            <span class="badge bg-success px-3 py-2">Đã xuất bản</span>
                        @else
                            <span class="badge bg-secondary px-3 py-2">Nháp</span>
                        @endif
                    </td>

                    <td>
                        {{ optional($post->published_at)->format('d/m/Y H:i') ?? '—' }}
                    </td>

                    <td>
                        <a href="{{ route('admin.baiviet.edit', $post) }}"
                           class="btn btn-warning btn-sm mb-1"
                           title="Sửa">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form action="{{ route('admin.baiviet.destroy', $post) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('Xóa bài viết?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm mb-1" title="Xóa">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- giữ nguyên phân trang --}}
        <div class="mt-3">
            {{ $posts->links() }}
        </div>
    </div>
</div>

{{-- =============================
     🔥 CSS Làm đẹp UI của trang bài viết
============================= --}}
<style>
    h1 {
        font-size: 28px;
    }

    table th {
        text-transform: uppercase;
        font-size: 13px;
        font-weight: 600;
    }

    .table-hover tbody tr:hover {
        background-color: #f9fafb;
    }
</style>

@endsection
