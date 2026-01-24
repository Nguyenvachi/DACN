@extends('layouts.admin')

@section('title', 'Loại Nội soi')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">🩺 Loại Nội soi</h4>
        <a href="{{ route('admin.loai-noi-soi.create') }}" class="btn btn-primary">+ Thêm loại</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="table-responsive">
            <table id="loaiNoiSoiTable" class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Tên</th>
                        <th>Mã</th>
                        <th>Giá</th>
                        <th>Thời gian</th>
                        <th>Phòng</th>
                        <th>Chuyên khoa</th>
                        <th>Trạng thái</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $it)
                        <tr>
                            <td>{{ $it->id }}</td>
                            <td class="fw-semibold">{{ $it->ten }}</td>
                            <td>{{ $it->ma ?? '-' }}</td>
                            <td>{{ number_format((float) ($it->gia ?? 0), 0, ',', '.') }} đ</td>
                            <td>{{ (int) ($it->thoi_gian_uoc_tinh ?? 0) }} phút</td>
                            <td>{{ $it->phong?->ten ?? '-' }}</td>
                            <td>
                                @if($it->chuyenKhoas && $it->chuyenKhoas->count() > 0)
                                    <small class="text-muted">
                                        {{ $it->chuyenKhoas->pluck('ten')->join(', ') }}
                                    </small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($it->active)
                                    <span class="badge bg-success">Hoạt động</span>
                                @else
                                    <span class="badge bg-secondary">Tạm ngưng</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.loai-noi-soi.edit', $it->id) }}" class="btn btn-sm btn-outline-primary">Sửa</a>
                                <form method="POST" action="{{ route('admin.loai-noi-soi.destroy', $it->id) }}" class="d-inline" onsubmit="return confirm('Xóa loại Nội soi này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">Chưa có dữ liệu</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

<x-datatable-script tableId="loaiNoiSoiTable" />
