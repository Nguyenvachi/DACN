@extends('layouts.admin')

@section('content')

    <style>
        .card-custom {
            border-radius: 12px;
            border: none;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        }

        .form-label {
            font-weight: 600;
        }
    </style>

    <div class="container-fluid py-4">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-file-earmark-spreadsheet me-2 text-success"></i>
                Xuất ca làm việc nhân viên
            </h2>

            <a href="{{ route('admin.nhanvien.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Quay lại danh sách
            </a>
        </div>

        {{-- FORM CARD --}}
        <div class="card card-custom">
            <div class="card-body">

                <form method="GET" action="{{ route('admin.nhanvien.shifts.export') }}" class="mb-3">

                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label">Từ ngày <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" class="form-control" required
                                value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Đến ngày <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" class="form-control" required
                                value="{{ request('end_date', now()->endOfMonth()->format('Y-m-d')) }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Nhân viên (tùy chọn)</label>
                            <select name="nhan_vien_id" class="form-select">
                                <option value="">-- Tất cả nhân viên --</option>

                                @foreach ($nhanViens as $nv)
                                    <option value="{{ $nv->id }}"
                                        {{ request('nhan_vien_id') == $nv->id ? 'selected' : '' }}>
                                        {{ $nv->ho_ten }} ({{ $nv->chuc_vu ?? 'Không rõ' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    {{-- BUTTONS --}}
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-download me-1"></i> Tải xuống CSV
                        </button>

                        <a href="{{ route('admin.nhanvien.index') }}" class="btn btn-outline-secondary">
                            Quay lại
                        </a>
                    </div>

                    {{-- ERRORS --}}
                    @if ($errors->any())
                        <div class="alert alert-danger mt-3">
                            <strong>Lỗi:</strong>
                            <ul class="mb-0 mt-1">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                </form>

                {{-- HELP BOX --}}
                <div class="alert alert-info">
                    <strong>📝 Hướng dẫn:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Chọn khoảng thời gian cần xuất báo cáo.</li>
                        <li>Có thể chọn riêng một nhân viên hoặc để trống để xuất tất cả.</li>
                        <li>File CSV được export ở định dạng UTF-8, mở được bằng Excel và Google Sheets.</li>
                        <li>Dữ liệu bao gồm: Ngày – Giờ bắt đầu – Giờ kết thúc – Ghi chú.</li>
                    </ul>
                </div>

            </div>
        </div>

    </div>

@endsection
