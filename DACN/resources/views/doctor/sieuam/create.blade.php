@extends('layouts.doctor')

@section('title', 'Chỉ định siêu âm')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: #1f2937;">
                <i class="fas fa-ultrasound me-2" style="color: #10b981;"></i>
                Chỉ định siêu âm
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('doctor.benhan.edit', $benhAn->id) }}">Bệnh án #{{ $benhAn->id }}</a></li>
                    <li class="breadcrumb-item active">Chỉ định siêu âm</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('doctor.benhan.edit', $benhAn->id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <div class="row">
        {{-- Form chỉ định --}}
        <div class="col-lg-8">
            <div class="card vc-card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-file-medical me-2" style="color: #10b981;"></i>
                        Thông tin chỉ định
                    </h5>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('doctor.sieuam.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="benh_an_id" value="{{ $benhAn->id }}">

                        {{-- Loại siêu âm --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Loại siêu âm <span class="text-danger">*</span>
                            </label>
                            <select name="loai_sieu_am_id" id="loaiSieuAmSelect" class="form-select form-select-lg" required>
                                <option value="">-- Chọn loại siêu âm --</option>
                                @foreach($loaiSieuAms as $loai)
                                    <option value="{{ $loai->id }}"
                                            data-gia="{{ $loai->gia_mac_dinh }}"
                                            data-mota="{{ $loai->mo_ta }}"
                                            {{ old('loai_sieu_am_id') == $loai->id ? 'selected' : '' }}>
                                        {{ $loai->ten }} - {{ number_format($loai->gia_mac_dinh, 0, ',', '.') }}đ
                                    </option>
                                @endforeach
                            </select>
                            <div id="loaiInfo" class="mt-2 small text-muted"></div>
                        </div>

                        {{-- Mô tả yêu cầu --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Mô tả yêu cầu</label>
                            <textarea name="mo_ta" rows="4" class="form-control"
                                      placeholder="VD: Siêu âm thai định kỳ 20 tuần, kiểm tra dị tật cấu trúc...">{{ old('mo_ta') }}</textarea>
                            <div class="form-text">Mô tả rõ mục đích siêu âm để kỹ thuật viên thực hiện đúng</div>
                        </div>

                        {{-- Buttons --}}
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Lưu chỉ định
                            </button>
                            <a href="{{ route('doctor.benhan.edit', $benhAn->id) }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times me-2"></i>Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Thông tin bệnh nhân --}}
        <div class="col-lg-4">
            <div class="card vc-card mb-4">
                <div class="card-header bg-light py-3">
                    <h6 class="mb-0"><i class="fas fa-user-injured me-2"></i>Thông tin bệnh nhân</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Họ tên</small>
                        <strong>{{ $benhAn->user->name ?? 'N/A' }}</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Mã bệnh án</small>
                        <span class="badge bg-primary">BA-{{ $benhAn->id }}</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Ngày khám</small>
                        {{ $benhAn->ngay_kham ? $benhAn->ngay_kham->format('d/m/Y') : 'N/A' }}
                    </div>
                    <div>
                        <small class="text-muted d-block mb-1">Chẩn đoán</small>
                        {{ $benhAn->chuan_doan ?? 'Chưa có chẩn đoán' }}
                    </div>
                </div>
            </div>

            {{-- Hướng dẫn --}}
            <div class="card vc-card bg-light">
                <div class="card-body">
                    <h6 class="mb-3"><i class="fas fa-info-circle text-primary me-2"></i>Lưu ý</h6>
                    <ul class="small mb-0 ps-3">
                        <li>Chọn loại siêu âm phù hợp với chẩn đoán</li>
                        <li>Mô tả rõ mục đích để kỹ thuật viên thực hiện đúng</li>
                        <li>Kết quả sẽ được upload bởi kỹ thuật viên siêu âm</li>
                        <li>Bạn sẽ nhận thông báo khi có kết quả</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('loaiSieuAmSelect');
    const infoDiv = document.getElementById('loaiInfo');

    select.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        const mota = option.dataset.mota;

        if (mota) {
            infoDiv.innerHTML = '<i class="fas fa-info-circle me-1"></i>' + mota;
            infoDiv.classList.add('text-info');
        } else {
            infoDiv.innerHTML = '';
        }
    });
});
</script>
@endpush
@endsection
