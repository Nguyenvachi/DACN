@extends('layouts.patient-modern')

@section('title', 'Chỉnh Sửa Thành Viên Gia Đình')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1"><i class="fas fa-edit text-primary me-2"></i>Chỉnh Sửa Thành Viên Gia Đình</h3>
            <p class="text-muted mb-0">Cập nhật thông tin thành viên gia đình</p>
        </div>
        <a href="{{ route('patient.family.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay Lại
        </a>
    </div>

    <!-- Edit Form -->
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('patient.family.update', $member->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Thông tin cơ bản -->
                        <h5 class="mb-3 text-primary"><i class="fas fa-user me-2"></i>Thông Tin Cơ Bản</h5>

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="ho_ten" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('ho_ten') is-invalid @enderror"
                                       id="ho_ten" name="ho_ten" value="{{ old('ho_ten', $member->ho_ten) }}" required>
                                @error('ho_ten')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="quan_he" class="form-label">Quan hệ <span class="text-danger">*</span></label>
                                <select class="form-select @error('quan_he') is-invalid @enderror" id="quan_he" name="quan_he" required>
                                    <option value="">-- Chọn quan hệ --</option>
                                    <option value="vo" {{ old('quan_he', $member->quan_he) == 'vo' ? 'selected' : '' }}>Vợ</option>
                                    <option value="chong" {{ old('quan_he', $member->quan_he) == 'chong' ? 'selected' : '' }}>Chồng</option>
                                    <option value="con" {{ old('quan_he', $member->quan_he) == 'con' ? 'selected' : '' }}>Con</option>
                                    <option value="cha" {{ old('quan_he', $member->quan_he) == 'cha' ? 'selected' : '' }}>Cha</option>
                                    <option value="me" {{ old('quan_he', $member->quan_he) == 'me' ? 'selected' : '' }}>Mẹ</option>
                                    <option value="anh" {{ old('quan_he', $member->quan_he) == 'anh' ? 'selected' : '' }}>Anh</option>
                                    <option value="chi" {{ old('quan_he', $member->quan_he) == 'chi' ? 'selected' : '' }}>Chị</option>
                                    <option value="em" {{ old('quan_he', $member->quan_he) == 'em' ? 'selected' : '' }}>Em</option>
                                    <option value="ong" {{ old('quan_he', $member->quan_he) == 'ong' ? 'selected' : '' }}>Ông</option>
                                    <option value="ba" {{ old('quan_he', $member->quan_he) == 'ba' ? 'selected' : '' }}>Bà</option>
                                    <option value="chau" {{ old('quan_he', $member->quan_he) == 'chau' ? 'selected' : '' }}>Cháu</option>
                                    <option value="khac" {{ old('quan_he', $member->quan_he) == 'khac' ? 'selected' : '' }}>Khác</option>
                                </select>
                                @error('quan_he')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="ngay_sinh" class="form-label">Ngày sinh <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('ngay_sinh') is-invalid @enderror"
                                       id="ngay_sinh" name="ngay_sinh" value="{{ old('ngay_sinh', $member->ngay_sinh?->format('Y-m-d')) }}" required>
                                @error('ngay_sinh')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="gioi_tinh" class="form-label">Giới tính <span class="text-danger">*</span></label>
                                <select class="form-select @error('gioi_tinh') is-invalid @enderror" id="gioi_tinh" name="gioi_tinh" required>
                                    <option value="">-- Chọn giới tính --</option>
                                    <option value="Nam" {{ old('gioi_tinh', $member->gioi_tinh) == 'Nam' ? 'selected' : '' }}>Nam</option>
                                    <option value="Nữ" {{ old('gioi_tinh', $member->gioi_tinh) == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                                    <option value="Khác" {{ old('gioi_tinh', $member->gioi_tinh) == 'Khác' ? 'selected' : '' }}>Khác</option>
                                </select>
                                @error('gioi_tinh')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Thông tin liên hệ -->
                        <h5 class="mb-3 text-primary mt-4"><i class="fas fa-address-book me-2"></i>Thông Tin Liên Hệ</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="so_dien_thoai" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('so_dien_thoai') is-invalid @enderror"
                                       id="so_dien_thoai" name="so_dien_thoai" value="{{ old('so_dien_thoai', $member->so_dien_thoai) }}" required>
                                @error('so_dien_thoai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email', $member->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="dia_chi" class="form-label">Địa chỉ</label>
                            <textarea class="form-control @error('dia_chi') is-invalid @enderror"
                                      id="dia_chi" name="dia_chi" rows="2">{{ old('dia_chi', $member->dia_chi) }}</textarea>
                            @error('dia_chi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Thông tin sức khỏe -->
                        <h5 class="mb-3 text-primary mt-4"><i class="fas fa-heartbeat me-2"></i>Thông Tin Sức Khỏe</h5>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="nhom_mau" class="form-label">Nhóm máu</label>
                                <select class="form-select @error('nhom_mau') is-invalid @enderror" id="nhom_mau" name="nhom_mau">
                                    <option value="">-- Chọn nhóm máu --</option>
                                    <option value="A" {{ old('nhom_mau', $member->nhom_mau) == 'A' ? 'selected' : '' }}>A</option>
                                    <option value="B" {{ old('nhom_mau', $member->nhom_mau) == 'B' ? 'selected' : '' }}>B</option>
                                    <option value="O" {{ old('nhom_mau', $member->nhom_mau) == 'O' ? 'selected' : '' }}>O</option>
                                    <option value="AB" {{ old('nhom_mau', $member->nhom_mau) == 'AB' ? 'selected' : '' }}>AB</option>
                                </select>
                                @error('nhom_mau')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="chieu_cao" class="form-label">Chiều cao (cm)</label>
                                <input type="number" step="0.01" class="form-control @error('chieu_cao') is-invalid @enderror"
                                       id="chieu_cao" name="chieu_cao" value="{{ old('chieu_cao', $member->chieu_cao) }}">
                                @error('chieu_cao')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="can_nang" class="form-label">Cân nặng (kg)</label>
                                <input type="number" step="0.01" class="form-control @error('can_nang') is-invalid @enderror"
                                       id="can_nang" name="can_nang" value="{{ old('can_nang', $member->can_nang) }}">
                                @error('can_nang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="tien_su_benh" class="form-label">Tiền sử bệnh</label>
                            <textarea class="form-control @error('tien_su_benh') is-invalid @enderror"
                                      id="tien_su_benh" name="tien_su_benh" rows="3"
                                      placeholder="Ghi rõ các bệnh mãn tính, dị ứng thuốc...">{{ old('tien_su_benh', $member->tien_su_benh) }}</textarea>
                            @error('tien_su_benh')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Thông tin BHYT -->
                        <h5 class="mb-3 text-primary mt-4"><i class="fas fa-id-card me-2"></i>Thông Tin Bảo Hiểm Y Tế</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="bhyt_ma_so" class="form-label">Mã số BHYT</label>
                                <input type="text" class="form-control @error('bhyt_ma_so') is-invalid @enderror"
                                       id="bhyt_ma_so" name="bhyt_ma_so" value="{{ old('bhyt_ma_so', $member->bhyt_ma_so) }}"
                                       placeholder="VD: DN123456789012">
                                @error('bhyt_ma_so')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="bhyt_ngay_het_han" class="form-label">Ngày hết hạn BHYT</label>
                                <input type="date" class="form-control @error('bhyt_ngay_het_han') is-invalid @enderror"
                                       id="bhyt_ngay_het_han" name="bhyt_ngay_het_han"
                                       value="{{ old('bhyt_ngay_het_han', $member->bhyt_ngay_het_han?->format('Y-m-d')) }}">
                                @error('bhyt_ngay_het_han')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Ảnh đại diện -->
                        <h5 class="mb-3 text-primary mt-4"><i class="fas fa-camera me-2"></i>Ảnh Đại Diện</h5>

                        @if($member->avatar)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $member->avatar) }}" alt="Avatar" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                        </div>
                        @endif

                        <div class="mb-3">
                            <label for="avatar" class="form-label">Chọn ảnh mới (nếu muốn thay đổi)</label>
                            <input type="file" class="form-control @error('avatar') is-invalid @enderror"
                                   id="avatar" name="avatar" accept="image/*">
                            <small class="text-muted">Định dạng: JPG, PNG. Kích thước tối đa: 2MB</small>
                            @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('patient.family.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Cập Nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
