@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="fas fa-tags me-2"></i> Quản lý Thẻ (Tags)
            </h2>

            <a href="{{ route('admin.tag.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Thêm thẻ
            </a>
        </div>

        {{-- SUCCESS MESSAGE --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- TABLE --}}
        <div class="card shadow border-0">
            <div class="card-body p-0">

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 80px">ID</th>
                                <th>Tên thẻ</th>
                                <th>Slug</th>
                                <th style="width: 140px" class="text-center">Hành động</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($items as $item)
                                <tr>
                                    <td class="fw-semibold">{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td><span class="text-muted">{{ $item->slug }}</span></td>

                                    <td class="text-center">
                                        <a href="{{ route('admin.tag.edit', $item) }}"
                                            class="btn btn-sm btn-outline-secondary"
                                            title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form action="{{ route('admin.tag.destroy', $item) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa thẻ này?')">

                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-sm btn-outline-danger" title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fs-2 d-block mb-2"></i>
                                        Chưa có thẻ nào.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

            {{-- FOOTER --}}
            <div class="card-footer d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    @if ($items->total() > 0)
                        Hiển thị {{ $items->firstItem() }} – {{ $items->lastItem() }}
                        / {{ $items->total() }} kết quả
                    @else
                        Không có kết quả
                    @endif
                </div>

                <div>
                    {{ $items->links() }}
                </div>
            </div>

        </div>

    </div>
@endsection
