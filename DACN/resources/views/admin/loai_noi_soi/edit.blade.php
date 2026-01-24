@extends('layouts.admin')

@section('title', 'Sửa Loại Nội soi')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Sửa Loại Nội soi</h4>
        <a href="{{ route('admin.loai-noi-soi.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Vui lòng kiểm tra lại:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.loai-noi-soi.update', $loaiNoiSoi->id) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Tên <span class="text-danger">*</span></label>
                        <input type="text" name="ten" class="form-control" value="{{ old('ten', $loaiNoiSoi->ten) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Mã</label>
                        <input type="text" name="ma" class="form-control" value="{{ old('ma', $loaiNoiSoi->ma) }}">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Mô tả</label>
                        <textarea name="mo_ta" class="form-control" rows="3">{{ old('mo_ta', $loaiNoiSoi->mo_ta) }}</textarea>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Giá <span class="text-danger">*</span></label>
                        <input type="number" name="gia" class="form-control" value="{{ old('gia', (float) $loaiNoiSoi->gia) }}" min="0" step="1000" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Thời gian ước tính (phút) <span class="text-danger">*</span></label>
                        <input type="number" name="thoi_gian_uoc_tinh" class="form-control" value="{{ old('thoi_gian_uoc_tinh', (int) $loaiNoiSoi->thoi_gian_uoc_tinh) }}" min="1" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Phòng</label>
                        <select name="phong_id" class="form-select">
                            <option value="">-- Chọn phòng --</option>
                            @foreach($phongs as $p)
                                <option value="{{ $p->id }}" {{ (string) old('phong_id', $loaiNoiSoi->phong_id) === (string) $p->id ? 'selected' : '' }}>
                                    {{ $p->ten }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Chuyên khoa</label>
                        <select name="chuyen_khoa_ids[]" class="form-select" multiple>
                            @foreach($chuyenKhoas as $ck)
                                <option value="{{ $ck->id }}" {{ in_array($ck->id, $selected ?? [], true) ? 'selected' : '' }}>
                                    {{ $ck->ten }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Giữ Ctrl (Windows) để chọn nhiều.</small>
                    </div>

                    <div class="col-md-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="active" id="active" value="1" {{ old('active', $loaiNoiSoi->active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="active">Active</label>
                        </div>
                    </div>
                </div>

                <div class="mt-3 d-flex gap-2">
                    <button class="btn btn-primary">Cập nhật</button>
                    <a href="{{ route('admin.loai-noi-soi.index') }}" class="btn btn-light">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
