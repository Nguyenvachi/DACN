@extends('layouts.doctor')

@section('title', 'Chi tiết lịch tái khám')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-calendar-check"></i> Chi tiết lịch tái khám #{{ $lichTaiKham->id }}</h2>
        <a href="{{ route('doctor.lich-tai-kham.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <!-- Thông tin bệnh nhân -->
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Thông tin bệnh nhân</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Họ tên:</strong> {{ $lichTaiKham->benhNhan->name }}</p>
                            <p><strong>Ngày sinh:</strong> {{ \Carbon\Carbon::parse($lichTaiKham->benhNhan->ngay_sinh)->format('d/m/Y') }}</p>
                            <p><strong>Số điện thoại:</strong> {{ $lichTaiKham->benhNhan->so_dien_thoai }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Email:</strong> {{ $lichTaiKham->benhNhan->email }}</p>
                            <p><strong>Mã bệnh án:</strong> <a href="{{ route('doctor.benhan.show', $lichTaiKham->benh_an_id) }}">#{{ $lichTaiKham->benh_an_id }}</a></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin lịch hẹn -->
            <div class="card mb-3">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> Thông tin lịch hẹn</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Ngày hẹn:</strong> {{ \Carbon\Carbon::parse($lichTaiKham->ngay_hen)->format('d/m/Y') }}</p>
                            <p><strong>Giờ hẹn:</strong> {{ $lichTaiKham->gio_hen ? \Carbon\Carbon::parse($lichTaiKham->gio_hen)->format('H:i') : 'Chưa xác định' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Trạng thái:</strong> 
                                @php
                                    $badgeClass = match($lichTaiKham->trang_thai) {
                                        'Đã hẹn' => 'bg-info',
                                        'Đã xác nhận' => 'bg-primary',
                                        'Đã khám' => 'bg-success',
                                        'Đã hủy' => 'bg-secondary',
                                        'Quá hạn' => 'bg-danger',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $lichTaiKham->trang_thai }}</span>
                            </p>
                            <p><strong>Ngày tạo:</strong> {{ $lichTaiKham->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="mt-3">
                        <h6>Lý do tái khám:</h6>
                        <p class="bg-light p-3 rounded">{{ $lichTaiKham->ly_do }}</p>
                    </div>

                    @if ($lichTaiKham->luu_y)
                        <div class="mt-3">
                            <h6>Lưu ý cho bệnh nhân:</h6>
                            <p class="bg-light p-3 rounded">{{ $lichTaiKham->luu_y }}</p>
                        </div>
                    @endif

                    @if ($lichTaiKham->ghi_chu)
                        <div class="mt-3">
                            <h6>Ghi chú:</h6>
                            <p class="bg-light p-3 rounded">{{ $lichTaiKham->ghi_chu }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Cập nhật trạng thái -->
            @if (!in_array($lichTaiKham->trang_thai, ['Đã khám', 'Đã hủy']))
                <div class="card mb-3">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fas fa-edit"></i> Cập nhật trạng thái</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('doctor.lich-tai-kham.update-status', $lichTaiKham) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Trạng thái mới</label>
                                <select name="trang_thai" class="form-select" required>
                                    <option value="Đã hẹn" {{ $lichTaiKham->trang_thai == 'Đã hẹn' ? 'selected' : '' }}>Đã hẹn</option>
                                    <option value="Đã xác nhận" {{ $lichTaiKham->trang_thai == 'Đã xác nhận' ? 'selected' : '' }}>Đã xác nhận</option>
                                    <option value="Đã khám">Đã khám</option>
                                    <option value="Đã hủy">Đã hủy</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ghi chú</label>
                                <textarea name="ghi_chu" class="form-control" rows="3">{{ $lichTaiKham->ghi_chu }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-save"></i> Cập nhật
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Timeline -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-history"></i> Lịch sử</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <i class="fas fa-plus-circle text-primary"></i>
                            <div>
                                <strong>Tạo lịch hẹn</strong>
                                <br><small class="text-muted">{{ $lichTaiKham->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>

                        @if ($lichTaiKham->ngay_xac_nhan)
                            <div class="timeline-item">
                                <i class="fas fa-check-circle text-info"></i>
                                <div>
                                    <strong>Đã xác nhận</strong>
                                    <br><small class="text-muted">{{ \Carbon\Carbon::parse($lichTaiKham->ngay_xac_nhan)->format('d/m/Y H:i') }}</small>
                                </div>
                            </div>
                        @endif

                        @if ($lichTaiKham->ngay_kham_thuc_te)
                            <div class="timeline-item">
                                <i class="fas fa-stethoscope text-success"></i>
                                <div>
                                    <strong>Đã khám</strong>
                                    <br><small class="text-muted">{{ \Carbon\Carbon::parse($lichTaiKham->ngay_kham_thuc_te)->format('d/m/Y H:i') }}</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    .timeline-item {
        position: relative;
        padding-bottom: 20px;
        border-left: 2px solid #dee2e6;
        padding-left: 20px;
    }
    .timeline-item:last-child {
        border-left: none;
        padding-bottom: 0;
    }
    .timeline-item i {
        position: absolute;
        left: -9px;
        background: white;
        padding: 2px;
    }
</style>
@endpush
@endsection
