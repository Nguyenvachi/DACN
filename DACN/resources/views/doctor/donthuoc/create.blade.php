@extends('layouts.doctor')

@section('title', 'Kê đơn thuốc')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: #1f2937;">
                <i class="fas fa-prescription-bottle-alt me-2" style="color: #10b981;"></i>
                Kê đơn thuốc
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('doctor.benhan.edit', $benhAn->id) }}">Bệnh án #{{ $benhAn->id }}</a></li>
                    <li class="breadcrumb-item active">Kê đơn thuốc</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('doctor.benhan.edit', $benhAn->id) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    {{-- Thông tin bệnh nhân --}}
    <div class="card vc-card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">
                <i class="fas fa-user-injured me-2" style="color: #3b82f6;"></i>
                Thông tin bệnh nhân
            </h5>
            <div class="row">
                <div class="col-md-4">
                    <p class="mb-2"><strong>Họ tên:</strong> {{ $benhAn->user->name }}</p>
                    <p class="mb-2"><strong>Mã BN:</strong> #{{ $benhAn->user->id }}</p>
                </div>
                <div class="col-md-4">
                    <p class="mb-2"><strong>Ngày khám:</strong> {{ \Carbon\Carbon::parse($benhAn->ngay_kham)->format('d/m/Y H:i') }}</p>
                    <p class="mb-2"><strong>Chẩn đoán:</strong> {{ $benhAn->chuan_doan ?? 'Chưa có' }}</p>
                </div>
                <div class="col-md-4">
                    <p class="mb-2"><strong>Dịch vụ:</strong> {{ $benhAn->lichHen->dichVu->ten_dich_vu ?? 'N/A' }}</p>
                    <p class="mb-2"><strong>Trạng thái:</strong>
                        <span class="vc-badge vc-badge-{{ $benhAn->lichHen->trang_thai === 'Hoàn thành' ? 'success' : 'warning' }}">
                            {{ $benhAn->lichHen->trang_thai }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Cảnh báo nếu đã có đơn --}}
    @if($existingDonThuoc)
    <div class="alert alert-warning" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Đã có đơn thuốc!</strong> Bệnh án này đã có đơn thuốc #{{ $existingDonThuoc->id }}.
        Nếu lưu lại, đơn cũ sẽ bị ghi đè.
        <a href="{{ route('doctor.donthuoc.show', $existingDonThuoc->id) }}" class="alert-link ms-2" target="_blank">
            Xem đơn cũ <i class="fas fa-external-link-alt ms-1"></i>
        </a>
    </div>
    @endif

    {{-- Form kê đơn --}}
    <form action="{{ route('doctor.donthuoc.store', $benhAn->id) }}" method="POST" id="prescriptionForm">
        @csrf

        <div class="card vc-card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-pills me-2" style="color: #10b981;"></i>
                    Danh sách thuốc
                </h5>
                <button type="button" class="btn btn-sm vc-btn-primary" id="addMedicineBtn">
                    <i class="fas fa-plus me-1"></i>Thêm thuốc
                </button>
            </div>
            <div class="card-body">
                <div id="medicineList">
                    @if($existingDonThuoc && $existingDonThuoc->items->count() > 0)
                        @foreach($existingDonThuoc->items as $index => $item)
                        <div class="medicine-row mb-3 p-3 border rounded" data-index="{{ $index }}">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="mb-0">Thuốc #<span class="medicine-number">{{ $index + 1 }}</span></h6>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-medicine">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Tên thuốc <span class="text-danger">*</span></label>
                                    <select name="thuocs[{{ $index }}][thuoc_id]" class="form-select thuoc-select" required>
                                        <option value="">-- Chọn thuốc --</option>
                                        @foreach($thuocs as $thuoc)
                                        <option value="{{ $thuoc->id }}"
                                                data-hoat-chat="{{ $thuoc->hoat_chat }}"
                                                data-ham-luong="{{ $thuoc->ham_luong }}"
                                                data-don-vi="{{ $thuoc->don_vi }}"
                                                {{ $item->thuoc_id == $thuoc->id ? 'selected' : '' }}>
                                            {{ $thuoc->ten }} ({{ $thuoc->ham_luong }})
                                        </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted thuoc-info"></small>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Số lượng <span class="text-danger">*</span></label>
                                    <input type="number" name="thuocs[{{ $index }}][so_luong]"
                                           class="form-control" min="1" value="{{ $item->so_luong }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Liều dùng <span class="text-danger">*</span></label>
                                    <input type="text" name="thuocs[{{ $index }}][lieu_dung]"
                                           class="form-control" placeholder="VD: 2 viên/lần, 3 lần/ngày"
                                           value="{{ $item->lieu_dung }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Cách dùng</label>
                                    <input type="text" name="thuocs[{{ $index }}][cach_dung]"
                                           class="form-control" placeholder="VD: Uống sau ăn"
                                           value="{{ $item->cach_dung }}">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        {{-- Empty state --}}
                        <div class="text-center py-5" id="emptyState">
                            <i class="fas fa-prescription-bottle-alt fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Chưa có thuốc nào. Nhấn "Thêm thuốc" để bắt đầu.</p>
                        </div>
                    @endif
                </div>

                {{-- Ghi chú --}}
                <div class="mt-4">
                    <label class="form-label">Ghi chú cho bệnh nhân</label>
                    <textarea name="ghi_chu" class="form-control" rows="3"
                              placeholder="VD: Uống đủ liều, không tự ý ngừng thuốc...">{{ $existingDonThuoc->ghi_chu ?? '' }}</textarea>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('doctor.benhan.edit', $benhAn->id) }}" class="btn btn-outline-secondary">
                <i class="fas fa-times me-2"></i>Hủy
            </a>
            <button type="submit" class="btn vc-btn-primary">
                <i class="fas fa-save me-2"></i>Lưu đơn thuốc
            </button>
        </div>
    </form>
