@extends('layouts.doctor')

@section('title', 'Tạo hồ sơ theo dõi thai kỳ')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: #1f2937;">
                <i class="fas fa-heart me-2" style="color: #ec4899;"></i>
                Tạo hồ sơ theo dõi thai kỳ
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('doctor.theo-doi-thai-ky.index') }}">Theo dõi thai kỳ</a></li>
                    <li class="breadcrumb-item active">Tạo mới</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('doctor.theo-doi-thai-ky.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <form action="{{ route('doctor.theo-doi-thai-ky.store') }}" method="POST">
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
                        @if($user)
                        {{-- Nếu đã có bệnh nhân được chọn --}}
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        @if(isset($benhAn))
                        <input type="hidden" name="benh_an_id" value="{{ $benhAn->id }}">
                        @endif
                        <div class="alert alert-info">
                            <strong>Bệnh nhân:</strong> {{ $user->name }}<br>
                            <strong>Email:</strong> {{ $user->email }}<br>
                            @if($user->so_dien_thoai)
                            <strong>SĐT:</strong> {{ $user->so_dien_thoai }}<br>
                            @endif
                            @if(isset($benhAn))
                            <strong>Bệnh án:</strong> #{{ str_pad($benhAn->id, 4, '0', STR_PAD_LEFT) }}<br>
                            @endif
                        </div>
                        @else
                        {{-- Tìm kiếm bệnh nhân --}}
                        <div class="mb-3">
                            <label class="form-label">Chọn bệnh nhân <span class="text-danger">*</span></label>
                            <select name="user_id" class="form-select" required id="patientSelect">
                                <option value="">-- Tìm kiếm bệnh nhân --</option>
                                @foreach(\App\Models\User::where('role', 'patient')->orderBy('name')->get() as $patient)
                                <option value="{{ $patient->id }}" {{ old('user_id') == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->name }} - {{ $patient->email }} {{ $patient->so_dien_thoai ? '(' . $patient->so_dien_thoai . ')' : '' }}
                                </option>
                                @endforeach
                            </select>
                            @error('user_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Thông tin thai kỳ --}}
                <div class="card vc-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-baby me-2" style="color: #ec4899;"></i>
                            Thông tin thai kỳ
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ngày kinh cuối (LMP) <span class="text-danger">*</span></label>
                                <input type="date" name="ngay_kinh_cuoi" class="form-control" 
                                       value="{{ old('ngay_kinh_cuoi') }}" required>
                                <small class="text-muted">Last Menstrual Period - ngày đầu kỳ kinh cuối</small>
                                @error('ngay_kinh_cuoi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Loại thai <span class="text-danger">*</span></label>
                                <select name="loai_thai" class="form-select" required>
                                    <option value="Đơn thai" {{ old('loai_thai') === 'Đơn thai' ? 'selected' : '' }}>Đơn thai</option>
                                    <option value="Song thai" {{ old('loai_thai') === 'Song thai' ? 'selected' : '' }}>Song thai</option>
                                    <option value="Đa thai" {{ old('loai_thai') === 'Đa thai' ? 'selected' : '' }}>Đa thai</option>
                                </select>
                                @error('loai_thai')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Số lần mang thai (Para) <span class="text-danger">*</span></label>
                                <input type="number" name="so_lan_mang_thai" class="form-control" 
                                       value="{{ old('so_lan_mang_thai', 1) }}" min="1" required>
                                @error('so_lan_mang_thai')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Số lần sinh (Gravida) <span class="text-danger">*</span></label>
                                <input type="number" name="so_lan_sinh" class="form-control" 
                                       value="{{ old('so_lan_sinh', 0) }}" min="0" required>
                                @error('so_lan_sinh')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Số con còn sống <span class="text-danger">*</span></label>
                                <input type="number" name="so_con_song" class="form-control" 
                                       value="{{ old('so_con_song', 0) }}" min="0" required>
                                @error('so_con_song')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Chỉ số ban đầu --}}
                <div class="card vc-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-weight me-2" style="color: #10b981;"></i>
                            Chỉ số ban đầu
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Cân nặng trước mang thai (kg)</label>
                                <input type="number" name="can_nang_truoc_mang_thai" class="form-control" 
                                       value="{{ old('can_nang_truoc_mang_thai') }}" step="0.1" min="0">
                                @error('can_nang_truoc_mang_thai')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Chiều cao (cm)</label>
                                <input type="number" name="chieu_cao" class="form-control" 
                                       value="{{ old('chieu_cao') }}" step="0.1" min="0">
                                @error('chieu_cao')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Nhóm máu</label>
                                <select name="nhom_mau" class="form-select">
                                    <option value="">-- Chọn nhóm máu --</option>
                                    <option value="A" {{ old('nhom_mau') === 'A' ? 'selected' : '' }}>A</option>
                                    <option value="B" {{ old('nhom_mau') === 'B' ? 'selected' : '' }}>B</option>
                                    <option value="AB" {{ old('nhom_mau') === 'AB' ? 'selected' : '' }}>AB</option>
                                    <option value="O" {{ old('nhom_mau') === 'O' ? 'selected' : '' }}>O</option>
                                </select>
                                @error('nhom_mau')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Rh</label>
                                <select name="rh" class="form-select">
                                    <option value="">-- Chọn Rh --</option>
                                    <option value="+" {{ old('rh') === '+' ? 'selected' : '' }}>Dương (+)</option>
                                    <option value="-" {{ old('rh') === '-' ? 'selected' : '' }}>Âm (-)</option>
                                </select>
                                @error('rh')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tiền sử --}}
                <div class="card vc-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-history me-2" style="color: #f59e0b;"></i>
                            Tiền sử bệnh
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Tiền sử sản khoa</label>
                            <textarea name="tien_su_san_khoa" class="form-control" rows="3"
                                      placeholder="VD: Sẩy thai 1 lần (năm 2020), sinh mổ 1 lần (2018)...">{{ old('tien_su_san_khoa') }}</textarea>
                            <small class="text-muted">Lịch sử mang thai, sinh nở, sẩy thai, nạo thai...</small>
                            @error('tien_su_san_khoa')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tiền sử bệnh lý</label>
                            <textarea name="tien_su_benh_ly" class="form-control" rows="3"
                                      placeholder="VD: Đái tháo đường, cao huyết áp, bệnh tim mạch...">{{ old('tien_su_benh_ly') }}</textarea>
                            <small class="text-muted">Các bệnh mãn tính, bệnh nền</small>
                            @error('tien_su_benh_ly')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tiền sử dị ứng</label>
                            <textarea name="di_ung" class="form-control" rows="2"
                                      placeholder="VD: Dị ứng penicillin, hải sản...">{{ old('di_ung') }}</textarea>
                            @error('di_ung')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="ghi_chu" class="form-control" rows="2"
                                      placeholder="Ghi chú thêm...">{{ old('ghi_chu') }}</textarea>
                            @error('ghi_chu')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Thông tin dịch vụ --}}
                <div class="card vc-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-dollar-sign me-2" style="color: #f59e0b;"></i>
                            Thông tin dịch vụ
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Gói dịch vụ</label>
                            <select name="goi_dich_vu" class="form-select" id="goiDichVu">
                                <option value="Gói theo dõi thai kỳ cơ bản" data-price="3000000">Gói cơ bản - 3,000,000đ</option>
                                <option value="Gói theo dõi thai kỳ tiêu chuẩn" data-price="5000000">Gói tiêu chuẩn - 5,000,000đ</option>
                                <option value="Gói theo dõi thai kỳ cao cấp" data-price="8000000">Gói cao cấp - 8,000,000đ</option>
                                <option value="Gói theo dõi thai kỳ VIP" data-price="12000000">Gói VIP - 12,000,000đ</option>
                            </select>
                            <small class="text-muted">Chọn gói dịch vụ theo dõi thai kỳ phù hợp</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Giá tiền (VNĐ)</label>
                            <input type="number" name="gia_tien" class="form-control" 
                                   value="{{ old('gia_tien', 3000000) }}" min="0" step="1000" id="giaTien">
                            @error('gia_tien')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info mb-0">
                            <small>
                                <i class="fas fa-info-circle me-1"></i>
                                Gói dịch vụ bao gồm theo dõi định kỳ, tư vấn sức khỏe, lịch tiêm chủng và các dịch vụ khác theo từng gói.
                            </small>
                        </div>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="d-flex gap-2 justify-content-end mb-4">
                    <a href="{{ route('doctor.theo-doi-thai-ky.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Hủy
                    </a>
                    <button type="submit" class="btn vc-btn-primary">
                        <i class="fas fa-save me-2"></i>Tạo hồ sơ
                    </button>
                </div>
            </div>

            <div class="col-lg-4">
                {{-- Hướng dẫn --}}
                <div class="card vc-card">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="fas fa-info-circle me-2" style="color: #10b981;"></i>
                            Hướng dẫn
                        </h6>
                        <ul class="mb-0 small">
                            <li><strong>Ngày kinh cuối (LMP):</strong> Ngày đầu tiên của kỳ kinh cuối cùng</li>
                            <li><strong>Ngày dự sinh (EDD):</strong> Sẽ được tự động tính = LMP + 280 ngày</li>
                            <li><strong>Para/Gravida:</strong> Số lần mang thai và sinh con</li>
                            <li><strong>BMI:</strong> Sẽ được tự động tính từ cân nặng và chiều cao</li>
                            <li>Hệ thống sẽ tự động tạo lịch tiêm chủng khuyến cáo</li>
                        </ul>
                    </div>
                </div>

                {{-- Lịch khám khuyến cáo --}}
                <div class="card vc-card mt-3">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="fas fa-calendar-check me-2" style="color: #6366f1;"></i>
                            Lịch khám khuyến cáo
                        </h6>
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Tuần thai</th>
                                    <th>Mục đích</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                <tr><td>6-8</td><td>Khám lần đầu</td></tr>
                                <tr><td>11-13</td><td>Sàng lọc Down</td></tr>
                                <tr><td>18-22</td><td>Siêu âm hình thái</td></tr>
                                <tr><td>24-28</td><td>Sàng lọc đái tháo đường</td></tr>
                                <tr><td>32-36</td><td>Đánh giá sức khỏe mẹ-con</td></tr>
                                <tr><td>37-40</td><td>Theo dõi trước sinh</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Vaccine khuyến cáo --}}
                <div class="card vc-card mt-3">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="fas fa-syringe me-2" style="color: #ef4444;"></i>
                            Vaccine khuyến cáo
                        </h6>
                        <ul class="mb-0 small">
                            <li><strong>Tdap:</strong> Tuần 27-36 (Uốn ván, Bạch hầu, Ho gà)</li>
                            <li><strong>Cúm:</strong> Bất kỳ thời điểm nào</li>
                            <li><strong>Viêm gan B:</strong> Nếu chưa tiêm đủ</li>
                            <li><strong>COVID-19:</strong> Theo khuyến cáo Y tế</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // Tự động cập nhật giá khi chọn gói dịch vụ
    document.getElementById('goiDichVu').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const price = selectedOption.getAttribute('data-price');
        document.getElementById('giaTien').value = price;
    });
</script>
@endsection
