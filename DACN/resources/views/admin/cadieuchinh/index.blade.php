@extends('layouts.admin')

@section('content')

{{-- =============================
     🔥 Header trang + tên bác sĩ
============================= --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="fas fa-user-clock me-2"></i>Ca điều chỉnh</h2>
        <h5 class="text-primary mb-0">{{ $bacSi->ho_ten }}</h5>
        <small class="text-muted">{{ $bacSi->chuyen_khoa }}</small>
    </div>

    <a href="{{ route('admin.bac-si.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>


<div class="container-fluid px-0">

    {{-- =============================
         🔥 Alert message
    ============================== --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- =============================
         🔥 Hướng dẫn sử dụng
    ============================== --}}
    <div class="alert alert-info shadow-sm mb-4">
        <h6 class="alert-heading fw-bold"><i class="fas fa-info-circle me-2"></i>Hướng dẫn</h6>
        <ul class="mb-0 small">
            <li><strong>add:</strong> thêm ca đột xuất (ngoài lịch chuẩn)</li>
            <li><strong>modify:</strong> chỉnh giờ ca cố định trong ngày cụ thể</li>
            <li><strong>cancel:</strong> hủy ca làm việc ngày cụ thể</li>
        </ul>
    </div>


    {{-- =============================
         🔥 Form tạo ca điều chỉnh
    ============================== --}}
    <div class="card shadow-lg border-0 mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Thêm ca điều chỉnh</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.cadieuchinh.store', $bacSi) }}" method="POST">
                @csrf

                <div class="row g-4">

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Ngày <span class="text-danger">*</span></label>
                        <input type="date" name="ngay"
                            class="form-control form-control-lg"
                            min="{{ date('Y-m-d') }}"
                            value="{{ old('ngay') }}"
                            required>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-bold">Giờ bắt đầu</label>
                        <input type="time" name="gio_bat_dau"
                            class="form-control form-control-lg"
                            value="{{ old('gio_bat_dau', '08:00') }}" required>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-bold">Giờ kết thúc</label>
                        <input type="time" name="gio_ket_thuc"
                            class="form-control form-control-lg"
                            value="{{ old('gio_ket_thuc', '17:00') }}" required>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-bold">Hành động <span class="text-danger">*</span></label>
                        <select name="hanh_dong" class="form-select form-select-lg" required>
                            <option value="add" {{ old('hanh_dong')=='add' ? 'selected':'' }}>Thêm ca</option>
                            <option value="modify" {{ old('hanh_dong')=='modify' ? 'selected':'' }}>Điều chỉnh</option>
                            <option value="cancel" {{ old('hanh_dong')=='cancel' ? 'selected':'' }}>Hủy ca</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Lý do</label>
                        <input type="text" name="ly_do"
                               class="form-control form-control-lg"
                               placeholder="VD: Tăng ca, công tác..."
                               value="{{ old('ly_do') }}">
                    </div>

                </div>

                <button type="submit" class="btn btn-primary mt-4 px-4">
                    <i class="fas fa-save me-2"></i>Thêm Ca
                </button>
            </form>
        </div>
    </div>


    {{-- =============================
         🔥 Bảng danh sách ca điều chỉnh
    ============================== --}}
    <div class="card shadow-sm border-0">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Danh sách ca điều chỉnh</h5>
        </div>

        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="15%">Ngày</th>
                        <th width="12%">Bắt đầu</th>
                        <th width="12%">Kết thúc</th>
                        <th width="15%">Hành động</th>
                        <th width="30%">Lý do</th>
                        <th width="16%" class="text-center">Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($caDieuChinhs as $ca)
                    <tr>
                        <td>
                            <strong>{{ \Carbon\Carbon::parse($ca->ngay)->format('d/m/Y') }}</strong>
                            <br>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($ca->ngay)->locale('vi')->isoFormat('dddd') }}
                            </small>
                        </td>

                        <td>{{ \Carbon\Carbon::parse($ca->gio_bat_dau)->format('H:i') }}</td>
                        <td>{{ \Carbon\Carbon::parse($ca->gio_ket_thuc)->format('H:i') }}</td>

                        <td>
                            @if($ca->hanh_dong === 'add')
                                <span class="badge bg-success px-3 py-2">
                                    <i class="fas fa-plus"></i> Thêm
                                </span>
                            @elseif($ca->hanh_dong === 'modify')
                                <span class="badge bg-warning px-3 py-2">
                                    <i class="fas fa-edit"></i> Điều chỉnh
                                </span>
                            @else
                                <span class="badge bg-danger px-3 py-2">
                                    <i class="fas fa-times"></i> Hủy ca
                                </span>
                            @endif
                        </td>

                        <td>{{ $ca->ly_do ?? '-' }}</td>

                        <td class="text-center">
                            <form action="{{ route('admin.cadieuchinh.destroy', $ca) }}" method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Xóa ca điều chỉnh này?')">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-calendar-times fa-3x mb-3"></i>
                            <p>Chưa có ca điều chỉnh nào.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


{{-- =============================
     🔥 CSS bổ sung cho trang này
============================= --}}
<style>
    .form-label {
        font-weight: 600;
    }

    table tbody tr:hover {
        background: #f8fafc;
    }
</style>

@endsection
