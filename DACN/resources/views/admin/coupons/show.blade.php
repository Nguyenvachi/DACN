@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Chi tiết mã giảm giá</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Sửa
            </a>
            <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thông tin mã giảm giá</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th width="200">Mã giảm giá:</th>
                                <td><code class="fs-5">{{ $coupon->ma_giam_gia }}</code></td>
                            </tr>
                            <tr>
                                <th>Tên:</th>
                                <td>{{ $coupon->ten }}</td>
                            </tr>
                            <tr>
                                <th>Mô tả:</th>
                                <td>{{ $coupon->mo_ta ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Loại:</th>
                                <td>
                                    @if($coupon->loai === 'phan_tram')
                                        <span class="badge bg-info">% Phần trăm</span>
                                    @else
                                        <span class="badge bg-success">Tiền mặt</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Giá trị:</th>
                                <td class="fs-5 fw-bold text-primary">
                                    @if($coupon->loai === 'phan_tram')
                                        {{ $coupon->gia_tri }}%
                                    @else
                                        {{ number_format($coupon->gia_tri, 0, ',', '.') }}đ
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Giảm tối đa:</th>
                                <td>
                                    @if($coupon->giam_toi_da)
                                        {{ number_format($coupon->giam_toi_da, 0, ',', '.') }}đ
                                    @else
                                        <span class="text-muted">Không giới hạn</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Đơn hàng tối thiểu:</th>
                                <td>
                                    @if($coupon->don_toi_thieu)
                                        {{ number_format($coupon->don_toi_thieu, 0, ',', '.') }}đ
                                    @else
                                        <span class="text-muted">Không giới hạn</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Ngày bắt đầu:</th>
                                <td>{{ optional($coupon->ngay_bat_dau)->format('d/m/Y') ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Ngày kết thúc:</th>
                                <td>{{ optional($coupon->ngay_ket_thuc)->format('d/m/Y') ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Số lần sử dụng:</th>
                                <td>{{ $coupon->so_lan_da_su_dung ?? 0 }} / {{ $coupon->so_lan_su_dung_toi_da ?? 'Không giới hạn' }}</td>
                            </tr>
                            <tr>
                                <th>Trạng thái:</th>
                                <td>
                                    @if($coupon->kich_hoat)
                                        <span class="badge bg-success">Đang hoạt động</span>
                                    @else
                                        <span class="badge bg-secondary">Tắt</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Ngày tạo:</th>
                                <td>{{ optional($coupon->created_at)->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Cập nhật lần cuối:</th>
                                <td>{{ optional($coupon->updated_at)->format('d/m/Y H:i') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thao tác</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Sửa mã giảm giá
                        </a>
                        <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('Xác nhận xóa mã giảm giá này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash me-2"></i>Xóa mã giảm giá
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