</div>

{{-- Template cho medicine row --}}
<template id="medicineRowTemplate">
    <div class="medicine-row mb-3 p-3 border rounded" data-index="INDEX_PLACEHOLDER">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <h6 class="mb-0">Thuốc #<span class="medicine-number">NUMBER_PLACEHOLDER</span></h6>
            <button type="button" class="btn btn-sm btn-outline-danger remove-medicine">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Tên thuốc <span class="text-danger">*</span></label>
                <select name="thuocs[INDEX_PLACEHOLDER][thuoc_id]" class="form-select thuoc-select" required>
                    <option value="">-- Chọn thuốc --</option>
                    @foreach($thuocs as $thuoc)
                    <option value="{{ $thuoc->id }}"
                            data-hoat-chat="{{ $thuoc->hoat_chat }}"
                            data-ham-luong="{{ $thuoc->ham_luong }}"
                            data-don-vi="{{ $thuoc->don_vi }}">
                        {{ $thuoc->ten }} ({{ $thuoc->ham_luong }})
                    </option>
                    @endforeach
                </select>
                <small class="text-muted thuoc-info"></small>
            </div>
            <div class="col-md-2">
                <label class="form-label">Số lượng <span class="text-danger">*</span></label>
                <input type="number" name="thuocs[INDEX_PLACEHOLDER][so_luong]"
                       class="form-control" min="1" value="1" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Liều dùng <span class="text-danger">*</span></label>
                <input type="text" name="thuocs[INDEX_PLACEHOLDER][lieu_dung]"
                       class="form-control" placeholder="VD: 2 viên/lần, 3 lần/ngày" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Cách dùng</label>
                <input type="text" name="thuocs[INDEX_PLACEHOLDER][cach_dung]"
                       class="form-control" placeholder="VD: Uống sau ăn">
            </div>
        </div>
    </div>
</template>

@push('scripts')
<script>
let medicineIndex = {{ $existingDonThuoc && $existingDonThuoc->items->count() > 0 ? $existingDonThuoc->items->count() : 0 }};

$(document).ready(function() {
    // Thêm thuốc mới
    $('#addMedicineBtn').click(function() {
        $('#emptyState').remove();

        const template = $('#medicineRowTemplate').html();
        const html = template
            .replace(/INDEX_PLACEHOLDER/g, medicineIndex)
            .replace(/NUMBER_PLACEHOLDER/g, medicineIndex + 1);

        $('#medicineList').append(html);
        medicineIndex++;

        // Re-attach event listeners
        attachMedicineEvents();
    });

    // Xóa thuốc
    function attachMedicineEvents() {
        $('.remove-medicine').off('click').on('click', function() {
            if ($('.medicine-row').length <= 1) {
                alert('Phải có ít nhất 1 loại thuốc!');
                return;
            }
            $(this).closest('.medicine-row').remove();
            updateMedicineNumbers();
        });

        // Hiển thị thông tin thuốc khi chọn
        $('.thuoc-select').off('change').on('change', function() {
            const selected = $(this).find('option:selected');
            const infoContainer = $(this).siblings('.thuoc-info');

            if (selected.val()) {
                const hoatChat = selected.data('hoat-chat');
                const donVi = selected.data('don-vi');
                infoContainer.text(`Hoạt chất: ${hoatChat} | Đơn vị: ${donVi}`);
            } else {
                infoContainer.text('');
            }
        });
    }

    // Cập nhật số thứ tự
    function updateMedicineNumbers() {
        $('.medicine-row').each(function(index) {
            $(this).find('.medicine-number').text(index + 1);
        });
    }

    // Init events
    attachMedicineEvents();

    // Form validation
    $('#prescriptionForm').submit(function(e) {
        if ($('.medicine-row').length === 0) {
            e.preventDefault();
            alert('Vui lòng thêm ít nhất 1 loại thuốc!');
            return false;
        }
    });
});
</script>
@endpush
@endsection
