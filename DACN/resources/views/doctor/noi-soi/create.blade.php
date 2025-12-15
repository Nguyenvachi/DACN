@extends('layouts.doctor')

@section('title', 'Chỉ định nội soi')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: #1f2937;">
                <i class="bi bi-camera-video me-2 text-info"></i>
                Chỉ định nội soi
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('doctor.benhan.show', $benhAn->id) }}">Bệnh án #{{ $benhAn->id }}</a></li>
                    <li class="breadcrumb-item active">Chỉ định nội soi</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('doctor.benhan.show', $benhAn->id) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            {{-- Thông tin bệnh nhân --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="bi bi-person-badge me-2 text-primary"></i>
                        Thông tin bệnh nhân
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Họ tên:</strong> {{ $benhAn->benhNhan->name }}</p>
                            <p class="mb-2"><strong>Mã BN:</strong> #{{ $benhAn->benhNhan->id }}</p>
                            <p class="mb-2"><strong>Ngày khám:</strong> {{ \Carbon\Carbon::parse($benhAn->ngay_kham)->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Chẩn đoán:</strong> {{ $benhAn->chuan_doan ?? 'Chưa có' }}</p>
                            <p class="mb-2"><strong>Triệu chứng:</strong> {{ $benhAn->trieu_chung ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form chỉ định --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-clipboard-plus me-2"></i>
                        Chỉ định nội soi
                    </h5>
                </div>
                <div class="card-body">
                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <form action="{{ route('doctor.noi-soi.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="benh_an_id" value="{{ $benhAn->id }}">

                        <div class="mb-3">
                            <label class="form-label">Dịch vụ nội soi</label>
                            <select name="dich_vu_id" class="form-select">
                                <option value="">-- Không chọn dịch vụ cụ thể --</option>
                                @foreach($dichVuNoiSoi as $dichVu)
                                <option value="{{ $dichVu->id }}" {{ old('dich_vu_id') == $dichVu->id ? 'selected' : '' }}>
                                    {{ $dichVu->ten_dich_vu }}
                                </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Chọn dịch vụ nếu có trong danh sách</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Loại nội soi <span class="text-danger">*</span></label>
                            <select name="loai_noi_soi" class="form-select" required>
                                <option value="">-- Chọn loại nội soi --</option>
                                <option value="Nội soi dạ dày" {{ old('loai_noi_soi') == 'Nội soi dạ dày' ? 'selected' : '' }}>Nội soi dạ dày</option>
                                <option value="Nội soi đại trà" {{ old('loai_noi_soi') == 'Nội soi đại trà' ? 'selected' : '' }}>Nội soi đại trà</option>
                                <option value="Nội soi phế quản" {{ old('loai_noi_soi') == 'Nội soi phế quản' ? 'selected' : '' }}>Nội soi phế quản</option>
                                <option value="Nội soi tai mũi họng" {{ old('loai_noi_soi') == 'Nội soi tai mũi họng' ? 'selected' : '' }}>Nội soi tai mũi họng</option>
                                <option value="Nội soi khớp" {{ old('loai_noi_soi') == 'Nội soi khớp' ? 'selected' : '' }}>Nội soi khớp</option>
                                <option value="Khác">Khác (ghi rõ trong ghi chú)</option>
                            </select>
                            @error('loai_noi_soi')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ngày chỉ định <span class="text-danger">*</span></label>
                            <input type="date" name="ngay_chi_dinh" class="form-control" 
                                   value="{{ old('ngay_chi_dinh', date('Y-m-d')) }}" required>
                            @error('ngay_chi_dinh')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Lý do chỉ định</label>
                            <textarea name="chi_dinh" class="form-control" rows="3"
                                      placeholder="VD: Đau bụng, khó nuốt, chẩn đoán viêm loét...">{{ old('chi_dinh') }}</textarea>
                            @error('chi_dinh')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Hướng dẫn chuẩn bị</label>
                            <textarea name="chuan_bi" class="form-control" rows="3"
                                      placeholder="VD: Nhịn ăn 8 tiếng trước khi làm nội soi...">{{ old('chuan_bi') }}</textarea>
                            <small class="text-muted">Hướng dẫn bệnh nhân chuẩn bị trước khi nội soi</small>
                            @error('chuan_bi')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-info">
                                <i class="bi bi-check-circle me-2"></i>Chỉ định
                            </button>
                            <a href="{{ route('doctor.benhan.show', $benhAn->id) }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-2"></i>Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-info">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Lưu ý</h6>
                </div>
                <div class="card-body">
                    <ul class="mb-0 small">
                        <li class="mb-2">Kiểm tra chống chỉ định trước khi chỉ định nội soi</li>
                        <li class="mb-2">Giải thích rõ quy trình cho bệnh nhân</li>
                        <li class="mb-2">Hướng dẫn chuẩn bị cụ thể (nhịn ăn, dùng thuốc...)</li>
                        <li class="mb-2">Yêu cầu bệnh nhân ký cam kết đồng ý</li>
                        <li>Chuẩn bị thuốc gây mê/giảm đau nếu cần</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
