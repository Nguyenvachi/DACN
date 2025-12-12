@extends('layouts.admin')

@section('title', 'Chỉnh sửa dịch vụ')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Chỉnh sửa dịch vụ: {{ $dichVu->ten_dich_vu }}</h2>
        <a href="{{ route('admin.dich-vu.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.dich-vu.update', $dichVu) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="ten_dich_vu" class="form-label">
                                Tên dịch vụ <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('ten_dich_vu') is-invalid @enderror" 
                                   id="ten_dich_vu" 
                                   name="ten_dich_vu" 
                                   value="{{ old('ten_dich_vu', $dichVu->ten_dich_vu) }}" 
                                   required>
                            @error('ten_dich_vu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="loai" class="form-label">
                                    Loại dịch vụ <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('loai') is-invalid @enderror" 
                                        id="loai" 
                                        name="loai" 
                                        required>
                                    <option value="">-- Chọn loại --</option>
                                    <option value="Cơ bản" {{ old('loai', $dichVu->loai) == 'Cơ bản' ? 'selected' : '' }}>Cơ bản</option>
                                    <option value="Nâng cao" {{ old('loai', $dichVu->loai) == 'Nâng cao' ? 'selected' : '' }}>Nâng cao</option>
                                </select>
                                @error('loai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="chuyen_khoa_id" class="form-label">Chuyên khoa</label>
                                <select class="form-select @error('chuyen_khoa_id') is-invalid @enderror" 
                                        id="chuyen_khoa_id" 
                                        name="chuyen_khoa_id">
                                    <option value="">-- Không xác định --</option>
                                    @foreach ($chuyenKhoas as $ck)
                                        <option value="{{ $ck->id }}" {{ old('chuyen_khoa_id', $dichVu->chuyen_khoa_id) == $ck->id ? 'selected' : '' }}>
                                            {{ $ck->ten_chuyen_khoa }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('chuyen_khoa_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="gia_tien" class="form-label">
                                    Giá tiền (VNĐ) <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control @error('gia_tien') is-invalid @enderror" 
                                       id="gia_tien" 
                                       name="gia_tien" 
                                       value="{{ old('gia_tien', $dichVu->gia_tien) }}" 
                                       min="0" 
                                       step="1000" 
                                       required>
                                @error('gia_tien')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Giá hiện tại: {{ number_format($dichVu->gia_tien) }}đ</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="thoi_gian" class="form-label">Thời gian (phút)</label>
                                <input type="number" 
                                       class="form-control @error('thoi_gian') is-invalid @enderror" 
                                       id="thoi_gian" 
                                       name="thoi_gian" 
                                       value="{{ old('thoi_gian', $dichVu->thoi_gian) }}" 
                                       min="1">
                                @error('thoi_gian')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Thời gian thực hiện dịch vụ</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="mo_ta" class="form-label">Mô tả</label>
                            <textarea class="form-control @error('mo_ta') is-invalid @enderror" 
                                      id="mo_ta" 
                                      name="mo_ta" 
                                      rows="4">{{ old('mo_ta', $dichVu->mo_ta) }}</textarea>
                            @error('mo_ta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" 
                                   class="form-check-input" 
                                   id="hoat_dong" 
                                   name="hoat_dong" 
                                   value="1" 
                                   {{ old('hoat_dong', $dichVu->hoat_dong) ? 'checked' : '' }}>
                            <label class="form-check-label" for="hoat_dong">
                                Dịch vụ đang hoạt động
                            </label>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.dich-vu.index') }}" class="btn btn-secondary">Hủy</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Cập nhật dịch vụ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-info-circle"></i> Thông tin dịch vụ
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <small class="text-muted">Mã dịch vụ:</small><br>
                        <strong>#{{ $dichVu->id }}</strong>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Ngày tạo:</small><br>
                        {{ $dichVu->created_at->format('d/m/Y H:i') }}
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Cập nhật lần cuối:</small><br>
                        {{ $dichVu->updated_at->format('d/m/Y H:i') }}
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <i class="fas fa-exclamation-triangle"></i> Lưu ý
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li>Thay đổi giá tiền sẽ ảnh hưởng đến các đơn hàng mới</li>
                        <li>Các đơn hàng cũ vẫn giữ nguyên giá cũ</li>
                        <li>Tắt dịch vụ sẽ ẩn khỏi danh sách đặt lịch</li>
                        <li>Không thể xóa dịch vụ đã được sử dụng</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
