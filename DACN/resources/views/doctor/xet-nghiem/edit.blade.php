@extends('layouts.doctor')

@section('title', 'Nhập Kết Quả Xét Nghiệm')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-9">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-file-earmark-medical"></i> Nhập Kết Quả Xét Nghiệm
                    </h5>
                    <a href="{{ route('doctor.benhan.show', $xetNghiem->benh_an_id) }}" class="btn btn-sm btn-light">
                        <i class="bi bi-arrow-left"></i> Quay lại
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('doctor.xet-nghiem.update', $xetNghiem->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Ngày Lấy Mẫu <span class="text-danger">*</span></label>
                                <input type="date" name="ngay_lay_mau" class="form-control @error('ngay_lay_mau') is-invalid @enderror" 
                                    value="{{ old('ngay_lay_mau', $xetNghiem->ngay_lay_mau?->format('Y-m-d')) }}" required>
                                @error('ngay_lay_mau')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Ngày Trả Kết Quả</label>
                                <input type="date" name="ngay_tra_ket_qua" class="form-control @error('ngay_tra_ket_qua') is-invalid @enderror" 
                                    value="{{ old('ngay_tra_ket_qua', $xetNghiem->ngay_tra_ket_qua?->format('Y-m-d')) }}">
                                @error('ngay_tra_ket_qua')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Trạng Thái <span class="text-danger">*</span></label>
                            <select name="trang_thai" class="form-select @error('trang_thai') is-invalid @enderror" required>
                                <option value="">-- Chọn trạng thái --</option>
                                <option value="Chờ lấy mẫu" {{ old('trang_thai', $xetNghiem->trang_thai) == 'Chờ lấy mẫu' ? 'selected' : '' }}>Chờ lấy mẫu</option>
                                <option value="Đã lấy mẫu" {{ old('trang_thai', $xetNghiem->trang_thai) == 'Đã lấy mẫu' ? 'selected' : '' }}>Đã lấy mẫu</option>
                                <option value="Đang xét nghiệm" {{ old('trang_thai', $xetNghiem->trang_thai) == 'Đang xét nghiệm' ? 'selected' : '' }}>Đang xét nghiệm</option>
                                <option value="Có kết quả" {{ old('trang_thai', $xetNghiem->trang_thai) == 'Có kết quả' ? 'selected' : '' }}>Có kết quả</option>
                                <option value="Đã hủy" {{ old('trang_thai', $xetNghiem->trang_thai) == 'Đã hủy' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                            @error('trang_thai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">
                        <h6 class="mb-3">Chỉ Số Xét Nghiệm</h6>

                        <div id="chi-so-container">
                            @php
                                $chiSoData = old('chi_so', $xetNghiem->chi_so ?? []);
                                if (empty($chiSoData)) {
                                    $chiSoData = [['ten' => '', 'ket_qua' => '', 'don_vi' => '', 'gia_tri_bt' => '']];
                                }
                            @endphp

                            @foreach($chiSoData as $index => $chiSo)
                            <div class="chi-so-item border rounded p-3 mb-3 position-relative">
                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 remove-chi-so" style="display: {{ count($chiSoData) > 1 ? 'inline-block' : 'none' }};">
                                    <i class="bi bi-trash"></i>
                                </button>

                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label">Tên Chỉ Số</label>
                                        <input type="text" name="chi_so[{{ $index }}][ten]" class="form-control" 
                                            value="{{ $chiSo['ten'] ?? '' }}" placeholder="VD: Hồng cầu">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Kết Quả</label>
                                        <input type="text" name="chi_so[{{ $index }}][ket_qua]" class="form-control" 
                                            value="{{ $chiSo['ket_qua'] ?? '' }}" placeholder="VD: 4.5">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Đơn Vị</label>
                                        <input type="text" name="chi_so[{{ $index }}][don_vi]" class="form-control" 
                                            value="{{ $chiSo['don_vi'] ?? '' }}" placeholder="VD: T/L">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Giá Trị BT</label>
                                        <input type="text" name="chi_so[{{ $index }}][gia_tri_bt]" class="form-control" 
                                            value="{{ $chiSo['gia_tri_bt'] ?? '' }}" placeholder="VD: 4.0-5.5">
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <button type="button" class="btn btn-outline-primary btn-sm mb-3" id="add-chi-so">
                            <i class="bi bi-plus-circle"></i> Thêm Chỉ Số
                        </button>

                        <div class="mb-3">
                            <label class="form-label">Nhận Xét</label>
                            <textarea name="nhan_xet" rows="3" class="form-control @error('nhan_xet') is-invalid @enderror" 
                                placeholder="Nhập nhận xét của bác sĩ về kết quả xét nghiệm">{{ old('nhan_xet', $xetNghiem->nhan_xet) }}</textarea>
                            @error('nhan_xet')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kết Luận</label>
                            <textarea name="ket_luan" rows="3" class="form-control @error('ket_luan') is-invalid @enderror" 
                                placeholder="Kết luận chung về kết quả xét nghiệm">{{ old('ket_luan', $xetNghiem->ket_luan) }}</textarea>
                            @error('ket_luan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Đề Xuất</label>
                            <textarea name="de_xuat" rows="2" class="form-control @error('de_xuat') is-invalid @enderror" 
                                placeholder="Đề xuất điều trị hoặc xét nghiệm bổ sung">{{ old('de_xuat', $xetNghiem->de_xuat) }}</textarea>
                            @error('de_xuat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ghi Chú</label>
                            <textarea name="ghi_chu" rows="2" class="form-control @error('ghi_chu') is-invalid @enderror" 
                                placeholder="Ghi chú khác">{{ old('ghi_chu', $xetNghiem->ghi_chu) }}</textarea>
                            @error('ghi_chu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">File Kết Quả (PDF, hình ảnh)</label>
                            <input type="file" name="file_ket_qua[]" class="form-control @error('file_ket_qua.*') is-invalid @enderror" 
                                multiple accept=".pdf,.jpg,.jpeg,.png">
                            <small class="form-text text-muted">Có thể chọn nhiều file. Tối đa 5MB/file.</small>
                            @error('file_ket_qua.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                            @if(!empty($xetNghiem->file_ket_qua))
                            <div class="mt-2">
                                <strong>File hiện tại:</strong>
                                <ul class="list-unstyled">
                                    @foreach($xetNghiem->file_ket_qua as $file)
                                    <li>
                                        <a href="{{ Storage::url($file) }}" target="_blank" class="text-decoration-none">
                                            <i class="bi bi-file-earmark-pdf"></i> {{ basename($file) }}
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('doctor.benhan.show', $xetNghiem->benh_an_id) }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Hủy
                            </a>
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-check-circle"></i> Lưu Kết Quả
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-info-circle"></i> Thông Tin Chỉ Định</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Loại:</strong> <span class="badge bg-secondary">{{ $xetNghiem->loai_xet_nghiem }}</span></p>
                    <p class="mb-2"><strong>Tên XN:</strong> {{ $xetNghiem->ten_xet_nghiem }}</p>
                    <p class="mb-2"><strong>Ngày chỉ định:</strong> {{ $xetNghiem->ngay_chi_dinh->format('d/m/Y') }}</p>
                    <p class="mb-2"><strong>Bác sĩ chỉ định:</strong> {{ $xetNghiem->bacSiChiDinh->ho_ten ?? 'N/A' }}</p>
                    @if($xetNghiem->can_nhin_an)
                    <p class="mb-0"><span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle"></i> Cần nhịn ăn</span></p>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-person"></i> Thông Tin BN</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Họ tên:</strong> {{ $xetNghiem->benhAn->benh_nhan_ten }}</p>
                    <p class="mb-2"><strong>Tuổi:</strong> {{ \Carbon\Carbon::parse($xetNghiem->benhAn->benh_nhan_ngay_sinh)->age }} tuổi</p>
                    <p class="mb-0"><strong>Giới tính:</strong> {{ $xetNghiem->benhAn->benh_nhan_gioi_tinh }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let chiSoIndex = {{ count($chiSoData) }};

    // Add new chi so
    document.getElementById('add-chi-so').addEventListener('click', function() {
        const container = document.getElementById('chi-so-container');
        const newItem = document.createElement('div');
        newItem.className = 'chi-so-item border rounded p-3 mb-3 position-relative';
        newItem.innerHTML = `
            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 remove-chi-so">
                <i class="bi bi-trash"></i>
            </button>
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Tên Chỉ Số</label>
                    <input type="text" name="chi_so[${chiSoIndex}][ten]" class="form-control" placeholder="VD: Hồng cầu">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kết Quả</label>
                    <input type="text" name="chi_so[${chiSoIndex}][ket_qua]" class="form-control" placeholder="VD: 4.5">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Đơn Vị</label>
                    <input type="text" name="chi_so[${chiSoIndex}][don_vi]" class="form-control" placeholder="VD: T/L">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Giá Trị BT</label>
                    <input type="text" name="chi_so[${chiSoIndex}][gia_tri_bt]" class="form-control" placeholder="VD: 4.0-5.5">
                </div>
            </div>
        `;
        container.appendChild(newItem);
        chiSoIndex++;
        updateRemoveButtons();
    });

    // Remove chi so (event delegation)
    document.getElementById('chi-so-container').addEventListener('click', function(e) {
        if (e.target.closest('.remove-chi-so')) {
            e.target.closest('.chi-so-item').remove();
            updateRemoveButtons();
        }
    });

    function updateRemoveButtons() {
        const items = document.querySelectorAll('.chi-so-item');
        items.forEach(item => {
            const removeBtn = item.querySelector('.remove-chi-so');
            if (removeBtn) {
                removeBtn.style.display = items.length > 1 ? 'inline-block' : 'none';
            }
        });
    }

    updateRemoveButtons();
});
</script>
@endpush
@endsection
