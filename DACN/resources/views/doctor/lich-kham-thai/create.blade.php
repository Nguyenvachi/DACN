@extends('layouts.doctor')

@section('title', 'Thêm lịch khám thai')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: #1f2937;">
                <i class="fas fa-calendar-check me-2" style="color: #10b981;"></i>
                Thêm lịch khám thai
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('doctor.theo-doi-thai-ky.index') }}">Theo dõi thai kỳ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('doctor.theo-doi-thai-ky.show', $theoDoiThaiKy->id) }}">Hồ sơ #{{ $theoDoiThaiKy->id }}</a></li>
                    <li class="breadcrumb-item active">Thêm lịch khám</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('doctor.theo-doi-thai-ky.show', $theoDoiThaiKy->id) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <form action="{{ route('doctor.lich-kham-thai.store', $theoDoiThaiKy->id) }}" method="POST">
        @csrf
        
        <div class="row">
            <div class="col-lg-8">
                {{-- Thông tin bệnh nhân --}}
                <div class="card vc-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user-injured me-2" style="color: #3b82f6;"></i>
                            Thông tin bệnh nhân
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-0">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Họ tên:</strong> {{ $theoDoiThaiKy->user->name }}</p>
                                    <p class="mb-1"><strong>Email:</strong> {{ $theoDoiThaiKy->user->email }}</p>
                                    @if($theoDoiThaiKy->user->so_dien_thoai)
                                    <p class="mb-0"><strong>SĐT:</strong> {{ $theoDoiThaiKy->user->so_dien_thoai }}</p>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Ngày kinh cuối:</strong> {{ \Carbon\Carbon::parse($theoDoiThaiKy->ngay_kinh_cuoi)->format('d/m/Y') }}</p>
                                    <p class="mb-1"><strong>Ngày dự sinh:</strong> {{ \Carbon\Carbon::parse($theoDoiThaiKy->ngay_du_sinh)->format('d/m/Y') }}</p>
                                    <p class="mb-0"><strong>Tuổi thai hiện tại:</strong> <span class="badge bg-info">{{ $tuoiThai['tuan'] }} tuần {{ $tuoiThai['ngay'] }} ngày</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Thông tin khám --}}
                <div class="card vc-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-day me-2" style="color: #ec4899;"></i>
                            Thông tin khám
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Ngày khám <span class="text-danger">*</span></label>
                                <input type="date" name="ngay_kham" class="form-control" 
                                       value="{{ old('ngay_kham', now()->format('Y-m-d')) }}" required>
                                @error('ngay_kham')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tuần thai <span class="text-danger">*</span></label>
                                <input type="number" name="tuan_thai" class="form-control" 
                                       value="{{ old('tuan_thai', $tuoiThai['tuan']) }}" required min="0" max="42">
                                @error('tuan_thai')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Ngày thai</label>
                                <input type="number" name="ngay_thai" class="form-control" 
                                       value="{{ old('ngay_thai', $tuoiThai['ngay']) }}" min="0" max="6">
                                @error('ngay_thai')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Chỉ số sinh tồn --}}
                <div class="card vc-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-heartbeat me-2" style="color: #ef4444;"></i>
                            Chỉ số sinh tồn
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Cân nặng (kg)</label>
                                <input type="number" step="0.1" name="can_nang" class="form-control" 
                                       value="{{ old('can_nang') }}" placeholder="VD: 58.5">
                                @error('can_nang')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Huyết áp tâm thu (mmHg)</label>
                                <input type="number" step="1" name="huyet_ap_tam_thu" class="form-control" 
                                       value="{{ old('huyet_ap_tam_thu') }}" placeholder="VD: 120">
                                @error('huyet_ap_tam_thu')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Huyết áp tâm trương (mmHg)</label>
                                <input type="number" step="1" name="huyet_ap_tam_truong" class="form-control" 
                                       value="{{ old('huyet_ap_tam_truong') }}" placeholder="VD: 80">
                                @error('huyet_ap_tam_truong')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Nhiệt độ (°C)</label>
                                <input type="number" step="0.1" name="nhiet_do" class="form-control" 
                                       value="{{ old('nhiet_do') }}" placeholder="VD: 36.5">
                                @error('nhiet_do')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Nhịp tim mẹ (bpm)</label>
                                <input type="number" name="nhip_tim_me" class="form-control" 
                                       value="{{ old('nhip_tim_me') }}" placeholder="VD: 80">
                                @error('nhip_tim_me')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Chiều cao tử cung (cm)</label>
                                <input type="number" step="0.1" name="chieu_cao_tu_cung" class="form-control" 
                                       value="{{ old('chieu_cao_tu_cung') }}" placeholder="VD: 28">
                                @error('chieu_cao_tu_cung')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Vòng bụng (cm)</label>
                                <input type="number" step="0.1" name="vong_bung" class="form-control" 
                                       value="{{ old('vong_bung') }}" placeholder="VD: 95">
                                @error('vong_bung')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Thông tin thai nhi --}}
                <div class="card vc-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-baby me-2" style="color: #ec4899;"></i>
                            Thông tin thai nhi
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nhịp tim thai (bpm)</label>
                                <input type="number" name="nhip_tim_thai" class="form-control" 
                                       value="{{ old('nhip_tim_thai') }}" placeholder="VD: 145">
                                <small class="text-muted">Bình thường: 120-160 bpm</small>
                                @error('nhip_tim_thai')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Vị trí thai</label>
                                <select name="vi_tri_thai" class="form-select">
                                    <option value="">-- Chọn --</option>
                                    <option value="Ngôi đầu" {{ old('vi_tri_thai') === 'Ngôi đầu' ? 'selected' : '' }}>Ngôi đầu</option>
                                    <option value="Ngôi mông" {{ old('vi_tri_thai') === 'Ngôi mông' ? 'selected' : '' }}>Ngôi mông</option>
                                    <option value="Ngôi ngang" {{ old('vi_tri_thai') === 'Ngôi ngang' ? 'selected' : '' }}>Ngôi ngang</option>
                                    <option value="Chưa xác định" {{ old('vi_tri_thai') === 'Chưa xác định' ? 'selected' : '' }}>Chưa xác định</option>
                                </select>
                                @error('vi_tri_thai')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Khám lâm sàng --}}
                <div class="card vc-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-stethoscope me-2" style="color: #6366f1;"></i>
                            Khám lâm sàng
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Triệu chứng</label>
                            <textarea name="trieu_chung" class="form-control" rows="3"
                                      placeholder="Ghi nhận các triệu chứng bất thường: đau bụng, chảy máu, phù chân...">{{ old('trieu_chung') }}</textarea>
                            @error('trieu_chung')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kết quả khám lâm sàng</label>
                            <textarea name="kham_lam_sang" class="form-control" rows="3"
                                      placeholder="Mô tả chi tiết kết quả khám: tim phổi, bụng, tử cung...">{{ old('kham_lam_sang') }}</textarea>
                            @error('kham_lam_sang')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Chỉ định</label>
                            <textarea name="chi_dinh" class="form-control" rows="2"
                                      placeholder="Các chỉ định xét nghiệm, siêu âm cần làm...">{{ old('chi_dinh') }}</textarea>
                            @error('chi_dinh')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Đánh giá</label>
                            <textarea name="danh_gia" class="form-control" rows="3"
                                      placeholder="Đánh giá tình trạng thai kỳ: bình thường, có nguy cơ...">{{ old('danh_gia') }}</textarea>
                            @error('danh_gia')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tư vấn</label>
                            <textarea name="tu_van" class="form-control" rows="3"
                                      placeholder="Tư vấn dinh dưỡng, chế độ sinh hoạt, lưu ý...">{{ old('tu_van') }}</textarea>
                            @error('tu_van')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Hẹn khám lại</label>
                                <input type="date" name="hen_kham_lai" class="form-control" 
                                       value="{{ old('hen_kham_lai') }}">
                                @error('hen_kham_lai')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ghi chú</label>
                                <textarea name="ghi_chu" class="form-control" rows="1"
                                          placeholder="Ghi chú thêm...">{{ old('ghi_chu') }}</textarea>
                                @error('ghi_chu')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mb-4">
                    <a href="{{ route('doctor.theo-doi-thai-ky.show', $theoDoiThaiKy->id) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Hủy
                    </a>
                    <button type="submit" class="btn vc-btn-primary">
                        <i class="fas fa-save me-2"></i>Lưu lịch khám
                    </button>
                </div>
            </div>

            <div class="col-lg-4">
                {{-- Hướng dẫn --}}
                <div class="card vc-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2" style="color: #f59e0b;"></i>
                            Hướng dẫn
                        </h5>
                    </div>
                    <div class="card-body">
                        <h6 class="fw-bold mb-2">Chỉ số cần lưu ý:</h6>
                        <ul class="mb-3">
                            <li><small>Nhịp tim thai: 120-160 bpm</small></li>
                            <li><small>Huyết áp: Dưới 140/90 mmHg</small></li>
                            <li><small>Tăng cân: 10-15 kg cả thai kỳ</small></li>
                        </ul>

                        <h6 class="fw-bold mb-2">Lịch khám khuyến cáo:</h6>
                        <ul class="mb-0">
                            <li><small>Tuần 6-8: Khám lần đầu</small></li>
                            <li><small>Tuần 11-13: Sàng lọc Down</small></li>
                            <li><small>Tuần 18-22: Siêu âm hình thái</small></li>
                            <li><small>Tuần 24-28: Sàng lọc đái tháo đường</small></li>
                            <li><small>Tuần 32-36: Đánh giá thai nhi</small></li>
                            <li><small>Tuần 37-40: Chuẩn bị sinh</small></li>
                        </ul>
                    </div>
                </div>

                {{-- Lịch sử khám gần nhất --}}
                @if($theoDoiThaiKy->lichKhamThai->count() > 0)
                <div class="card vc-card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-history me-2" style="color: #10b981;"></i>
                            Lần khám gần nhất
                        </h5>
                    </div>
                    <div class="card-body">
                        @php
                            $lastExam = $theoDoiThaiKy->lichKhamThai->sortByDesc('ngay_kham')->first();
                        @endphp
                        <p class="mb-2"><strong>Ngày:</strong> {{ \Carbon\Carbon::parse($lastExam->ngay_kham)->format('d/m/Y') }}</p>
                        <p class="mb-2"><strong>Tuần thai:</strong> {{ $lastExam->tuan_thai }} tuần {{ $lastExam->ngay_thai }} ngày</p>
                        @if($lastExam->can_nang)
                        <p class="mb-2"><strong>Cân nặng:</strong> {{ $lastExam->can_nang }} kg</p>
                        @endif
                        @if($lastExam->nhip_tim_thai)
                        <p class="mb-2"><strong>Tim thai:</strong> {{ $lastExam->nhip_tim_thai }} bpm</p>
                        @endif
                        @if($lastExam->hen_kham_lai)
                        <div class="alert alert-info mb-0 mt-2 py-2">
                            <small><strong>Đã hẹn:</strong> {{ \Carbon\Carbon::parse($lastExam->hen_kham_lai)->format('d/m/Y') }}</small>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </form>
</div>
@endsection
