@extends('layouts.doctor')

@section('title', 'Nhập kết quả nội soi')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">
                <i class="bi bi-camera-video me-2 text-info"></i>
                Nhập kết quả nội soi
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('doctor.benhan.show', $noiSoi->benh_an_id) }}">Bệnh án #{{ $noiSoi->benh_an_id }}</a></li>
                    <li class="breadcrumb-item active">Nhập kết quả</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('doctor.benhan.show', $noiSoi->benh_an_id) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-clipboard-data me-2"></i>Kết quả nội soi</h5>
                </div>
                <div class="card-body">
                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <form action="{{ route('doctor.noi-soi.update', $noiSoi->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Ngày thực hiện <span class="text-danger">*</span></label>
                                <input type="date" name="ngay_thuc_hien" class="form-control" 
                                       value="{{ old('ngay_thuc_hien', $noiSoi->ngay_thuc_hien ? $noiSoi->ngay_thuc_hien->format('Y-m-d') : date('Y-m-d')) }}" required>
                                @error('ngay_thuc_hien')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                <select name="trang_thai" class="form-select" required>
                                    <option value="Chờ thực hiện" {{ old('trang_thai', $noiSoi->trang_thai) == 'Chờ thực hiện' ? 'selected' : '' }}>Chờ thực hiện</option>
                                    <option value="Đang thực hiện" {{ old('trang_thai', $noiSoi->trang_thai) == 'Đang thực hiện' ? 'selected' : '' }}>Đang thực hiện</option>
                                    <option value="Hoàn thành" {{ old('trang_thai', $noiSoi->trang_thai) == 'Hoàn thành' ? 'selected' : '' }}>Hoàn thành</option>
                                    <option value="Đã hủy" {{ old('trang_thai', $noiSoi->trang_thai) == 'Đã hủy' ? 'selected' : '' }}>Đã hủy</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mô tả hình ảnh nội soi</label>
                            <textarea name="mo_ta_hinh_anh" class="form-control" rows="3"
                                      placeholder="Mô tả các hình ảnh quan sát được qua nội soi...">{{ old('mo_ta_hinh_anh', $noiSoi->mo_ta_hinh_anh) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tổn thương phát hiện</label>
                            <textarea name="ton_thuong" class="form-control" rows="3"
                                      placeholder="Mô tả các tổn thương, bất thường...">{{ old('ton_thuong', $noiSoi->ton_thuong) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Chẩn đoán</label>
                            <textarea name="chan_doan" class="form-control" rows="2"
                                      placeholder="Chẩn đoán sau nội soi...">{{ old('chan_doan', $noiSoi->chan_doan) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Sinh thiết</label>
                            <textarea name="sinh_thiet" class="form-control" rows="2"
                                      placeholder="Có lấy mẫu sinh thiết không? Vị trí? Số lượng...">{{ old('sinh_thiet', $noiSoi->sinh_thiet) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Xử trí trong khi nội soi</label>
                            <textarea name="xu_tri" class="form-control" rows="2"
                                      placeholder="Các thủ thuật, can thiệp thực hiện...">{{ old('xu_tri', $noiSoi->xu_tri) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Biến chứng</label>
                            <textarea name="bien_chung" class="form-control" rows="2"
                                      placeholder="Ghi nhận biến chứng nếu có...">{{ old('bien_chung', $noiSoi->bien_chung) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kết luận</label>
                            <textarea name="ket_luan" class="form-control" rows="2"
                                      placeholder="Kết luận tổng hợp...">{{ old('ket_luan', $noiSoi->ket_luan) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Đề nghị</label>
                            <textarea name="de_nghi" class="form-control" rows="2"
                                      placeholder="Đề nghị theo dõi, điều trị...">{{ old('de_nghi', $noiSoi->de_nghi) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="ghi_chu" class="form-control" rows="2">{{ old('ghi_chu', $noiSoi->ghi_chu) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Hình ảnh nội soi</label>
                            <input type="file" name="hinh_anh[]" class="form-control" multiple accept="image/*">
                            <small class="text-muted">Chọn nhiều file hình ảnh (tối đa 5MB/file)</small>
                            @if($noiSoi->hinh_anh && count($noiSoi->hinh_anh) > 0)
                            <div class="mt-2">
                                <strong>Hình ảnh hiện có:</strong>
                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    @foreach($noiSoi->hinh_anh as $img)
                                    <img src="{{ asset('storage/' . $img) }}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Lưu kết quả
                            </button>
                            <a href="{{ route('doctor.benhan.show', $noiSoi->benh_an_id) }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-2"></i>Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-info mb-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Thông tin chỉ định</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Loại nội soi:</strong> {{ $noiSoi->loai_noi_soi }}</p>
                    <p class="mb-2"><strong>Ngày chỉ định:</strong> {{ $noiSoi->ngay_chi_dinh->format('d/m/Y') }}</p>
                    <p class="mb-2"><strong>Bác sĩ chỉ định:</strong> {{ $noiSoi->bacSiChiDinh->ho_ten ?? 'N/A' }}</p>
                    @if($noiSoi->chi_dinh)
                    <p class="mb-2"><strong>Lý do:</strong> {{ $noiSoi->chi_dinh }}</p>
                    @endif
                    @if($noiSoi->chuan_bi)
                    <p class="mb-0"><strong>Chuẩn bị:</strong> {{ $noiSoi->chuan_bi }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
