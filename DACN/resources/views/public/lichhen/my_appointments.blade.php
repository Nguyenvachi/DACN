@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Lịch hẹn của tôi</h2>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($danhSachLichHen->count() > 0)
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Ngày hẹn</th>
                    <th>Giờ hẹn</th>
                    <th>Bác sĩ</th>
                    <th>Chuyên khoa</th>
                    <th>Dịch vụ</th>
                    <th>Trạng thái</th>
                    <th>Thanh toán</th>
                    <th>Ghi chú</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($danhSachLichHen as $lichHen)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($lichHen->ngay_hen)->format('d/m/Y') }}</td>
                    <td>{{ $lichHen->thoi_gian_hen }}</td>
                    <td>{{ $lichHen->bacSi->ho_ten ?? 'N/A' }}</td>
                    <td>{{ $lichHen->bacSi->chuyen_khoa ?? 'N/A' }}</td>
                    <td>{{ $lichHen->dichVu->ten_dich_vu ?? 'N/A' }}</td>
                    <td>
                        @switch($lichHen->trang_thai)
                        @case('Chờ xác nhận')
                        <span class="badge bg-warning">Chờ xác nhận</span>
                        @break
                        @case('Đã xác nhận')
                        <span class="badge bg-success">Đã xác nhận</span>
                        @break
                        @case('Đã hủy')
                        <span class="badge bg-danger">Đã hủy</span>
                        @break
                        @case('Hoàn thành')
                        <span class="badge bg-info">Hoàn thành</span>
                        @break
                        @default
                        <span class="badge bg-secondary">{{ $lichHen->trang_thai }}</span>
                        @endswitch
                    </td>
                    <td>
                        @if($lichHen->is_paid)
                        <span class="badge bg-success">Đã thanh toán</span>
                        @else
                        <span class="badge bg-warning">Chưa thanh toán</span>
                        @endif
                    </td>
                    <td>{{ $lichHen->ghi_chu ?? '-' }}</td>
                    <td>
                        @if(in_array($lichHen->trang_thai, ['Chờ xác nhận', 'Đã xác nhận']))
                        <button type="button" class="btn btn-sm btn-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#editModal{{ $lichHen->id }}">
                            Sửa
                        </button>
                        <form action="{{ route('lichhen.cancel', $lichHen->id) }}"
                            method="POST"
                            style="display:inline;"
                            onsubmit="return confirm('Bạn có chắc muốn hủy lịch hẹn này?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-danger">Hủy</button>
                        </form>
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </td>
                </tr>

                <!-- Modal Sửa -->
                <div class="modal fade" id="editModal{{ $lichHen->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('lichhen.update', $lichHen->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Chỉnh sửa lịch hẹn</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Ngày hẹn</label>
                                        <input type="date" class="form-control" name="ngay_hen"
                                            value="{{ $lichHen->ngay_hen }}"
                                            min="{{ date('Y-m-d') }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Giờ hẹn</label>
                                        <input type="time" class="form-control" name="thoi_gian_hen"
                                            value="{{ $lichHen->thoi_gian_hen }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Ghi chú</label>
                                        <textarea class="form-control" name="ghi_chu" rows="3">{{ $lichHen->ghi_chu }}</textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="alert alert-info">
        Bạn chưa có lịch hẹn nào.
        <a href="{{ route('public.bacsi.index') }}" class="alert-link">Chọn bác sĩ để đặt lịch</a>
    </div>
    @endif
</div>
@endsection