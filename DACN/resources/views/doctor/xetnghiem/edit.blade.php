@extends('layouts.doctor')

@section('title', 'Nhập kết quả xét nghiệm #XN' . $xetNghiem->id)

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: #1f2937;">
                <i class="fas fa-{{ $xetNghiem->trang_thai === 'completed' ? 'eye' : 'edit' }} me-2" style="color: #8b5cf6;"></i>
                {{ $xetNghiem->trang_thai === 'completed' ? 'Xem kết quả' : 'Nhập kết quả' }} xét nghiệm #XN{{ $xetNghiem->id }}
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('doctor.xet-nghiem.index') }}">Xét nghiệm</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('doctor.xet-nghiem.show', $xetNghiem) }}">Chi tiết #XN{{ $xetNghiem->id }}</a></li>
                    <li class="breadcrumb-item active">Nhập kết quả</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('doctor.xet-nghiem.show', $xetNghiem) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card vc-card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-file-medical-alt me-2" style="color: #10b981;"></i>
                        Thông tin kết quả
                        @if($xetNghiem->trang_thai === 'completed')
                            <span class="badge bg-success ms-2">Chỉ xem</span>
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <strong>Vui lòng kiểm tra lại:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('doctor.xet-nghiem.update', $xetNghiem) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Chỉ số xét nghiệm --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-chart-line me-2 text-primary"></i>Các chỉ số
                            </label>
                            @if($xetNghiem->trang_thai === 'completed')
                                <div class="alert alert-info mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Kết quả đã được hoàn tất. Không thể chỉnh sửa.
                                </div>
                            @endif
                            <div id="chiSoContainer">
                                @php
                                    $chiSoCu = old('chi_so', $xetNghiem->chi_so ?? []);
                                    if (empty($chiSoCu)) {
                                        $chiSoCu = [['ten' => '', 'ket_qua' => '', 'don_vi' => '', 'min' => '', 'max' => '']];
                                    }
                                @endphp
                                @foreach($chiSoCu as $index => $chiSo)
                                <div class="chi-so-item card mb-3" data-index="{{ $index }}">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="mb-0">Chỉ số #{{ $index + 1 }}</h6>
                                            @if($xetNghiem->trang_thai !== 'completed')
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-chi-so" onclick="removeChiSo(this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @endif
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label">Tên chỉ số</label>
                                                <input type="text" name="chi_so[{{ $index }}][ten]" class="form-control" 
                                                       placeholder="VD: Hemoglobin" 
                                                       value="{{ $chiSo['ten'] ?? '' }}" 
                                                       {{ $xetNghiem->trang_thai === 'completed' ? 'readonly' : 'required' }}>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Kết quả</label>
                                                <input type="text" name="chi_so[{{ $index }}][ket_qua]" class="form-control" 
                                                       placeholder="VD: 14.5" 
                                                       value="{{ $chiSo['ket_qua'] ?? '' }}" 
                                                       {{ $xetNghiem->trang_thai === 'completed' ? 'readonly' : 'required' }}>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Đơn vị</label>
                                                <input type="text" name="chi_so[{{ $index }}][don_vi]" class="form-control" 
                                                       placeholder="g/dL" 
                                                       value="{{ $chiSo['don_vi'] ?? '' }}"
                                                       {{ $xetNghiem->trang_thai === 'completed' ? 'readonly' : '' }}>
                                            </div>
                                            <div class="col-md-1">
                                                <label class="form-label">Min</label>
                                                <input type="number" name="chi_so[{{ $index }}][min]" class="form-control" 
                                                       placeholder="12" 
                                                       step="0.01"
                                                       value="{{ $chiSo['min'] ?? '' }}"
                                                       {{ $xetNghiem->trang_thai === 'completed' ? 'readonly' : '' }}>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Max</label>
                                                <input type="number" name="chi_so[{{ $index }}][max]" class="form-control" 
                                                       placeholder="16" 
                                                       step="0.01"
                                                       value="{{ $chiSo['max'] ?? '' }}"
                                                       {{ $xetNghiem->trang_thai === 'completed' ? 'readonly' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @if($xetNghiem->trang_thai !== 'completed')
                            <button type="button" class="btn btn-outline-primary" onclick="addChiSo()">
                                <i class="fas fa-plus me-2"></i>Thêm chễ số
                            </button>
                            @endif
                        </div>

                        {{-- Nhận xét --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-comment-medical me-2 text-info"></i>Nhận xét
                            </label>
                            <textarea name="nhan_xet" class="form-control" rows="4" 
                                      placeholder="Nhận xét chi tiết về kết quả xét nghiệm..."
                                      {{ $xetNghiem->trang_thai === 'completed' ? 'readonly' : '' }}>{{ old('nhan_xet', $xetNghiem->nhan_xet) }}</textarea>
                        </div>

                        {{-- Kết luận --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-clipboard-check me-2 text-success"></i>Kết luận
                            </label>
                            <textarea name="ket_luan" class="form-control" rows="3" 
                                      placeholder="Kết luận chung về xét nghiệm..."
                                      {{ $xetNghiem->trang_thai === 'completed' ? 'readonly' : '' }}>{{ old('ket_luan', $xetNghiem->ket_luan) }}</textarea>
                        </div>

                        {{-- File kết quả --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-file-upload me-2 text-warning"></i>File kết quả (PDF/Images)
                            </label>
                            @if($xetNghiem->trang_thai !== 'completed')
                            <input type="file" name="file_ket_qua[]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png">
                            <small class="text-muted">Có thể chọn nhiều file. Định dạng: PDF, JPG, PNG</small>
                            @endif
                            
                            @if($xetNghiem->file_ket_qua && count($xetNghiem->file_ket_qua) > 0)
                            <div class="mt-3">
                                <strong>File hiện có:</strong>
                                <ul class="list-unstyled mt-2">
                                    @foreach($xetNghiem->file_ket_qua as $file)
                                    <li class="mb-1">
                                        <i class="fas fa-file text-primary me-2"></i>
                                        {{ basename($file) }}
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        </div>

                        {{-- Ngày trả kết quả --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-calendar-check me-2 text-danger"></i>Ngày trả kết quả
                            </label>
                            <input type="date" name="ngay_tra_ket_qua" class="form-control" 
                                   value="{{ old('ngay_tra_ket_qua', $xetNghiem->ngay_tra_ket_qua ? $xetNghiem->ngay_tra_ket_qua->format('Y-m-d') : now()->format('Y-m-d')) }}" 
                                   {{ $xetNghiem->trang_thai === 'completed' ? 'readonly' : 'required' }}>
                        </div>

                        {{-- Hidden status field - auto set to completed --}}
                        <input type="hidden" name="trang_thai" value="Có kết quả">

                        {{-- Buttons --}}
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('doctor.xet-nghiem.show', $xetNghiem) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>{{ $xetNghiem->trang_thai === 'completed' ? 'Đóng' : 'Hủy' }}
                            </a>
                            @if($xetNghiem->trang_thai !== 'completed')
                            <button type="submit" class="btn vc-btn-primary">
                                <i class="fas fa-save me-2"></i>Lưu kết quả
                            </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Sidebar - Thông tin bệnh nhân --}}
        <div class="col-lg-4">
            <div class="card vc-card mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-user-injured me-2" style="color: #3b82f6;"></i>
                        Thông tin bệnh nhân
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Họ tên:</strong> {{ $xetNghiem->benhAn->user->name ?? 'N/A' }}</p>
                    <p class="mb-2"><strong>Mã BN:</strong> #{{ $xetNghiem->benhAn->user_id }}</p>
                    <p class="mb-2"><strong>Loại XN:</strong> {{ $xetNghiem->loai_xet_nghiem ?? $xetNghiem->ten_xet_nghiem }}</p>
                    <p class="mb-2"><strong>Ngày chỉ định:</strong> {{ $xetNghiem->ngay_chi_dinh->format('d/m/Y') }}</p>
                </div>
            </div>

            <div class="card vc-card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-lightbulb me-2" style="color: #f59e0b;"></i>
                        Hướng dẫn
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Nhập đầy đủ các chỉ số xét nghiệm</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Ghi rõ đơn vị và giá trị tham chiếu</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Upload file kết quả nếu có</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Nhận xét và kết luận cụ thể</li>
                        <li class="mb-0"><i class="fas fa-check text-success me-2"></i>Cập nhật trạng thái phù hợp</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let chiSoIndex = {{ count($chiSoCu) }};

function addChiSo() {
    const container = document.getElementById('chiSoContainer');
    const newItem = `
        <div class="chi-so-item card mb-3" data-index="${chiSoIndex}">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Chỉ số #${chiSoIndex + 1}</h6>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-chi-so" onclick="removeChiSo(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Tên chỉ số</label>
                        <input type="text" name="chi_so[${chiSoIndex}][ten]" class="form-control" 
                               placeholder="VD: Hemoglobin" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Kết quả</label>
                        <input type="text" name="chi_so[${chiSoIndex}][ket_qua]" class="form-control" 
                               placeholder="VD: 14.5" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Đơn vị</label>
                        <input type="text" name="chi_so[${chiSoIndex}][don_vi]" class="form-control" 
                               placeholder="g/dL">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">Min</label>
                        <input type="number" name="chi_so[${chiSoIndex}][min]" class="form-control" 
                               placeholder="12" step="0.01">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Max</label>
                        <input type="number" name="chi_so[${chiSoIndex}][max]" class="form-control" 
                               placeholder="16" step="0.01">
                    </div>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', newItem);
    chiSoIndex++;
}

function removeChiSo(button) {
    const item = button.closest('.chi-so-item');
    if (document.querySelectorAll('.chi-so-item').length > 1) {
        item.remove();
        updateChiSoNumbers();
    } else {
        alert('Phải có ít nhất 1 chỉ số!');
    }
}

function updateChiSoNumbers() {
    document.querySelectorAll('.chi-so-item').forEach((item, index) => {
        item.querySelector('h6').textContent = `Chỉ số #${index + 1}`;
    });
}
</script>
@endpush
@endsection
