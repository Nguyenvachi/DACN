@extends('layouts.admin')

@section('title', 'Quản lý Thủ thuật')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">
            <i class="bi bi-tools me-2"></i>
            Quản lý Thủ thuật
        </h2>
        <a href="{{ route('admin.thu-thuat.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Thêm thủ thuật
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Tên thủ thuật</th>
                            <th>Giá tiền</th>
                            <th>Mô tả</th>
                            <th width="200">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td><strong>{{ $item->ten_thu_thuat }}</strong></td>
                            <td>{{ number_format($item->gia_tien ?? 0, 0, ',', '.') }} đ</td>
                            <td>{{ Str::limit($item->mo_ta ?? 'N/A', 50) }}</td>
                            <td>
                                <a href="{{ route('admin.thu-thuat.edit', $item) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.thu-thuat.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Xác nhận xóa?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Chưa có dữ liệu</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $items->links() }}
        </div>
    </div>
</div>
@endsection
