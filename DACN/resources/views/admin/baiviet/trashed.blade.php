@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold">
        <i class="fas fa-trash-restore me-2 text-warning"></i> Thùng rác Bài viết
    </h1>
    <a href="{{ route('admin.baiviet.index') }}" class="btn btn-primary">
        <i class="fas fa-arrow-left"></i> Danh sách bài viết
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow-lg border-0">
    <div class="card-body">

        @if($posts->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Tiêu đề</th>
                            <th>Danh mục</th>
                            <th>Tác giả</th>
                            <th>Đã xóa lúc</th>
                            <th width="200">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($posts as $post)
                        <tr>
                            <td>{{ $post->id }}</td>
                            <td>
                                <strong>{{ $post->title }}</strong>
                                @if($post->thumbnail)
                                    <br><img src="{{ $post->thumbnail }}" alt="" style="height: 40px; border-radius: 4px;">
                                @endif
                            </td>
                            <td>
                                @if($post->danhMuc)
                                    <span class="badge bg-secondary">{{ $post->danhMuc->name }}</span>
                                @else
                                    <span class="text-muted">--</span>
                                @endif
                            </td>
                            <td>{{ $post->author->name ?? 'N/A' }}</td>
                            <td>
                                <small class="text-muted">
                                    {{ $post->deleted_at->diffForHumans() }}
                                </small>
                            </td>
                            <td>
                                <form action="{{ route('admin.baiviet.restore', $post->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success"
                                            onclick="return confirm('Khôi phục bài viết này?')">
                                        <i class="fas fa-undo"></i> Khôi phục
                                    </button>
                                </form>

                                <form action="{{ route('admin.baiviet.forceDelete', $post->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('XÓA VĨNH VIỄN bài viết này? Không thể hoàn tác!')">
                                        <i class="fas fa-trash"></i> Xóa vĩnh viễn
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $posts->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-trash-alt fs-1 text-muted"></i>
                <p class="text-muted mt-3">Thùng rác trống</p>
            </div>
        @endif

    </div>
</div>

@endsection
