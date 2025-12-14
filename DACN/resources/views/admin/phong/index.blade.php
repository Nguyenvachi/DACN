@extends('layouts.admin')

@section('content')
    <style>
        .card-custom {
            border-radius: 12px;
            border: none;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        }
    </style>

    <div class="container-fluid py-4">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-door-closed-fill text-primary me-2"></i>
                Quản lý Phòng khám
            </h2>

            <a href="{{ route('admin.phong.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Thêm phòng
            </a>
        </div>

        {{-- FLASH MESSAGE --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- MAIN CARD --}}
        <div class="card card-custom">
            <div class="card-body p-0">

                <div class="table-responsive">
                    <table id="phongTable" class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tên phòng</th>
                                <th>Loại</th>
                                <th>Số bác sĩ</th>
                                <th style="width: 150px" class="text-center">Hành động</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($phongs as $p)
                                <tr>
                                    <td class="fw-semibold">{{ $p->ten }}</td>
                                    <td>
                                        @if($p->loaiPhong)
                                            <span class="badge bg-primary">{{ $p->loaiPhong->ten }}</span>
                                        @else
                                            <span class="badge bg-secondary">Chưa gán</span>
                                        @endif
                                    </td>
                                    <td>{{ $p->bac_sis_count }}</td>

                                    <td class="text-center text-nowrap">

                                        <a href="{{ route('admin.phong.edit', $p) }}"
                                            class="btn btn-sm btn-outline-primary me-1"
                                            title="Sửa">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <form action="{{ route('admin.phong.destroy', $p) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa phòng này?')">
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
                                        <i class="bi bi-door-closed fs-2 d-block mb-2"></i>
                                        Chưa có phòng
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
                    @if ($phongs->total() > 0)
                        Hiển thị {{ $phongs->firstItem() }} - {{ $phongs->lastItem() }} / {{ $phongs->total() }} phòng
                    @else
                        Không có dữ liệu
                    @endif
                </div>

                <div>
                    {{ $phongs->links() }}
                </div>
            </div>

        </div>

    </div>
@endsection

{{-- DataTables Script --}}
<x-datatable-script tableId="phongTable" />
