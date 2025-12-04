@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-capsule me-2 text-primary"></i>
                Quản lý Thuốc
            </h2>

            <div>
                <a href="{{ route('admin.thuoc.create') }}" class="btn btn-primary btn-sm me-2">
                    <i class="bi bi-plus-lg"></i> Thêm thuốc
                </a>

                <a href="{{ route('admin.kho.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-box-seam"></i> Kho
                </a>
            </div>
        </div>

        {{-- FLASH MESSAGE --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- TABLE --}}
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">

                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tên</th>
                                <th>Hoạt chất</th>
                                <th>Hàm lượng</th>
                                <th>Đơn vị</th>
                                <th>Giá tham khảo</th>
                                <th>Tồn kho</th>
                                <th style="width: 160px;">Hành động</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($thuocs as $t)
                                <tr>
                                    <td class="fw-semibold">{{ $t->ten }}</td>
                                    <td>{{ $t->hoat_chat }}</td>
                                    <td>{{ $t->ham_luong }}</td>
                                    <td>{{ $t->don_vi }}</td>
                                    <td>{{ number_format((float) ($t->gia_tham_khao ?? 0), 0, ',', '.') }} đ</td>
                                    <td>{{ number_format((int) ($t->ton_kho ?? 0)) }}</td>

                                    <td class="text-nowrap">

                                        <a href="{{ route('admin.kho.lots', $t) }}"
                                            class="btn btn-sm btn-outline-info me-1"
                                            title="Xem lô">
                                            <i class="bi bi-boxes"></i>
                                        </a>

                                        <a href="{{ route('admin.thuoc.edit', $t) }}"
                                            class="btn btn-sm btn-outline-primary me-1"
                                            title="Sửa">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <form action="{{ route('admin.thuoc.destroy', $t) }}" method="post"
                                            onsubmit="return confirm('Xóa thuốc này?')" class="d-inline">
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
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                        Chưa có thuốc nào.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

            </div>

            {{-- FOOTER: Pagination --}}
            <div class="card-footer d-flex justify-content-between align-items-center">

                <div class="text-muted small">
                    @if ($thuocs->total() > 0)
                        Hiển thị {{ $thuocs->firstItem() }}–{{ $thuocs->lastItem() }} /
                        {{ $thuocs->total() }} thuốc
                    @else
                        Không có kết quả
                    @endif
                </div>

                <div>
                    {{ $thuocs->links() }}
                </div>

            </div>
        </div>

    </div>
@endsection
