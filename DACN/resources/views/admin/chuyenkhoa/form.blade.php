@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">

        {{-- ============================
             🔥 Header động
        ============================= --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="fas fa-stethoscope me-2"></i>
                {{ $khoa->exists ? 'Sửa chuyên khoa' : 'Thêm chuyên khoa' }}
            </h2>

            <a href="{{ route('admin.chuyenkhoa.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        {{-- ============================
             🔥 Card form
        ============================= --}}
        <div class="card shadow-lg border-0">
            <div class="card-body">

                <form method="post"
                    action="{{ $khoa->exists ? route('admin.chuyenkhoa.update', $khoa) : route('admin.chuyenkhoa.store') }}">
                    @csrf
                    @if ($khoa->exists)
                        @method('PUT')
                    @endif

                    <div class="row g-4">

                        {{-- Tên chuyên khoa --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tên chuyên khoa <span class="text-danger">*</span></label>
                            <input type="text" name="ten" class="form-control form-control-lg"
                                value="{{ old('ten', $khoa->ten) }}" required>
                        </div>

                        {{-- Slug --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Slug</label>
                            <input type="text" name="slug" class="form-control form-control-lg"
                                value="{{ old('slug', $khoa->slug) }}">
                        </div>

                        {{-- Mô tả --}}
                        <div class="col-12">
                            <label class="form-label fw-bold">Mô tả</label>
                            <textarea name="mo_ta" rows="3" class="form-control form-control-lg">{{ old('mo_ta', $khoa->mo_ta) }}</textarea>
                        </div>

                        {{-- Gán bác sĩ --}}
                        <div class="col-12">
                            <label class="form-label fw-bold">Gán bác sĩ</label>

                            <select name="bac_si_ids[]" id="selectBacSi" class="form-select select2" multiple>
                                @foreach ($bacSis as $bs)
                                    <option value="{{ $bs->id }}" data-name="{{ $bs->ho_ten }}"
                                        data-email="{{ $bs->email }}"
                                        data-avatar="https://ui-avatars.com/api/?name={{ urlencode($bs->ho_ten) }}&background=0D6EFD&color=fff"
                                        {{ in_array($bs->id, old('bac_si_ids', $khoa->exists ? $khoa->bacSis->pluck('id')->all() : [])) ? 'selected' : '' }}>
                                        {{ $bs->ho_ten }} {{ $bs->email ? "($bs->email)" : '' }}
                                    </option>
                                @endforeach
                            </select>

                            <small class="text-muted">Bạn có thể tìm theo tên hoặc email bác sĩ</small>
                        </div>

                    </div>

                    {{-- Nút --}}
                    <div class="mt-4 d-flex justify-content-end">
                        <a href="{{ route('admin.chuyenkhoa.index') }}" class="btn btn-light me-2">
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
     🔥 CSS tùy chỉnh
============================= --}}
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />

    <style>
        .form-label {
            font-weight: 600;
            font-size: 15px;
        }

        .form-control-lg,
        .form-select-lg {
            border-radius: 10px;
            padding: 10px 14px;
        }

        textarea {
            resize: none;
        }

        /* Select2 custom đẹp */
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
     🔥 Select2 (JS)
============================= --}}
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
