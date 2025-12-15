@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">

        {{-- ============================
         🔥 TIÊN ĐỀ + THÔNG BÁO
    ============================= --}}
        <h1 class="fw-bold mb-4">
            <i class="bi bi-heart-pulse me-2"></i>Quản lý Dịch vụ
        </h1>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif


        {{-- ============================
         🔥 DANH SÁCH DỊCH VỤ
    ============================= --}}
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold">
                    <i class="bi bi-list-ul me-2"></i>Danh sách dịch vụ
                </h5>

                <a href="{{ route('admin.dich-vu.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg me-1"></i> Thêm dịch vụ
                </a>
            </div>

            <div class="card-body">

                <div class="table-responsive">
                    <table id="dichvuTable" class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">ID</th>
                                <th width="22%">Tên dịch vụ</th>
                                <th width="28%">Mô tả</th>
                                <th width="15%">Chuyên khoa</th>
                                <th width="8%">Thời gian<br>(phút)</th>
                                <th width="12%">Giá (VNĐ)</th>
                                <th width="10%" class="text-center">Hành động</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($dsDichVu as $dichVu)
                                <tr>
                                    <td>{{ $dichVu->id }}</td>

                                    <td class="fw-semibold">
                                        {{ $dichVu->ten_dich_vu }}
                                    </td>

                                    <td>{{ Str::limit($dichVu->mo_ta, 100) }}</td>

                                    <td>
                                        {{ $dichVu->chuyenKhoas && $dichVu->chuyenKhoas->isNotEmpty() ? $dichVu->chuyenKhoas->pluck('ten')->join(', ') : '-' }}
                                    </td>

                                    <td>{{ $dichVu->thoi_gian_uoc_tinh }}</td>

                                    <td>{{ number_format($dichVu->gia, 0, ',', '.') }}</td>

                                    <td class="text-center text-nowrap">
                                        <a href="{{ route('admin.dich-vu.edit', $dichVu) }}"
                                            class="btn btn-sm btn-outline-primary me-1"
                                            title="Sửa">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <form action="{{ route('admin.dich-vu.destroy', $dichVu) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa dịch vụ này?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty

                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="bi bi-inbox fs-2 text-muted mb-3 d-block"></i>
                                        <p class="text-muted mb-0">Chưa có dịch vụ nào.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
@endsection

{{-- DataTables Script --}}
<x-datatable-script tableId="dichvuTable" />
