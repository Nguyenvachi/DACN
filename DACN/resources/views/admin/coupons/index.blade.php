@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Danh sách mã giảm giá</h1>
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tạo mã mới
        </a>
    </div>

    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            @if($coupons->count() > 0)
                <div class="table-responsive">
                    <table id="couponsTable" class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Mã</th>
                                <th>Tên</th>
                                <th>Loại</th>
                                <th>Giá trị</th>
                                <th>Trạng thái</th>
                                <th class="text-end">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($coupons as $coupon)
                                <tr>
                                    <td><code>{{ $coupon->ma_giam_gia }}</code></td>
                                    <td>{{ $coupon->ten }}</td>
                                    <td>
                                        @if($coupon->loai === 'phan_tram')
                                            <span class="badge bg-info">% Phần trăm</span>
                                        @else
                                            <span class="badge bg-success">Tiền mặt</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($coupon->loai === 'phan_tram')
                                            {{ $coupon->gia_tri }}%
                                        @else
                                            {{ number_format($coupon->gia_tri, 0, ',', '.') }}đ
                                        @endif
                                    </td>
                                    <td>
                                        @if($coupon->kich_hoat)
                                            <span class="badge bg-success">Hoạt động</span>
                                        @else
                                            <span class="badge bg-secondary">Tắt</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.coupons.show', $coupon) }}" class="btn btn-sm btn-outline-secondary me-1" title="Xem">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-sm btn-outline-primary me-1" title="Sửa">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="d-inline" onsubmit="return confirm('Xác nhận xóa mã giảm giá này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center text-muted py-5">
                    <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                    <p>Chưa có mã giảm giá</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#couponsTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/vi.json'
        },
        order: [[0, 'desc']],
        pageLength: 15,
        responsive: true,
        columnDefs: [
            { orderable: false, targets: -1 }
        ]
    });
});
</script>
@endpush

@endsection
