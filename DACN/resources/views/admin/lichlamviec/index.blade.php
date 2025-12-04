@extends('layouts.admin')

@section('content')

    <style>
        .card-custom {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
        }

        .section-title {
            font-weight: 700;
            font-size: 22px;
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
                    <i class="bi bi-calendar-week text-primary me-2"></i>
                    Lịch làm việc - Bác sĩ: {{ $bacSi->ho_ten }}
                </h2>
                <small class="text-muted">{{ $bacSi->chuyen_khoa }}</small>
            </div>

            <div>
                <div class="btn-group me-2" role="group">
                    <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-download"></i> Xuất báo cáo
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('admin.lichlamviec.export', ['bacSi' => $bacSi->id, 'format' => 'pdf']) }}" target="_blank">
                            <i class="bi bi-file-pdf"></i> Xuất PDF
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.lichlamviec.export', ['bacSi' => $bacSi->id, 'format' => 'csv']) }}">
                            <i class="bi bi-file-earmark-spreadsheet"></i> Xuất CSV
                        </a></li>
                    </ul>
                </div>
                <a href="{{ route('admin.bac-si.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>

        {{-- ALERT --}}
        @if (session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill me-1"></i>
                {{ session('success') }}
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

        {{-- MAIN CARD --}}
        <div class="card card-custom mb-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0"><i class="bi bi-plus-circle text-success me-2"></i>Thêm lịch làm việc</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.lichlamviec.store', $bacSi) }}" method="POST">
                    @csrf

                    <div class="row g-3">

                        {{-- Ngày trong tuần --}}
                        <div class="col-md-4">
                            <label class="form-label">Ngày trong tuần <span class="text-danger">*</span></label>
                            <select name="ngay_trong_tuan" class="form-select" required>
                                <option value="">-- Chọn --</option>
                                <option value="0">Chủ nhật</option>
                                <option value="1">Thứ hai</option>
                                <option value="2">Thứ ba</option>
                                <option value="3">Thứ tư</option>
                                <option value="4">Thứ năm</option>
                                <option value="5">Thứ sáu</option>
                                <option value="6">Thứ bảy</option>
                            </select>
                        </div>

                        {{-- Bắt đầu --}}
                        <div class="col-md-3">
                            <label class="form-label">Giờ bắt đầu <span class="text-danger">*</span></label>
                            <input type="time" name="thoi_gian_bat_dau" class="form-control" required value="08:00">
                        </div>

                        {{-- Kết thúc --}}
                        <div class="col-md-3">
                            <label class="form-label">Giờ kết thúc <span class="text-danger">*</span></label>
                            <input type="time" name="thoi_gian_ket_thuc" class="form-control" required value="17:00">
                        </div>

                        {{-- Phòng --}}
                        <div class="col-md-3">
                            <label class="form-label">Phòng</label>
                            <select name="phong_id" class="form-select">
                                <option value="">-- Không chỉ định --</option>
                                @foreach ($phongs ?? [] as $p)
                                    <option value="{{ $p->id }}">{{ $p->ten }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Nút --}}
                        <div class="col-md-2">
                            <label class="d-block">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-plus-lg"></i> Thêm
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="card card-custom">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0"><i class="bi bi-list-check text-primary me-2"></i>Danh sách lịch làm việc</h5>
            </div>

            <div class="card-body p-0">

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Ngày</th>
                                <th>Giờ bắt đầu</th>
                                <th>Giờ kết thúc</th>
                                <th>Phòng</th>
                                <th style="width:120px">Hành động</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $daysMap = [
                                    0 => 'Chủ nhật',
                                    1 => 'Thứ hai',
                                    2 => 'Thứ ba',
                                    3 => 'Thứ tư',
                                    4 => 'Thứ năm',
                                    5 => 'Thứ sáu',
                                    6 => 'Thứ bảy',
                                ];
                            @endphp

                            @forelse($bacSi->lichLamViecs()->with('phong')->orderBy('ngay_trong_tuan')->get() as $lich)
                                <tr>
                                    <td class="fw-semibold">
                                        <i class="bi bi-calendar-event me-1"></i>
                                        {{ $daysMap[$lich->ngay_trong_tuan] }}
                                    </td>

                                    <td>{{ $lich->thoi_gian_bat_dau }}</td>
                                    <td>{{ $lich->thoi_gian_ket_thuc }}</td>
                                    <td>{{ $lich->phong->ten ?? '-' }}</td>

                                    <td>
                                        <form action="{{ route('admin.lichlamviec.destroy', [$bacSi, $lich]) }}"
                                            method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa lịch này?')">
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
                                        <p class="mb-0">Chưa có lịch làm việc nào.</p>
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
