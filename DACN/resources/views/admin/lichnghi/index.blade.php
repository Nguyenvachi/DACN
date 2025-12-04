@extends('layouts.admin')

@section('content')

    <style>
        .card-custom {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
        }

        .form-label {
            font-weight: 600;
        }
    </style>

    <div class="container-fluid py-4">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">
                    <i class="bi bi-calendar-x-fill text-danger me-2"></i>
                    Lịch nghỉ - Bác sĩ: {{ $bacSi->ho_ten }}
                </h2>
                <small class="text-muted">{{ $bacSi->chuyen_khoa }}</small>
            </div>

            <a href="{{ route('admin.bac-si.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>

        {{-- ALERTS --}}
        @if (session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill me-1"></i>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-octagon-fill me-1"></i>
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Lỗi:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORM: THÊM LỊCH NGHỈ --}}
        <div class="card card-custom mb-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">
                    <i class="bi bi-plus-circle text-success me-2"></i>
                    Thêm lịch nghỉ
                </h5>
            </div>

            <div class="card-body">

                <form action="{{ route('admin.lichnghi.store', $bacSi) }}" method="POST">
                    @csrf

                    <div class="row g-3">

                        {{-- Ngày nghỉ --}}
                        <div class="col-md-3">
                            <label class="form-label">Ngày nghỉ <span class="text-danger">*</span></label>
                            <input type="date" name="ngay" class="form-control" required min="{{ date('Y-m-d') }}"
                                value="{{ old('ngay') }}">
                        </div>

                        {{-- Bắt đầu --}}
                        <div class="col-md-2">
                            <label class="form-label">Từ giờ <span class="text-danger">*</span></label>
                            <input type="time" name="bat_dau" class="form-control" required
                                value="{{ old('bat_dau', '08:00') }}">
                        </div>

                        {{-- Kết thúc --}}
                        <div class="col-md-2">
                            <label class="form-label">Đến giờ <span class="text-danger">*</span></label>
                            <input type="time" name="ket_thuc" class="form-control" required
                                value="{{ old('ket_thuc', '17:00') }}">
                        </div>

                        {{-- Lý do --}}
                        <div class="col-md-3">
                            <label class="form-label">Lý do</label>
                            <input type="text" name="ly_do" class="form-control"
                                placeholder="VD: Nghỉ phép, đi công tác..." value="{{ old('ly_do') }}">
                        </div>

                        {{-- Nút submit --}}
                        <div class="col-md-2">
                            <label class="d-block">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-plus-lg"></i>
                                Thêm
                            </button>
                        </div>

                    </div>
                </form>

            </div>
        </div>

        {{-- DANH SÁCH --}}
        <div class="card card-custom">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">
                    <i class="bi bi-list-check me-2 text-primary"></i>
                    Danh sách lịch nghỉ
                </h5>
            </div>

            <div class="card-body p-0">

                <div class="table-responsive">
                    <table class="table table-hover align-middle table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 15%">Ngày nghỉ</th>
                                <th style="width: 15%">Từ giờ</th>
                                <th style="width: 15%">Đến giờ</th>
                                <th>Lý do</th>
                                <th style="width: 120px">Hành động</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($lichNghis as $lich)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($lich->ngay)->format('d/m/Y') }}</td>
                                    <td>{{ $lich->bat_dau }}</td>
                                    <td>{{ $lich->ket_thuc }}</td>
                                    <td>{{ $lich->ly_do ?? 'Không có' }}</td>

                                    <td>
                                        <form action="{{ route('admin.lichnghi.destroy', $lich) }}" method="POST"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa lịch này?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i> Xóa
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty

                                {{-- EMPTY --}}
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-calendar-x fs-1 d-block mb-3"></i>
                                        <p class="mb-0">Chưa có lịch nghỉ nào.</p>
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
