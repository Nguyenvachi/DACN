@extends('layouts.doctor')

@section('title', 'Nhập kết quả thủ thuật')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: #1f2937;">
                <i class="fas fa-procedures me-2" style="color: #f59e0b;"></i>
                Nhập kết quả thủ thuật
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('doctor.benhan.edit', $thuThuat->benh_an_id) }}">Bệnh án #{{ $thuThuat->benh_an_id }}</a></li>
                    <li class="breadcrumb-item active">Kết quả thủ thuật</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('doctor.benhan.edit', $thuThuat->benh_an_id) }}" class="btn btn-outline-secondary">
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
                            <p class="mb-2"><strong>Bệnh nhân:</strong> {{ $thuThuat->benhAn->user->name }}</p>
                            <p class="mb-2"><strong>Thủ thuật:</strong> {{ $thuThuat->ten_thu_thuat }}</p>
                            <p class="mb-2"><strong>Ngày chỉ định:</strong> {{ \Carbon\Carbon::parse($thuThuat->ngay_chi_dinh)->format('d/m/Y H:i') }}</p>
                            <p class="mb-2"><strong>Trạng thái:</strong>
                                @if($thuThuat->trang_thai === 'Đã hoàn thành')
                                <span class="badge bg-success">Đã hoàn thành</span>
                                @elseif($thuThuat->trang_thai === 'Đang thực hiện')
                                <span class="badge bg-warning">Đang thực hiện</span>
                                @else
                                <span class="badge bg-secondary">Chờ thực hiện</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Chi tiết trước thủ thuật:</strong></p>
                            <div class="p-2 bg-light rounded">
                                <small>{{ $thuThuat->chi_tiet_truoc_thu_thuat }}</small>
                            </div>
                            @if($thuThuat->ghi_chu)
                            <p class="mb-2 mt-2"><strong>Ghi chú:</strong><br>
                                <small>{{ $thuThuat->ghi_chu }}</small>
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form kết quả --}}
            <form action="{{ route('doctor.thu-thuat.update', $thuThuat->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Quá trình thực hiện --}}
                <div class="card vc-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-clipboard-list me-2" style="color: #10b981;"></i>
                            Quá trình thực hiện
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Ngày thực hiện <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="ngay_thuc_hien" class="form-control" 
                                   value="{{ old('ngay_thuc_hien', $thuThuat->ngay_thuc_hien ? \Carbon\Carbon::parse($thuThuat->ngay_thuc_hien)->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}" 
                                   required>
                            @error('ngay_thuc_hien')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mô tả quy trình thực hiện <span class="text-danger">*</span></label>
                            <textarea name="mo_ta_quy_trinh" class="form-control" rows="6" required
                                      placeholder="Mô tả chi tiết các bước thực hiện thủ thuật, phương pháp, dụng cụ sử dụng...">{{ old('mo_ta_quy_trinh', $thuThuat->mo_ta_quy_trinh) }}</textarea>
                            <small class="text-muted">Ghi rõ: vị trí thực hiện, phương pháp gây tê/mê, các bước thực hiện...</small>
                            @error('mo_ta_quy_trinh')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kết quả thủ thuật <span class="text-danger">*</span></label>
                            <textarea name="ket_qua" class="form-control" rows="4" required
                                      placeholder="VD: Thủ thuật thành công, lấy được mẫu nước ối 20ml, màu trong, không lẫn máu...">{{ old('ket_qua', $thuThuat->ket_qua) }}</textarea>
                            @error('ket_qua')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Thời gian thực hiện (phút)</label>
                                <input type="number" name="thoi_gian_thuc_hien" class="form-control" 
                                       value="{{ old('thoi_gian_thuc_hien', $thuThuat->thoi_gian_thuc_hien) }}" 
                                       min="1" step="1" placeholder="VD: 30">
                                @error('thoi_gian_thuc_hien')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                <select name="trang_thai" class="form-select" required>
                                    <option value="Đang thực hiện" {{ old('trang_thai', $thuThuat->trang_thai) === 'Đang thực hiện' ? 'selected' : '' }}>Đang thực hiện</option>
                                    <option value="Đã hoàn thành" {{ old('trang_thai', $thuThuat->trang_thai) === 'Đã hoàn thành' ? 'selected' : '' }}>Đã hoàn thành</option>
                                </select>
                                @error('trang_thai')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Biến chứng và xử trí --}}
                <div class="card vc-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-exclamation-triangle me-2" style="color: #ef4444;"></i>
                            Biến chứng và xử trí
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Biến chứng (nếu có)</label>
                            <textarea name="bien_chung" class="form-control" rows="3"
                                      placeholder="Mô tả các biến chứng xảy ra trong hoặc sau thủ thuật (nếu có)...">{{ old('bien_chung', $thuThuat->bien_chung) }}</textarea>
                            <small class="text-muted">Để trống nếu không có biến chứng</small>
                            @error('bien_chung')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Cách xử trí</label>
                            <textarea name="xu_tri" class="form-control" rows="3"
                                      placeholder="Mô tả cách xử trí biến chứng hoặc các biện pháp hỗ trợ sau thủ thuật...">{{ old('xu_tri', $thuThuat->xu_tri) }}</textarea>
                            @error('xu_tri')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Hướng dẫn sau thủ thuật --}}
                <div class="card vc-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user-nurse me-2" style="color: #8b5cf6;"></i>
                            Hướng dẫn và theo dõi
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Hướng dẫn chăm sóc sau thủ thuật</label>
                            <textarea name="huong_dan_sau_thu_thuat" class="form-control" rows="4"
                                      placeholder="VD: Nghỉ ngơi 24h, không tắm trong 2 ngày, uống thuốc kháng sinh theo đơn, tái khám sau 1 tuần...">{{ old('huong_dan_sau_thu_thuat', $thuThuat->huong_dan_sau_thu_thuat) }}</textarea>
                            @error('huong_dan_sau_thu_thuat')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Lịch tái khám</label>
                            <input type="date" name="ngay_tai_kham" class="form-control" 
                                   value="{{ old('ngay_tai_kham', $thuThuat->ngay_tai_kham ? \Carbon\Carbon::parse($thuThuat->ngay_tai_kham)->format('Y-m-d') : '') }}">
                            @error('ngay_tai_kham')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Hình ảnh/tài liệu</label>
                            <input type="file" name="hinh_anh[]" class="form-control" multiple accept="image/*,.pdf">
                            <small class="text-muted">Có thể chọn nhiều file. Định dạng: JPG, PNG, PDF</small>
                            @error('hinh_anh')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            
                            @if($thuThuat->hinh_anh && is_array($thuThuat->hinh_anh))
                            <div class="mt-2">
                                <small class="text-muted">File hiện có:</small>
                                <div class="d-flex flex-wrap gap-2 mt-1">
                                    @foreach($thuThuat->hinh_anh as $file)
                                    @if(Str::endsWith($file, ['.jpg', '.jpeg', '.png', '.gif']))
                                    <img src="{{ asset('storage/' . $file) }}" alt="Thủ thuật" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                    @else
                                    <a href="{{ asset('storage/' . $file) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-file-pdf me-1"></i>{{ basename($file) }}
                                    </a>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ghi chú bổ sung</label>
                            <textarea name="ghi_chu_bo_sung" class="form-control" rows="2"
                                      placeholder="Các ghi chú, lưu ý khác...">{{ old('ghi_chu_bo_sung', $thuThuat->ghi_chu_bo_sung) }}</textarea>
                            @error('ghi_chu_bo_sung')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="d-flex gap-2 justify-content-end mb-4">
                    <a href="{{ route('doctor.benhan.edit', $thuThuat->benh_an_id) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Hủy
                    </a>
                    <button type="submit" class="btn vc-btn-primary">
                        <i class="fas fa-save me-2"></i>Lưu kết quả
                    </button>
                </div>
            </form>
        </div>

        <div class="col-lg-4">
            {{-- Checklist an toàn --}}
            <div class="card vc-card">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-tasks me-2" style="color: #10b981;"></i>
                        Checklist quan trọng
                    </h6>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="check1">
                        <label class="form-check-label small" for="check1">
                            Đã kiểm tra thiết bị và dụng cụ
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="check2">
                        <label class="form-check-label small" for="check2">
                            Bệnh nhân ổn định sau thủ thuật
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="check3">
                        <label class="form-check-label small" for="check3">
                            Đã ghi nhận dấu hiệu sinh tồn
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="check4">
                        <label class="form-check-label small" for="check4">
                            Đã hướng dẫn bệnh nhân chăm sóc
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="check5">
                        <label class="form-check-label small" for="check5">
                            Đã lên lịch tái khám (nếu cần)
                        </label>
                    </div>
                </div>
            </div>

            {{-- Dấu hiệu cảnh báo --}}
            <div class="card vc-card mt-3">
                <div class="card-body">
                    <h6 class="card-title text-danger">
                        <i class="fas fa-bell me-2"></i>
                        Dấu hiệu cần báo ngay
                    </h6>
                    <ul class="mb-0 small">
                        <li>Chảy máu nhiều, không cầm</li>
                        <li>Đau bụng dữ dội, co thắt tử cung</li>
                        <li>Sốt cao > 38.5°C</li>
                        <li>Ra nước ối bất thường</li>
                        <li>Thai không động</li>
                        <li>Choáng váng, mệt lả</li>
                    </ul>
                </div>
            </div>

            {{-- Mẫu báo cáo --}}
            <div class="card vc-card mt-3">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-clipboard me-2" style="color: #6366f1;"></i>
                        Mẫu báo cáo
                    </h6>
                    <div class="small">
                        <p class="mb-1"><strong>Chọc ối:</strong></p>
                        <p class="text-muted small mb-2">
                            "Thực hiện chọc ối qua thành bụng dưới hướng dẫn siêu âm. Lấy được 20ml nước ối màu trong, không lẫn máu. Không có biến chứng."
                        </p>
                        
                        <p class="mb-1"><strong>Sinh thiết nhau:</strong></p>
                        <p class="text-muted small mb-0">
                            "Sinh thiết tua nhau qua cổ tử cung. Lấy được 15mg mô nhau. Thai nhi ổn định sau thủ thuật."
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
