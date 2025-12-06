@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Tạo mã giảm giá</h1>
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.coupons.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mã giảm giá <span class="text-danger">*</span></label>
                        <input type="text" name="ma_giam_gia" class="form-control @error('ma_giam_gia') is-invalid @enderror" value="{{ old('ma_giam_gia') }}" required>
                        @error('ma_giam_gia')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tên <span class="text-danger">*</span></label>
                        <input type="text" name="ten" class="form-control @error('ten') is-invalid @enderror" value="{{ old('ten') }}" required>
                        @error('ten')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mô tả</label>
                    <textarea name="mo_ta" class="form-control @error('mo_ta') is-invalid @enderror" rows="3">{{ old('mo_ta') }}</textarea>
                    @error('mo_ta')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Loại <span class="text-danger">*</span></label>
                        <select name="loai" class="form-select @error('loai') is-invalid @enderror" required>
                            <option value="phan_tram" @selected(old('loai')==='phan_tram')>% Phần trăm</option>
                            <option value="tien_mat" @selected(old('loai')==='tien_mat')>Tiền mặt (VNĐ)</option>
                        </select>
                        @error('loai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Giá trị <span class="text-danger">*</span></label>
                        <input type="number" name="gia_tri" step="0.01" class="form-control @error('gia_tri') is-invalid @enderror" value="{{ old('gia_tri') }}" required>
                        @error('gia_tri')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">Nhập % nếu chọn phần trăm, hoặc số tiền nếu chọn tiền mặt</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Giảm tối đa (cho loại %)</label>
                        <input type="number" name="giam_toi_da" step="0.01" class="form-control @error('giam_toi_da') is-invalid @enderror" value="{{ old('giam_toi_da') }}">
                        @error('giam_toi_da')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">Để trống nếu không giới hạn</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Đơn hàng tối thiểu</label>
                        <input type="number" name="don_toi_thieu" step="0.01" class="form-control @error('don_toi_thieu') is-invalid @enderror" value="{{ old('don_toi_thieu') }}">
                        @error('don_toi_thieu')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">Để trống nếu không giới hạn</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                        <input type="date" name="ngay_bat_dau" class="form-control @error('ngay_bat_dau') is-invalid @enderror" value="{{ old('ngay_bat_dau') }}" required>
                        @error('ngay_bat_dau')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
                        <input type="date" name="ngay_ket_thuc" class="form-control @error('ngay_ket_thuc') is-invalid @enderror" value="{{ old('ngay_ket_thuc') }}" required>
                        @error('ngay_ket_thuc')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Số lần sử dụng tối đa</label>
                    <input type="number" name="so_lan_su_dung_toi_da" class="form-control @error('so_lan_su_dung_toi_da') is-invalid @enderror" value="{{ old('so_lan_su_dung_toi_da') }}">
                    @error('so_lan_su_dung_toi_da')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted">Để trống nếu không giới hạn</small>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="kich_hoat" value="1" class="form-check-input @error('kich_hoat') is-invalid @enderror" id="kich_hoat" @checked(old('kich_hoat'))>
                        <label class="form-check-label" for="kich_hoat">
                            Kích hoạt ngay
                        </label>
                        @error('kich_hoat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Lưu
                    </button>
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
