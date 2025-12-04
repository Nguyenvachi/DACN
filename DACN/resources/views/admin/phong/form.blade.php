@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="fas fa-door-open me-2"></i>
                {{ $phong->exists ? 'Sửa phòng khám' : 'Thêm phòng khám' }}
            </h2>

            <a href="{{ route('admin.phong.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        {{-- FORM --}}
        <div class="card shadow-lg border-0">
            <div class="card-body">

                <form method="post"
                    action="{{ $phong->exists ? route('admin.phong.update', $phong) : route('admin.phong.store') }}">
                    @csrf
                    @if ($phong->exists)
                        @method('PUT')
                    @endif

                    <div class="row g-4">

                        {{-- Tên --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tên phòng <span class="text-danger">*</span></label>
                            <input type="text" name="ten" class="form-control form-control-lg"
                                value="{{ old('ten', $phong->ten) }}" required>
                        </div>

                        {{-- Loại --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Loại phòng</label>
                            <input type="text" name="loai" class="form-control form-control-lg"
                                placeholder="phong_kham, phong_chuc_nang, phong_xet_nghiem..."
                                value="{{ old('loai', $phong->loai) }}">
                        </div>

                        {{-- MỞ RỘNG: Trạng thái (Parent file: resources/views/admin/phong/form.blade.php) --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Trạng thái</label>
                            <select name="trang_thai" class="form-select form-select-lg">
                                <option value="Sẵn sàng" {{ old('trang_thai', $phong->trang_thai ?? 'Sẵn sàng') === 'Sẵn sàng' ? 'selected' : '' }}>
                                    ✓ Sẵn sàng
                                </option>
                                <option value="Đang sử dụng" {{ old('trang_thai', $phong->trang_thai) === 'Đang sử dụng' ? 'selected' : '' }}>
                                    ⏳ Đang sử dụng
                                </option>
                                <option value="Bảo trì" {{ old('trang_thai', $phong->trang_thai) === 'Bảo trì' ? 'selected' : '' }}>
                                    🔧 Bảo trì
                                </option>
                                <option value="Tạm ngưng" {{ old('trang_thai', $phong->trang_thai) === 'Tạm ngưng' ? 'selected' : '' }}>
                                    ✖ Tạm ngưng
                                </option>
                            </select>
                        </div>

                        {{-- MỞ RỘNG: Vị trí (Parent file: resources/views/admin/phong/form.blade.php) --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Vị trí</label>
                            <input type="text" name="vi_tri" class="form-control form-control-lg"
                                placeholder="Tầng 1, Tầng 2, Khu A..."
                                value="{{ old('vi_tri', $phong->vi_tri) }}">
                        </div>

                        {{-- MỞ RỘNG: Diện tích (Parent file: resources/views/admin/phong/form.blade.php) --}}
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Diện tích (m²)</label>
                            <input type="number" step="0.01" name="dien_tich" class="form-control form-control-lg"
                                placeholder="25.5"
                                value="{{ old('dien_tich', $phong->dien_tich) }}">
                        </div>

                        {{-- MỞ RỘNG: Sức chứa (Parent file: resources/views/admin/phong/form.blade.php) --}}
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Sức chứa (người)</label>
                            <input type="number" name="suc_chua" class="form-control form-control-lg"
                                placeholder="5"
                                value="{{ old('suc_chua', $phong->suc_chua) }}">
                        </div>

                        {{-- Mô tả --}}
                        <div class="col-12">
                            <label class="form-label fw-bold">Mô tả</label>
                            <textarea name="mo_ta" rows="3" class="form-control form-control-lg">{{ old('mo_ta', $phong->mo_ta) }}</textarea>
                        </div>

                        {{-- Gán bác sĩ --}}
                        <div class="col-12">
                            <label class="form-label fw-bold">Gán bác sĩ</label>

                            <select name="bac_si_ids[]" id="selectBacSi" class="form-select select2" multiple>
                                @foreach ($bacSis as $bs)
                                    <option value="{{ $bs->id }}" data-name="{{ $bs->ho_ten }}"
                                        data-email="{{ $bs->email }}"
                                        data-avatar="https://ui-avatars.com/api/?name={{ urlencode($bs->ho_ten) }}&background=0D6EFD&color=fff"
                                        {{ in_array($bs->id, old('bac_si_ids', $phong->exists ? $phong->bacSis->pluck('id')->all() : [])) ? 'selected' : '' }}>
                                        {{ $bs->ho_ten }} {{ $bs->email ? "($bs->email)" : '' }}
                                    </option>
                                @endforeach
                            </select>

                            <small class="text-muted">Bạn có thể tìm theo tên hoặc email bác sĩ</small>
                        </div>

                    </div>

                    {{-- Buttons --}}
                    <div class="mt-4 d-flex justify-content-end">
                        <a href="{{ route('admin.phong.index') }}" class="btn btn-light me-2">
                            <i class="fas fa-times"></i> Hủy
                        </a>

                        <button class="btn btn-primary px-4">
                            <i class="fas fa-save me-1"></i> Lưu
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
@endsection

{{-- ============================
     CSS SELECT2 giống chuyên khoa
============================ --}}
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />

    <style>
        .select2-container .select2-selection--multiple {
            border-radius: 10px !important;
            padding: 8px !important;
            min-height: 52px;
            border: 1px solid #dee2e6 !important;
        }

        .select2-selection__choice {
            display: flex !important;
            align-items: center;
            gap: 6px;
            background: #0d6efd !important;
            color: #fff !important;
            border-radius: 8px !important;
            padding: 4px 10px !important;
            border: none !important;
        }

        .select2-selection__choice__remove {
            color: #fff !important;
            margin-right: 4px;
        }
    </style>
@endpush

{{-- ============================
     JS SELECT2 giống chuyên khoa
============================ --}}
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            function formatDoctor(option) {
                if (!option.id) return option.text;

                const name = $(option.element).data("name");
                const email = $(option.element).data("email") ?? "";
                const avatar = $(option.element).data("avatar");

                return $(`
                <div class="d-flex align-items-center">
                    <img src="${avatar}" class="rounded-circle me-2" width="32" height="32">
                    <div class="d-flex flex-column lh-1">
                        <span class="fw-semibold">${name}</span>
                        <small class="text-muted">${email}</small>
                    </div>
                </div>
            `);
            }

            $('#selectBacSi').select2({
                placeholder: "Chọn bác sĩ...",
                allowClear: true,
                width: "100%",
                theme: "bootstrap-5",
                templateResult: formatDoctor,
                templateSelection: formatDoctor,
                dropdownAutoWidth: true
            });

        });
    </script>
@endpush
