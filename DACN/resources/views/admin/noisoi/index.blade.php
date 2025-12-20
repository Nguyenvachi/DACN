@extends('layouts.admin')

@section('title', 'Nội soi')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 fw-bold text-dark">Nội soi</h4>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table id="noiSoiTable" class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Bệnh nhân</th>
                            <th>Bệnh án</th>
                            <th>Loại</th>
                            <th>Trạng thái</th>
                            <th>Ngày</th>
                            <th class="text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($noiSois as $item)
                            <tr>
                                <td>#{{ $item->id }}</td>
                                <td>{{ $item->benhAn->user->name ?? 'N/A' }}</td>
                                <td>#BA{{ $item->benh_an_id }}</td>
                                    <td><span class="badge bg-light text-dark">{{ $item->loaiNoiSoi?->ten ?? $item->loai }}</span></td>
                                <td><span class="badge bg-secondary">{{ $item->trang_thai_text }}</span></td>
                                <td>{{ $item->created_at?->format('d/m/Y H:i') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.noisoi.show', $item) }}" class="btn btn-sm btn-outline-primary">Chi tiết</a>
                                    @if($item->hasResult())
                                        <a href="{{ $item->getDownloadUrl() }}" class="btn btn-sm btn-success" target="_blank">Tải file</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Chưa có dữ liệu.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

<x-datatable-script tableId="noiSoiTable" />
