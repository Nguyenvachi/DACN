@extends('layouts.doctor')

@section('title', 'Nhập kết quả X-quang')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">
                <i class="bi bi-file-medical me-2 text-warning"></i>
                Nhập kết quả X-quang
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('doctor.benhan.show', $xQuang->benh_an_id) }}">Bệnh án #{{ $xQuang->benh_an_id }}</a></li>
                    <li class="breadcrumb-item active">Nhập kết quả X-quang</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('doctor.benhan.show', $xQuang->benh_an_id) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="bi bi-clipboard-data me-2"></i>Kết quả X-quang</h5>
                </div>
                <div class="card-body">
                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <form action="{{ route('doctor.x-quang.update', $xQuang->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Ngày chụp <span class="text-danger">*</span></label>
                                <input type="date" name="ngay_chup" class="form-control" 
                                       value="{{ old('ngay_chup', $xQuang->ngay_chup ? $xQuang->ngay_chup->format('Y-m-d') : date('Y-m-d')) }}" required>
                                @error('ngay_chup')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                <select name="trang_thai" class="form-select" required>
                                    <option value="Chờ chụp" {{ old('trang_thai', $xQuang->trang_thai) == 'Chờ chụp' ? 'selected' : '' }}>Chờ chụp</option>
                                    <option value="Đã chụp" {{ old('trang_thai', $xQuang->trang_thai) == 'Đã chụp' ? 'selected' : '' }}>Đã chụp</option>
                                    <option value="Đã có kết quả" {{ old('trang_thai', $xQuang->trang_thai) == 'Đã có kết quả' ? 'selected' : '' }}>Đã có kết quả</option>
                                    <option value="Đã hủy" {{ old('trang_thai', $xQuang->trang_thai) == 'Đã hủy' ? 'selected' : '' }}>Đã hủy</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kỹ thuật chụp</label>
                            <input type="text" name="ky_thuat" class="form-control" 
                                   value="{{ old('ky_thuat', $xQuang->ky_thuat) }}"
                                   placeholder="VD: 1 tư thế thẳng, 2 tư thế (thẳng + nghiêng)...">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mô tả hình ảnh</label>
                            <textarea name="mo_ta_hinh_anh" class="form-control" rows="3"
                                      placeholder="Mô tả các hình ảnh X-quang...">{{ old('mo_ta_hinh_anh', $xQuang->mo_ta_hinh_anh) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tim mạch</label>
                            <textarea name="tim_mach" class="form-control" rows="2"
                                      placeholder="Đánh giá tim mạch...">{{ old('tim_mach', $xQuang->tim_mach) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phổi</label>
                            <textarea name="phoi" class="form-control" rows="2"
                                      placeholder="Đánh giá phổi...">{{ old('phoi', $xQuang->phoi) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Xương khớp</label>
                            <textarea name="xuong_khop" class="form-control" rows="2"
                                      placeholder="Đánh giá xương khớp...">{{ old('xuong_khop', $xQuang->xuong_khop) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Cơ quan khác</label>
                            <textarea name="co_quan_khac" class="form-control" rows="2"
                                      placeholder="Đánh giá các cơ quan khác...">{{ old('co_quan_khac', $xQuang->co_quan_khac) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Chẩn đoán</label>
                            <textarea name="chan_doan" class="form-control" rows="2"
                                      placeholder="Chẩn đoán qua X-quang...">{{ old('chan_doan', $xQuang->chan_doan) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kết luận</label>
                            <textarea name="ket_luan" class="form-control" rows="2"
                                      placeholder="Kết luận tổng hợp...">{{ old('ket_luan', $xQuang->ket_luan) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Đề nghị</label>
                            <textarea name="de_nghi" class="form-control" rows="2"
                                      placeholder="Đề nghị theo dõi, điều trị...">{{ old('de_nghi', $xQuang->de_nghi) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="ghi_chu" class="form-control" rows="2">{{ old('ghi_chu', $xQuang->ghi_chu) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Hình ảnh X-quang</label>
                            <input type="file" name="hinh_anh[]" class="form-control" multiple accept="image/*">
                            <small class="text-muted">Chọn nhiều file hình ảnh (tối đa 5MB/file)</small>
                            @if($xQuang->hinh_anh && count($xQuang->hinh_anh) > 0)
                            <div class="mt-2">
                                <strong>Hình ảnh hiện có:</strong>
                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    @foreach($xQuang->hinh_anh as $img)
                                    <img src="{{ asset('storage/' . $img) }}" class="img-thumbnail" style="width: 120px; height: 120px; object-fit: cover;">
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Lưu kết quả
                            </button>
                            <a href="{{ route('doctor.benhan.show', $xQuang->benh_an_id) }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-2"></i>Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-warning mb-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Thông tin chỉ định</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Loại X-quang:</strong> {{ $xQuang->loai_x_quang }}</p>
                    <p class="mb-2"><strong>Vị trí:</strong> {{ $xQuang->vi_tri }}</p>
                    <p class="mb-2"><strong>Ngày chỉ định:</strong> {{ $xQuang->ngay_chi_dinh->format('d/m/Y') }}</p>
                    <p class="mb-2"><strong>Bác sĩ chỉ định:</strong> {{ $xQuang->bacSiChiDinh->ho_ten ?? 'N/A' }}</p>
                    @if($xQuang->chi_dinh)
                    <p class="mb-0"><strong>Lý do:</strong> {{ $xQuang->chi_dinh }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
