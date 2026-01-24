@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="fas fa-edit me-2"></i>Sửa loại X-Quang
            </h2>

            <a href="{{ route('admin.loai-x-quang.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">

                <form action="{{ route('admin.loai-x-quang.update', $loaiXQuang) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tên loại X-Quang <span class="text-danger">*</span></label>
                            <input type="text" name="ten" class="form-control form-control-lg" value="{{ old('ten', $loaiXQuang->ten) }}" required>
                            @error('ten')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Mã</label>
                            <input type="text" name="ma" class="form-control form-control-lg" value="{{ old('ma', $loaiXQuang->ma) }}">
                            @error('ma')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Giá (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" name="gia" class="form-control form-control-lg" value="{{ old('gia', $loaiXQuang->gia) }}" required>
                            @error('gia')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Thời gian ước tính (phút) <span class="text-danger">*</span></label>
                            <input type="number" name="thoi_gian_uoc_tinh" class="form-control form-control-lg" value="{{ old('thoi_gian_uoc_tinh', $loaiXQuang->thoi_gian_uoc_tinh) }}" required>
                            @error('thoi_gian_uoc_tinh')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Mô tả</label>
                            <textarea name="mo_ta" rows="4" class="form-control form-control-lg" style="resize:none">{{ old('mo_ta', $loaiXQuang->mo_ta) }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phòng thực hiện</label>
                            <select name="phong_id" class="form-select">
                                <option value="">-- Không chọn --</option>
                                @foreach($phongs as $p)
                                    <option value="{{ $p->id }}" {{ (string) old('phong_id', $loaiXQuang->phong_id) === (string) $p->id ? 'selected' : '' }}>{{ $p->ten }}</option>
                                @endforeach
                            </select>
                            @error('phong_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Trạng thái</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" role="switch" name="active" value="1" {{ old('active', $loaiXQuang->active) ? 'checked' : '' }}>
                                <label class="form-check-label">Đang bật</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Chuyên khoa liên kết</label>
                            <select name="chuyen_khoa_ids[]" class="form-select" multiple>
                                @foreach($chuyenKhoas as $ck)
                                    <option value="{{ $ck->id }}" {{ in_array($ck->id, old('chuyen_khoa_ids', $selected ?? []), true) ? 'selected' : '' }}>{{ $ck->ten }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">(Giữ Ctrl/Cmd để chọn nhiều chuyên khoa)</small>
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-end">
                        <a href="{{ route('admin.loai-x-quang.index') }}" class="btn btn-light me-2">
                            <i class="fas fa-times"></i> Hủy
                        </a>

                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-1"></i> Lưu
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>
@endsection
