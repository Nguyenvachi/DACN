@extends('layouts.doctor')

@section('title', 'Cập nhật hồ sơ theo dõi thai kỳ #' . $theoDoiThaiKy->id)

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: #1f2937;">
                <i class="fas fa-heart me-2" style="color: #ec4899;"></i>
                Cập nhật hồ sơ theo dõi thai kỳ
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('doctor.theo-doi-thai-ky.index') }}">Theo dõi thai kỳ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('doctor.theo-doi-thai-ky.show', $theoDoiThaiKy->id) }}">Chi tiết #{{ $theoDoiThaiKy->id }}</a></li>
                    <li class="breadcrumb-item active">Cập nhật</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('doctor.theo-doi-thai-ky.show', $theoDoiThaiKy->id) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <form action="{{ route('doctor.theo-doi-thai-ky.update', $theoDoiThaiKy->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-lg-8">
                {{-- Thông tin bệnh nhân --}}
                <div class="card vc-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user-injured me-2" style="color: #3b82f6;"></i>
                            Thông tin bệnh nhân
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <strong>Bệnh nhân:</strong> {{ $theoDoiThaiKy->user->name }}<br>
                            <strong>Email:</strong> {{ $theoDoiThaiKy->user->email }}<br>
                            @if($theoDoiThaiKy->user->so_dien_thoai)
                            <strong>SĐT:</strong> {{ $theoDoiThaiKy->user->so_dien_thoai }}<br>
                            @endif
                            @if($theoDoiThaiKy->benh_an_id)
                            <strong>Bệnh án:</strong> #{{ str_pad($theoDoiThaiKy->benh_an_id, 4, '0', STR_PAD_LEFT) }}<br>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Thông tin thai kỳ --}}
                <div class="card vc-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-baby me-2" style="color: #ec4899;"></i>
                            Thông tin thai kỳ
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ngày kinh cuối (LMP) <span class="text-danger">*</span></label>
                                <input type="date" name="ngay_kinh_cuoi" class="form-control" 
                                       value="{{ old('ngay_kinh_cuoi', $theoDoiThaiKy->ngay_kinh_cuoi->format('Y-m-d')) }}" required>
                                <small class="text-muted">Last Menstrual Period - ngày đầu kỳ kinh cuối</small>
                                @error('ngay_kinh_cuoi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Loại thai <span class="text-danger">*</span></label>
                                <select name="loai_thai" class="form-select" required>
                                    <option value="Đơn thai" {{ old('loai_thai', $theoDoiThaiKy->loai_thai) === 'Đơn thai' ? 'selected' : '' }}>Đơn thai</option>
                                    <option value="Song thai" {{ old('loai_thai', $theoDoiThaiKy->loai_thai) === 'Song thai' ? 'selected' : '' }}>Song thai</option>
                                    <option value="Đa thai" {{ old('loai_thai', $theoDoiThaiKy->loai_thai) === 'Đa thai' ? 'selected' : '' }}>Đa thai</option>
                                </select>
                                @error('loai_thai')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Nhóm máu</label>
                                <select name="nhom_mau" class="form-select">
                                    <option value="">-- Chọn --</option>
                                    <option value="A" {{ old('nhom_mau', $theoDoiThaiKy->nhom_mau) === 'A' ? 'selected' : '' }}>A</option>
                                    <option value="B" {{ old('nhom_mau', $theoDoiThaiKy->nhom_mau) === 'B' ? 'selected' : '' }}>B</option>
                                    <option value="AB" {{ old('nhom_mau', $theoDoiThaiKy->nhom_mau) === 'AB' ? 'selected' : '' }}>AB</option>
                                    <option value="O" {{ old('nhom_mau', $theoDoiThaiKy->nhom_mau) === 'O' ? 'selected' : '' }}>O</option>
                                </select>
                                @error('nhom_mau')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">RH</label>
                                <select name="rh" class="form-select">
                                    <option value="">-- Chọn --</option>
                                    <option value="+" {{ old('rh', $theoDoiThaiKy->rh) === '+' ? 'selected' : '' }}>Dương (+)</option>
                                    <option value="-" {{ old('rh', $theoDoiThaiKy->rh) === '-' ? 'selected' : '' }}>Âm (-)</option>
                                </select>
                                @error('rh')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Cân nặng trước mang thai (kg)</label>
                                <input type="number" step="0.1" name="can_nang_truoc_mang_thai" class="form-control"
                                       value="{{ old('can_nang_truoc_mang_thai', $theoDoiThaiKy->can_nang_truoc_mang_thai) }}"
                                       placeholder="VD: 55">
                                @error('can_nang_truoc_mang_thai')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Chiều cao (cm)</label>
                                <input type="number" step="0.1" name="chieu_cao" class="form-control"
                                       value="{{ old('chieu_cao', $theoDoiThaiKy->chieu_cao) }}"
                                       placeholder="VD: 160">
                                @error('chieu_cao')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">BMI trước mang thai</label>
                                <input type="text" class="form-control" 
                                       value="{{ $theoDoiThaiKy->bmi_truoc_mang_thai ? number_format($theoDoiThaiKy->bmi_truoc_mang_thai, 2) : '-' }}" 
                                       readonly disabled>
                                <small class="text-muted">Sẽ được tự động tính từ cân nặng và chiều cao</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tiền sử --}}
                <div class="card vc-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-history me-2" style="color: #f59e0b;"></i>
                            Tiền sử bệnh
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Tiền sử sản khoa</label>
                            <textarea name="tien_su_san_khoa" class="form-control" rows="3"
                                      placeholder="VD: Sẩy thai 1 lần (năm 2020), sinh mổ 1 lần (2018)...">{{ old('tien_su_san_khoa', $theoDoiThaiKy->tien_su_san_khoa) }}</textarea>
                            <small class="text-muted">Lịch sử mang thai, sinh nở, sẩy thai, nạo thai...</small>
                            @error('tien_su_san_khoa')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tiền sử bệnh lý</label>
                            <textarea name="tien_su_benh_ly" class="form-control" rows="3"
                                      placeholder="VD: Đái tháo đường, cao huyết áp, bệnh tim mạch...">{{ old('tien_su_benh_ly', $theoDoiThaiKy->tien_su_benh_ly) }}</textarea>
                            <small class="text-muted">Các bệnh mãn tính, bệnh nền</small>
                            @error('tien_su_benh_ly')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tiền sử dị ứng</label>
                            <textarea name="di_ung" class="form-control" rows="2"
                                      placeholder="VD: Dị ứng penicillin, hải sản...">{{ old('di_ung', $theoDoiThaiKy->di_ung) }}</textarea>
                            @error('di_ung')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="ghi_chu" class="form-control" rows="2"
                                      placeholder="Ghi chú thêm...">{{ old('ghi_chu', $theoDoiThaiKy->ghi_chu) }}</textarea>
                            @error('ghi_chu')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Trạng thái --}}
                <div class="card vc-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2" style="color: #6366f1;"></i>
                            Trạng thái
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Trạng thái theo dõi</label>
                                <select name="trang_thai" class="form-select">
                                    <option value="Đang theo dõi" {{ old('trang_thai', $theoDoiThaiKy->trang_thai) === 'Đang theo dõi' ? 'selected' : '' }}>Đang theo dõi</option>
                                    <option value="Đã hoàn thành" {{ old('trang_thai', $theoDoiThaiKy->trang_thai) === 'Đã hoàn thành' ? 'selected' : '' }}>Đã hoàn thành</option>
                                    <option value="Tạm dừng" {{ old('trang_thai', $theoDoiThaiKy->trang_thai) === 'Tạm dừng' ? 'selected' : '' }}>Tạm dừng</option>
                                </select>
                                @error('trang_thai')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kết quả thai kỳ</label>
                                <input type="text" name="ket_qua_thai_ky" class="form-control" 
                                       value="{{ old('ket_qua_thai_ky', $theoDoiThaiKy->ket_qua_thai_ky) }}"
                                       placeholder="VD: Sinh thường, sinh mổ, sẩy thai...">
                                @error('ket_qua_thai_ky')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mb-4">
                    <a href="{{ route('doctor.theo-doi-thai-ky.show', $theoDoiThaiKy->id) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Hủy
                    </a>
                    <button type="submit" class="btn vc-btn-primary">
                        <i class="fas fa-save me-2"></i>Cập nhật hồ sơ
                    </button>
                </div>
            </div>

            <div class="col-lg-4">
                {{-- Thông tin gói dịch vụ --}}
                <div class="card vc-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-box me-2" style="color: #f59e0b;"></i>
                            Thông tin gói dịch vụ
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-0">
                            <p class="mb-2"><strong>Gói hiện tại:</strong> {{ $theoDoiThaiKy->goi_dich_vu ?? 'Gói cơ bản' }}</p>
                            <p class="mb-2"><strong>Giá:</strong> <span class="text-danger">{{ number_format($theoDoiThaiKy->gia_tien, 0, ',', '.') }} VNĐ</span></p>
                            <p class="mb-0"><strong>Trạng thái:</strong> 
                                @if($theoDoiThaiKy->trang_thai_thanh_toan === 'Đã thanh toán')
                                <span class="badge bg-success">Đã thanh toán</span>
                                @else
                                <span class="badge bg-warning">Chưa thanh toán</span>
                                @endif
                            </p>
                            <hr>
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Thông tin gói dịch vụ không thể chỉnh sửa. Liên hệ nhân viên để thay đổi.
                            </small>
                        </div>
                    </div>
                </div>

                {{-- Thông tin thống kê --}}
                <div class="card vc-card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-line me-2" style="color: #10b981;"></i>
                            Thống kê
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-2"><strong>Số lần khám:</strong> {{ $theoDoiThaiKy->lichKhamThai->count() ?? 0 }} lần</p>
                        <p class="mb-2"><strong>Ngày bắt đầu:</strong> {{ \Carbon\Carbon::parse($theoDoiThaiKy->ngay_bat_dau)->format('d/m/Y') }}</p>
                        @if($theoDoiThaiKy->ngay_ket_thuc)
                        <p class="mb-2"><strong>Ngày kết thúc:</strong> {{ \Carbon\Carbon::parse($theoDoiThaiKy->ngay_ket_thuc)->format('d/m/Y') }}</p>
                        @endif
                        <hr>
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            Cập nhật lần cuối: {{ $theoDoiThaiKy->updated_at->diffForHumans() }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
