@extends('layouts.doctor')

@section('title', 'Chỉ định X-quang')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">
                <i class="bi bi-file-medical me-2 text-warning"></i>
                Chỉ định X-quang
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('doctor.benhan.show', $benhAn->id) }}">Bệnh án #{{ $benhAn->id }}</a></li>
                    <li class="breadcrumb-item active">Chỉ định X-quang</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('doctor.benhan.show', $benhAn->id) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
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

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="bi bi-clipboard-plus me-2"></i>
                        Chỉ định X-quang
                    </h5>
                </div>
                <div class="card-body">
                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <form action="{{ route('doctor.x-quang.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="benh_an_id" value="{{ $benhAn->id }}">

                        <div class="mb-3">
                            <label class="form-label">Dịch vụ X-quang</label>
                            <select name="dich_vu_id" class="form-select">
                                <option value="">-- Không chọn dịch vụ cụ thể --</option>
                                @foreach($dichVuXQuang as $dichVu)
                                <option value="{{ $dichVu->id }}" {{ old('dich_vu_id') == $dichVu->id ? 'selected' : '' }}>
                                    {{ $dichVu->ten_dich_vu }}
                                </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Chọn dịch vụ nếu có trong danh sách</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Loại X-quang <span class="text-danger">*</span></label>
                            <select name="loai_x_quang" class="form-select" required>
                                <option value="">-- Chọn loại X-quang --</option>
                                <option value="X-quang ngực" {{ old('loai_x_quang') == 'X-quang ngực' ? 'selected' : '' }}>X-quang ngực</option>
                                <option value="X-quang bụng" {{ old('loai_x_quang') == 'X-quang bụng' ? 'selected' : '' }}>X-quang bụng</option>
                                <option value="X-quang xương" {{ old('loai_x_quang') == 'X-quang xương' ? 'selected' : '' }}>X-quang xương</option>
                                <option value="X-quang sọ não" {{ old('loai_x_quang') == 'X-quang sọ não' ? 'selected' : '' }}>X-quang sọ não</option>
                                <option value="X-quang cột sống" {{ old('loai_x_quang') == 'X-quang cột sống' ? 'selected' : '' }}>X-quang cột sống</option>
                                <option value="X-quang khớp" {{ old('loai_x_quang') == 'X-quang khớp' ? 'selected' : '' }}>X-quang khớp</option>
                                <option value="X-quang răng hàm mặt" {{ old('loai_x_quang') == 'X-quang răng hàm mặt' ? 'selected' : '' }}>X-quang răng hàm mặt</option>
                                <option value="Khác">Khác (ghi rõ trong ghi chú)</option>
                            </select>
                            @error('loai_x_quang')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Vị trí chụp <span class="text-danger">*</span></label>
                            <input type="text" name="vi_tri" class="form-control" 
                                   value="{{ old('vi_tri') }}" 
                                   placeholder="VD: Ngực thẳng, Cột sống cổ 2 tư thế..." required>
                            @error('vi_tri')
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
                                      placeholder="VD: Đau ngực, ho kéo dài, nghi ngờ viêm phổi...">{{ old('chi_dinh') }}</textarea>
                            @error('chi_dinh')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning">
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
            <div class="card shadow-sm border-warning">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Lưu ý</h6>
                </div>
                <div class="card-body">
                    <ul class="mb-0 small">
                        <li class="mb-2">Kiểm tra chống chỉ định (thai phụ, trẻ em...)</li>
                        <li class="mb-2">Ghi rõ vị trí và tư thế chụp</li>
                        <li class="mb-2">Yêu cầu bệnh nhân tháo đồ kim loại</li>
                        <li class="mb-2">Hướng dẫn giữ yên khi chụp</li>
                        <li>Sử dụng chì bảo vệ nếu cần thiết</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
