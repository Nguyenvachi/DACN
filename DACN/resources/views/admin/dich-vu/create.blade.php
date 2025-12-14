@extends('layouts.admin')

@section('title', 'Thêm dịch vụ mới')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Thêm dịch vụ mới</h2>
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
                    <form action="{{ route('admin.dich-vu.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="ten_dich_vu" class="form-label">
                                Tên dịch vụ <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('ten_dich_vu') is-invalid @enderror" 
                                   id="ten_dich_vu" 
                                   name="ten_dich_vu" 
                                   value="{{ old('ten_dich_vu') }}" 
                                   required>
                            @error('ten_dich_vu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="chuyen_khoa_id" class="form-label">Chuyên khoa</label>
                                <select class="form-select @error('chuyen_khoa_id') is-invalid @enderror" 
                                        id="chuyen_khoa_id" 
                                        name="chuyen_khoa_id">
                                    <option value="">-- Không xác định --</option>
                                    @foreach ($chuyenKhoas as $ck)
                                        <option value="{{ $ck->id }}" {{ old('chuyen_khoa_id') == $ck->id ? 'selected' : '' }}>
                                            {{ $ck->ten_chuyen_khoa }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('chuyen_khoa_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="alert alert-info small mb-0">
                                    Loại dịch vụ được đặt mặc định là <strong>Cơ bản</strong>.
                                </div>
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
                                       value="{{ old('gia_tien') }}" 
                                       min="0" 
                                       step="1000" 
                                       required>
                                @error('gia_tien')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Nhập giá tiền (ví dụ: 200000 cho 200,000đ)</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="thoi_gian" class="form-label">Thời gian (phút)</label>
                                <input type="number" 
                                       class="form-control @error('thoi_gian') is-invalid @enderror" 
                                       id="thoi_gian" 
                                       name="thoi_gian" 
                                       value="{{ old('thoi_gian') }}" 
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
                                      rows="4">{{ old('mo_ta') }}</textarea>
                            @error('mo_ta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                                <input type="hidden" name="hoat_dong" value="0">
                                <input type="checkbox" 
                                    class="form-check-input" 
                                    id="hoat_dong" 
                                    name="hoat_dong" 
                                    value="1" 
                                    {{ old('hoat_dong', 1) ? 'checked' : '' }}>
                            <label class="form-check-label" for="hoat_dong">
                                Dịch vụ đang hoạt động
                            </label>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.dich-vu.index') }}" class="btn btn-secondary">Hủy</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Lưu dịch vụ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-info-circle"></i> Hướng dẫn
                </div>
                <div class="card-body">
                    <h6 class="mt-3">Lưu ý</h6>
                    <ul class="small">
                        <li>Tên dịch vụ nên rõ ràng, dễ hiểu</li>
                        <li>Giá tiền phải lớn hơn hoặc bằng 0</li>
                        <li>Thời gian giúp lên lịch hẹn chính xác hơn</li>
                        <li>Chỉ dịch vụ hoạt động mới hiển thị cho bệnh nhân</li>
                    </ul>

                    <h6 class="mt-3">Ví dụ</h6>
                    <ul class="small">
                        <li>Siêu âm thai 3D - 400,000đ - 30 phút</li>
                        <li>Xét nghiệm máu tổng quát - 150,000đ - 15 phút</li>
                        <li>Khám sản khoa tổng quát - 200,000đ - 20 phút</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
