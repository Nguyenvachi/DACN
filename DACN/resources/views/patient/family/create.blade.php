@extends('layouts.patient-modern')

@section('title', 'Thành viên gia đình (đã gỡ)')
@section('page-title', 'Thành viên gia đình (đã gỡ)')
@section('page-subtitle', 'Module Thành viên gia đình đã được gỡ khỏi ứng dụng')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="alert alert-warning">Module "Thành viên gia đình" đã bị gỡ. Không thể thêm thành viên.</div>
                    @csrf

                    <div class="row g-3">
                        <div class="col-12">
                            <h6 class="mb-3"><i class="fas fa-user-circle me-2"></i>Thông tin cơ bản</h6>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" name="ho_ten" class="form-control @error('ho_ten') is-invalid @enderror" value="{{ old('ho_ten') }}" required>
                            @error('ho_ten')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Quan hệ <span class="text-danger">*</span></label>
                            <select name="quan_he" class="form-select @error('quan_he') is-invalid @enderror" required>
                                <option value="">-- Chọn quan hệ --</option>
                                <option value="cha" {{ old('quan_he') === 'cha' ? 'selected' : '' }}>Bố</option>
                                <option value="me" {{ old('quan_he') === 'me' ? 'selected' : '' }}>Mẹ</option>
                                <option value="vo" {{ old('quan_he') === 'vo' ? 'selected' : '' }}>Vợ</option>
                                <option value="chong" {{ old('quan_he') === 'chong' ? 'selected' : '' }}>Chồng</option>
                                <option value="con" {{ old('quan_he') === 'con' ? 'selected' : '' }}>Con</option>
                                <option value="anh" {{ old('quan_he') === 'anh' ? 'selected' : '' }}>Anh</option>
                                <option value="chi" {{ old('quan_he') === 'chi' ? 'selected' : '' }}>Chị</option>
                                <option value="em" {{ old('quan_he') === 'em' ? 'selected' : '' }}>Em</option>
                                <option value="ong" {{ old('quan_he') === 'ong' ? 'selected' : '' }}>Ông</option>
                                <option value="ba" {{ old('quan_he') === 'ba' ? 'selected' : '' }}>Bà</option>
                                <option value="khac" {{ old('quan_he') === 'khac' ? 'selected' : '' }}>Khác</option>
                            </select>
                            @error('quan_he')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Ngày sinh <span class="text-danger">*</span></label>
                            <input type="date" name="ngay_sinh" class="form-control @error('ngay_sinh') is-invalid @enderror" value="{{ old('ngay_sinh') }}" required>
                            @error('ngay_sinh')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Giới tính <span class="text-danger">*</span></label>
                            <select name="gioi_tinh" class="form-select @error('gioi_tinh') is-invalid @enderror" required>
                                <option value="">-- Chọn giới tính --</option>
                                <option value="nam" {{ old('gioi_tinh') === 'nam' ? 'selected' : '' }}>Nam</option>
                                <option value="nu" {{ old('gioi_tinh') === 'nu' ? 'selected' : '' }}>Nữ</option>
                                <option value="khac" {{ old('gioi_tinh') === 'khac' ? 'selected' : '' }}>Khác</option>
                            </select>
                            @error('gioi_tinh')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Số điện thoại</label>
                            <input type="tel" name="so_dien_thoai" class="form-control @error('so_dien_thoai') is-invalid @enderror" value="{{ old('so_dien_thoai') }}">
                            @error('so_dien_thoai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Địa chỉ</label>
                            <textarea name="dia_chi" class="form-control @error('dia_chi') is-invalid @enderror" rows="2">{{ old('dia_chi') }}</textarea>
                            @error('dia_chi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mt-4">
                            <h6 class="mb-3"><i class="fas fa-heartbeat me-2"></i>Thông tin sức khỏe</h6>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nhóm máu</label>
                            <select name="nhom_mau" class="form-select @error('nhom_mau') is-invalid @enderror">
                                <option value="">-- Chọn nhóm máu --</option>
                                <option value="A" {{ old('nhom_mau') === 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" {{ old('nhom_mau') === 'B' ? 'selected' : '' }}>B</option>
                                <option value="AB" {{ old('nhom_mau') === 'AB' ? 'selected' : '' }}>AB</option>
                                <option value="O" {{ old('nhom_mau') === 'O' ? 'selected' : '' }}>O</option>
                            </select>
                            @error('nhom_mau')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Chiều cao (cm)</label>
                            <input type="number" name="chieu_cao" class="form-control @error('chieu_cao') is-invalid @enderror" value="{{ old('chieu_cao') }}">
                            @error('chieu_cao')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Cân nặng (kg)</label>
                            <input type="number" name="can_nang" class="form-control @error('can_nang') is-invalid @enderror" value="{{ old('can_nang') }}">
                            @error('can_nang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Tiền sử bệnh</label>
                            <textarea name="tien_su_benh" class="form-control @error('tien_su_benh') is-invalid @enderror" rows="3" placeholder="Các bệnh đã mắc, dị ứng thuốc...">{{ old('tien_su_benh') }}</textarea>
                            @error('tien_su_benh')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mt-4">
                            <h6 class="mb-3"><i class="fas fa-id-card me-2"></i>Bảo hiểm y tế (nếu có)</h6>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Mã số BHYT</label>
                            <input type="text" name="bhyt_ma_so" class="form-control @error('bhyt_ma_so') is-invalid @enderror" value="{{ old('bhyt_ma_so') }}">
                            @error('bhyt_ma_so')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Ngày hết hạn BHYT</label>
                            <input type="date" name="bhyt_ngay_het_han" class="form-control @error('bhyt_ngay_het_han') is-invalid @enderror" value="{{ old('bhyt_ngay_het_han') }}">
                            @error('bhyt_ngay_het_han')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Lưu thông tin
                        </button>
                        <a href="{{ route('patient.family.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
