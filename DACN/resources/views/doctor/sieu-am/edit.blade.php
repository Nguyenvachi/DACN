@extends('layouts.doctor')

@section('title', 'Nhập kết quả siêu âm')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: #1f2937;">
                <i class="fas fa-baby me-2" style="color: #ec4899;"></i>
                Nhập kết quả siêu âm thai
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('doctor.benhan.edit', $sieuAm->benh_an_id) }}">Bệnh án #{{ $sieuAm->benh_an_id }}</a></li>
                    <li class="breadcrumb-item active">Kết quả siêu âm</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('doctor.benhan.edit', $sieuAm->benh_an_id) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            {{-- Thông tin chỉ định --}}
            <div class="card vc-card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-info-circle me-2" style="color: #3b82f6;"></i>
                        Thông tin chỉ định
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Bệnh nhân:</strong> {{ $sieuAm->benhAn->user->name }}</p>
                            <p class="mb-2"><strong>Loại siêu âm:</strong> {{ $sieuAm->loai_sieu_am }}</p>
                            <p class="mb-2"><strong>Ngày chỉ định:</strong> {{ \Carbon\Carbon::parse($sieuAm->ngay_chi_dinh)->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            @if($sieuAm->ly_do_chi_dinh)
                            <p class="mb-2"><strong>Lý do chỉ định:</strong><br>{{ $sieuAm->ly_do_chi_dinh }}</p>
                            @endif
                            @if($sieuAm->ghi_chu)
                            <p class="mb-2"><strong>Ghi chú:</strong><br>{{ $sieuAm->ghi_chu }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form kết quả --}}
            <form action="{{ route('doctor.sieu-am.update', $sieuAm->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Thông tin thai kỳ --}}
                <div class="card vc-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-alt me-2" style="color: #10b981;"></i>
                            Thông tin thai kỳ
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tuổi thai (tuần) <span class="text-danger">*</span></label>
                                <input type="number" name="tuoi_thai_tuan" class="form-control" 
                                       value="{{ old('tuoi_thai_tuan', $sieuAm->tuoi_thai_tuan) }}" 
                                       min="0" max="42" step="1" required>
                                @error('tuoi_thai_tuan')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tuổi thai (ngày)</label>
                                <input type="number" name="tuoi_thai_ngay" class="form-control" 
                                       value="{{ old('tuoi_thai_ngay', $sieuAm->tuoi_thai_ngay) }}" 
                                       min="0" max="6" step="1">
                                <small class="text-muted">VD: 12 tuần 3 ngày</small>
                                @error('tuoi_thai_ngay')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Các chỉ số thai nhi --}}
                <div class="card vc-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-ruler me-2" style="color: #8b5cf6;"></i>
                            Các chỉ số thai nhi
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">CRL - Chiều dài đầu mông (mm)</label>
                                <input type="number" name="chieu_dai_dau_mong" class="form-control" 
                                       value="{{ old('chieu_dai_dau_mong', $sieuAm->chieu_dai_dau_mong) }}" 
                                       step="0.1" placeholder="VD: 65.3">
                                <small class="text-muted">Crown-Rump Length</small>
                                @error('chieu_dai_dau_mong')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">BPD - Đường kính hai đỉnh (mm)</label>
                                <input type="number" name="duong_kinh_hai_dinh" class="form-control" 
                                       value="{{ old('duong_kinh_hai_dinh', $sieuAm->duong_kinh_hai_dinh) }}" 
                                       step="0.1" placeholder="VD: 85.2">
                                <small class="text-muted">Biparietal Diameter</small>
                                @error('duong_kinh_hai_dinh')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">AC - Chu vi bụng (mm)</label>
                                <input type="number" name="chu_vi_bung" class="form-control" 
                                       value="{{ old('chu_vi_bung', $sieuAm->chu_vi_bung) }}" 
                                       step="0.1" placeholder="VD: 285.5">
                                <small class="text-muted">Abdominal Circumference</small>
                                @error('chu_vi_bung')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">FL - Chiều dài xương đùi (mm)</label>
                                <input type="number" name="chieu_dai_xuong_dui" class="form-control" 
                                       value="{{ old('chieu_dai_xuong_dui', $sieuAm->chieu_dai_xuong_dui) }}" 
                                       step="0.1" placeholder="VD: 65.8">
                                <small class="text-muted">Femur Length</small>
                                @error('chieu_dai_xuong_dui')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">HC - Chu vi đầu (mm)</label>
                                <input type="number" name="chu_vi_dau" class="form-control" 
                                       value="{{ old('chu_vi_dau', $sieuAm->chu_vi_dau) }}" 
                                       step="0.1" placeholder="VD: 310.2">
                                <small class="text-muted">Head Circumference</small>
                                @error('chu_vi_dau')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Cân nặng ước tính (gram)</label>
                                <input type="number" name="can_nang_uoc_tinh" class="form-control" 
                                       value="{{ old('can_nang_uoc_tinh', $sieuAm->can_nang_uoc_tinh) }}" 
                                       step="1" placeholder="VD: 2850">
                                <small class="text-muted">Estimated Fetal Weight</small>
                                @error('can_nang_uoc_tinh')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Thông tin khác --}}
                <div class="card vc-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-notes-medical me-2" style="color: #f59e0b;"></i>
                            Thông tin khác
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nhịp tim thai (bpm)</label>
                                <input type="number" name="nhip_tim_thai" class="form-control" 
                                       value="{{ old('nhip_tim_thai', $sieuAm->nhip_tim_thai) }}" 
                                       step="1" placeholder="VD: 145">
                                <small class="text-muted">Fetal Heart Rate</small>
                                @error('nhip_tim_thai')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">AFI - Lượng nước ối (cm)</label>
                                <input type="number" name="luong_nuoc_oi" class="form-control" 
                                       value="{{ old('luong_nuoc_oi', $sieuAm->luong_nuoc_oi) }}" 
                                       step="0.1" placeholder="VD: 12.5">
                                <small class="text-muted">Amniotic Fluid Index</small>
                                @error('luong_nuoc_oi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Vị trí thai</label>
                                <select name="vi_tri_thai" class="form-select">
                                    <option value="">-- Chọn vị trí --</option>
                                    <option value="Ngôi đầu" {{ old('vi_tri_thai', $sieuAm->vi_tri_thai) === 'Ngôi đầu' ? 'selected' : '' }}>Ngôi đầu</option>
                                    <option value="Ngôi mông" {{ old('vi_tri_thai', $sieuAm->vi_tri_thai) === 'Ngôi mông' ? 'selected' : '' }}>Ngôi mông</option>
                                    <option value="Ngôi ngang" {{ old('vi_tri_thai', $sieuAm->vi_tri_thai) === 'Ngôi ngang' ? 'selected' : '' }}>Ngôi ngang</option>
                                </select>
                                @error('vi_tri_thai')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Giới tính</label>
                                <select name="gioi_tinh" class="form-select">
                                    <option value="">-- Chưa xác định --</option>
                                    <option value="Nam" {{ old('gioi_tinh', $sieuAm->gioi_tinh) === 'Nam' ? 'selected' : '' }}>Nam</option>
                                    <option value="Nữ" {{ old('gioi_tinh', $sieuAm->gioi_tinh) === 'Nữ' ? 'selected' : '' }}>Nữ</option>
                                </select>
                                @error('gioi_tinh')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Vị trí nhau thai</label>
                                <input type="text" name="vi_tri_nhau_thai" class="form-control" 
                                       value="{{ old('vi_tri_nhau_thai', $sieuAm->vi_tri_nhau_thai) }}" 
                                       placeholder="VD: Thành trước, thành sau, đáy...">
                                @error('vi_tri_nhau_thai')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kết luận --}}
                <div class="card vc-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-file-medical-alt me-2" style="color: #ef4444;"></i>
                            Kết luận và đánh giá
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Kết luận <span class="text-danger">*</span></label>
                            <textarea name="ket_luan" class="form-control" rows="4" required
                                      placeholder="VD: Thai phát triển tốt, tương xứng tuổi thai. Không thấy dấu hiệu bất thường...">{{ old('ket_luan', $sieuAm->ket_luan) }}</textarea>
                            @error('ket_luan')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Đánh giá chung</label>
                            <textarea name="danh_gia" class="form-control" rows="3"
                                      placeholder="Đánh giá tổng quan về tình trạng thai nhi...">{{ old('danh_gia', $sieuAm->danh_gia) }}</textarea>
                            @error('danh_gia')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Hình ảnh siêu âm</label>
                            <input type="file" name="hinh_anh[]" class="form-control" multiple accept="image/*">
                            <small class="text-muted">Có thể chọn nhiều ảnh. Định dạng: JPG, PNG</small>
                            @error('hinh_anh')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            
                            @if($sieuAm->hinh_anh && is_array($sieuAm->hinh_anh))
                            <div class="mt-2">
                                <small class="text-muted">Hình ảnh hiện có:</small>
                                <div class="d-flex flex-wrap gap-2 mt-1">
                                    @foreach($sieuAm->hinh_anh as $img)
                                    <img src="{{ asset('storage/' . $img) }}" alt="Siêu âm" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="d-flex gap-2 justify-content-end mb-4">
                    <a href="{{ route('doctor.benhan.edit', $sieuAm->benh_an_id) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Hủy
                    </a>
                    <button type="submit" class="btn vc-btn-primary">
                        <i class="fas fa-save me-2"></i>Lưu kết quả
                    </button>
                </div>
            </form>
        </div>

        <div class="col-lg-4">
            {{-- Hướng dẫn --}}
            <div class="card vc-card">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-info-circle me-2" style="color: #10b981;"></i>
                        Hướng dẫn nhập kết quả
                    </h6>
                    <ul class="mb-0 small">
                        <li><strong>CRL:</strong> Đo từ đầu đến mông, dùng cho thai 7-14 tuần</li>
                        <li><strong>BPD:</strong> Đường kính hai đỉnh xương sọ</li>
                        <li><strong>AC:</strong> Chu vi vòng bụng thai nhi</li>
                        <li><strong>FL:</strong> Chiều dài xương đùi</li>
                        <li><strong>HC:</strong> Chu vi vòng đầu</li>
                        <li><strong>AFI:</strong> Chỉ số nước ối (bình thường: 5-25cm)</li>
                        <li><strong>Nhịp tim:</strong> Bình thường 120-160 bpm</li>
                    </ul>
                </div>
            </div>

            {{-- Giá trị tham khảo --}}
            <div class="card vc-card mt-3">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-chart-line me-2" style="color: #f59e0b;"></i>
                        Giá trị tham khảo
                    </h6>
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Tuần thai</th>
                                <th>BPD (mm)</th>
                                <th>FL (mm)</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                            <tr><td>12</td><td>20-24</td><td>7-10</td></tr>
                            <tr><td>16</td><td>33-37</td><td>18-22</td></tr>
                            <tr><td>20</td><td>46-50</td><td>29-33</td></tr>
                            <tr><td>24</td><td>58-62</td><td>41-45</td></tr>
                            <tr><td>28</td><td>68-72</td><td>51-55</td></tr>
                            <tr><td>32</td><td>78-82</td><td>60-64</td></tr>
                            <tr><td>36</td><td>86-90</td><td>67-71</td></tr>
                            <tr><td>40</td><td>92-96</td><td>73-77</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
