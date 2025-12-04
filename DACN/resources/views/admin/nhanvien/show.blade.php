@extends('layouts.admin')

@section('content')
    <style>
        .card-custom {
            border-radius: 12px;
            border: none;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        }

        .info-label {
            font-weight: 600;
        }
    </style>

    <div class="container-fluid py-4">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-person-vcard me-2 text-primary"></i>
                Nhân viên #{{ $nhanvien->id }}
            </h2>

            <div>
                <a href="{{ route('admin.nhanvien.history', $nhanvien) }}" class="btn btn-info btn-sm me-2">
                    <i class="bi bi-clock-history me-1"></i> Lịch sử thay đổi
                </a>

                <a href="{{ route('admin.nhanvien.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>

        <div class="row">

            {{-- THÔNG TIN NHÂN VIÊN --}}
            <div class="col-md-6">
                <div class="card card-custom mb-4">
                    <div class="card-body">

                        <h5 class="fw-bold mb-3">Thông tin nhân viên</h5>

                        @php
                            $statusLabels = [
                                'active' => 'Đang làm',
                                'inactive' => 'Ngừng hoạt động',
                            ];

                            $statusColors = [
                                'active' => 'success',
                                'inactive' => 'secondary',
                            ];
                        @endphp

                        <ul class="list-group">
                            <li class="list-group-item">
                                <span class="info-label">Họ tên:</span> {{ $nhanvien->ho_ten }}
                            </li>
                            <li class="list-group-item">
                                <span class="info-label">Chức vụ:</span> {{ $nhanvien->chuc_vu ?? '—' }}
                            </li>
                            <li class="list-group-item">
                                <span class="info-label">Email:</span> {{ $nhanvien->email_cong_viec }}
                            </li>
                            <li class="list-group-item">
                                <span class="info-label">Điện thoại:</span> {{ $nhanvien->so_dien_thoai ?? '—' }}
                            </li>
                            <li class="list-group-item">
                                <span class="info-label">Trạng thái:</span>
                                <span class="badge bg-{{ $statusColors[$nhanvien->trang_thai] ?? 'secondary' }}">
                                    {{ $statusLabels[$nhanvien->trang_thai] ?? $nhanvien->trang_thai }}
                                </span>
                            </li>
                            <li class="list-group-item">
                                <span class="info-label">Ngày sinh:</span>
                                {{ $nhanvien->ngay_sinh ?: '—' }}
                            </li>
                            <li class="list-group-item">
                                <span class="info-label">Giới tính:</span>
                                {{ $nhanvien->gioi_tinh ?: '—' }}
                            </li>
                        </ul>

                    </div>
                </div>
            </div>

            {{-- FORM THÊM CA LÀM VIỆC --}}
            <div class="col-md-6">
                <div class="card card-custom">
                    <div class="card-body">

                        <h5 class="fw-bold mb-3">Thêm ca làm việc</h5>

                        {{-- CẢNH BÁO XUNG ĐỘT --}}
                        @if ($errors->has('shift_conflict'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                <strong>⚠️ Xung đột ca làm việc!</strong><br>
                                {{ $errors->first('shift_conflict') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        {{-- FORM THÊM CA --}}
                        <form method="POST" action="{{ route('admin.nhanvien.shift.add', $nhanvien) }}" class="row g-3">
                            @csrf

                            <div class="col-md-4">
                                <label class="form-label">Ngày</label>
                                <input type="date" name="ngay" class="form-control" required
                                    value="{{ old('ngay') }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Bắt đầu</label>
                                <input type="time" name="bat_dau" class="form-control" required
                                    value="{{ old('bat_dau') }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Kết thúc</label>
                                <input type="time" name="ket_thuc" class="form-control" required
                                    value="{{ old('ket_thuc') }}">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Ghi chú</label>
                                <input name="ghi_chu" class="form-control" placeholder="Ghi chú (không bắt buộc)"
                                    value="{{ old('ghi_chu') }}">
                            </div>

                            <div class="col-12">
                                <button class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-circle me-1"></i> Thêm
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>

        </div>

        {{-- LỊCH LÀM VIỆC --}}
        <div class="card card-custom mt-4">
            <div class="card-body">

                <h5 class="fw-bold mb-3">Lịch làm việc</h5>

                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Ngày</th>
                                <th>Bắt đầu</th>
                                <th>Kết thúc</th>
                                <th>Ghi chú</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($nhanvien->caLamViecs as $ca)
                                <tr>
                                    <td>{{ $ca->ngay }}</td>
                                    <td>{{ $ca->bat_dau }}</td>
                                    <td>{{ $ca->ket_thuc }}</td>
                                    <td>{{ $ca->ghi_chu ?: '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">
                                        <i class="bi bi-calendar-x fs-4 d-block mb-2"></i>
                                        Chưa có ca làm việc.
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
