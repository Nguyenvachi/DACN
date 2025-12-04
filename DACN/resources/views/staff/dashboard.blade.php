@extends('layouts.staff')

@section('content')
    <div class="container">
        @if (!$nhanVien)
            <div class="alert alert-warning">
                <h4>⚠️ Chưa có hồ sơ nhân viên</h4>
                <p>Tài khoản của bạn chưa được liên kết với hồ sơ nhân viên. Vui lòng liên hệ quản trị viên.</p>
            </div>
        @else
            <div class="row">
                <div class="col-md-8">
                    <h3>Xin chào, {{ $nhanVien->ho_ten }}! 👋</h3>
                    <p class="text-muted">{{ $nhanVien->chuc_vu ?? 'Nhân viên' }}</p>
                </div>
                <div class="col-md-4 text-end">
                    <small class="text-muted">{{ now()->isoFormat('dddd, D MMMM YYYY') }}</small>
                </div>
            </div>

            <hr>

            <!-- Ca làm việc hôm nay -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">📅 Ca làm việc hôm nay ({{ now()->format('d/m/Y') }})</h5>
                </div>
                <div class="card-body">
                    @if ($caHomNay->isEmpty())
                        <div class="alert alert-info mb-0">
                            🎉 Bạn không có ca làm việc hôm nay!
                        </div>
                    @else
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Giờ bắt đầu</th>
                                    <th>Giờ kết thúc</th>
                                    <th>Ghi chú</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($caHomNay as $ca)
                                    <tr>
                                        <td><strong>{{ \Carbon\Carbon::parse($ca->bat_dau)->format('H:i') }}</strong></td>
                                        <td><strong>{{ \Carbon\Carbon::parse($ca->ket_thuc)->format('H:i') }}</strong></td>
                                        <td>{{ $ca->ghi_chu ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            <!-- Lịch tuần này -->
            <div class="card" id="lich">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">📆 Lịch làm việc tuần này</h5>
                </div>
                <div class="card-body">
                    @if ($caTuanNay->isEmpty())
                        <div class="alert alert-secondary mb-0">
                            Không có ca làm việc nào trong tuần này.
                        </div>
                    @else
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Ngày</th>
                                    <th>Thứ</th>
                                    <th>Giờ</th>
                                    <th>Ghi chú</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($caTuanNay as $ca)
                                    <tr class="{{ \Carbon\Carbon::parse($ca->ngay)->isToday() ? 'table-warning' : '' }}">
                                        <td>{{ \Carbon\Carbon::parse($ca->ngay)->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($ca->ngay)->isoFormat('dddd') }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($ca->bat_dau)->format('H:i') }} -
                                            {{ \Carbon\Carbon::parse($ca->ket_thuc)->format('H:i') }}
                                        </td>
                                        <td>{{ $ca->ghi_chu ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            <!-- Thông tin cá nhân -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">👤 Thông tin cá nhân</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Họ tên:</strong> {{ $nhanVien->ho_ten }}</p>
                            <p><strong>Chức vụ:</strong> {{ $nhanVien->chuc_vu ?? 'Chưa cập nhật' }}</p>
                            <p><strong>Email:</strong> {{ $nhanVien->email_cong_viec }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Số điện thoại:</strong> {{ $nhanVien->so_dien_thoai ?? 'Chưa cập nhật' }}</p>
                            <p><strong>Ngày sinh:</strong>
                                {{ $nhanVien->ngay_sinh ? \Carbon\Carbon::parse($nhanVien->ngay_sinh)->format('d/m/Y') : 'Chưa cập nhật' }}
                            </p>
                            <p><strong>Trạng thái:</strong>
                                <span
                                    class="badge {{ $nhanVien->trang_thai === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $nhanVien->trang_thai === 'active' ? 'Hoạt động' : 'Ngưng hoạt động' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
