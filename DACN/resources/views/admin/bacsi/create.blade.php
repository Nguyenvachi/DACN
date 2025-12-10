@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">

        <!-- ============================
                 🔥 BỔ SUNG: Thanh tiêu đề đẹp
            ============================= -->
        <div class="bg-white shadow-sm p-3 rounded mb-4 d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold">
                <i class="fas fa-user-md text-primary me-2"></i> Thêm Bác sĩ mới
            </h4>
            <a href="{{ route('admin.bac-si.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
        <!-- ============================ -->

        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg border-0">
                    <div class="card-body">

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('admin.bac-si.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row g-4">

                                <!-- Họ tên -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Họ tên <span class="text-danger">*</span></label>
                                    <input type="text" name="ho_ten"
                                        class="form-control form-control-lg @error('ho_ten') is-invalid @enderror"
                                        value="{{ old('ho_ten') }}" required>
                                    @error('ho_ten')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email"
                                        class="form-control form-control-lg @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Số điện thoại -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Số điện thoại <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="so_dien_thoai"
                                        class="form-control form-control-lg @error('so_dien_thoai') is-invalid @enderror"
                                        value="{{ old('so_dien_thoai') }}" required>
                                    @error('so_dien_thoai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Ảnh đại diện -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Ảnh đại diện (avatar)</label>
                                    <input type="file" name="avatar" accept="image/*"
                                        class="form-control form-control-lg @error('avatar') is-invalid @enderror">
                                    @error('avatar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="mt-2">
                                        <img id="avatarPreview" src="" alt="" style="max-width:120px;display:none;border-radius:8px;" />
                                    </div>
                                </div>

                                <!-- Chuyên khoa -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Chuyên khoa <span class="text-danger">*</span></label>
                                    <select name="chuyen_khoa_id" 
                                            class="form-control form-control-lg @error('chuyen_khoa_id') is-invalid @enderror"
                                            required>
                                        <option value="">-- Chọn chuyên khoa --</option>
                                        @foreach($chuyenKhoas as $ck)
                                            <option value="{{ $ck->id }}" 
                                                {{ old('chuyen_khoa_id') == $ck->id ? 'selected' : '' }}>
                                                {{ $ck->ten }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('chuyen_khoa_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Số năm kinh nghiệm -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Số năm kinh nghiệm</label>
                                    <input type="number" name="kinh_nghiem"
                                        class="form-control form-control-lg @error('kinh_nghiem') is-invalid @enderror"
                                        value="{{ old('kinh_nghiem', 0) }}" min="0" max="50">
                                    @error('kinh_nghiem')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Trạng thái -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Trạng thái <span class="text-danger">*</span></label>
                                    <select name="trang_thai"
                                        class="form-control form-control-lg @error('trang_thai') is-invalid @enderror"
                                        required>
                                        <option value="Đang hoạt động" @selected(old('trang_thai') == 'Đang hoạt động')>
                                            Đang hoạt động
                                        </option>
                                        <option value="Ngừng hoạt động" @selected(old('trang_thai') == 'Ngừng hoạt động')>
                                            Ngừng hoạt động
                                        </option>
                                    </select>
                                    @error('trang_thai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Địa chỉ -->
                                <div class="col-12">
                                    <label class="form-label fw-bold">Địa chỉ</label>
                                    <input type="text" name="dia_chi"
                                        class="form-control form-control-lg @error('dia_chi') is-invalid @enderror"
                                        value="{{ old('dia_chi') }}">
                                    @error('dia_chi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Mô tả -->
                                <div class="col-12">
                                    <label class="form-label fw-bold">Mô tả / Giới thiệu</label>
                                    <textarea name="mo_ta" class="form-control form-control-lg @error('mo_ta') is-invalid @enderror" rows="4"
                                        placeholder="Thông tin về trình độ, chuyên môn, kinh nghiệm làm việc...">{{ old('mo_ta') }}</textarea>
                                    @error('mo_ta')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <a href="{{ route('admin.bac-si.index') }}" class="btn btn-light me-2">
                                    <i class="fas fa-times"></i> Hủy
                                </a>

                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save"></i> Lưu
                                </button>
                            </div>

                            <div class="alert alert-info mt-4">
                                <i class="fas fa-info-circle"></i>
                                <strong>Lưu ý:</strong> Bác sĩ sẽ nhận mật khẩu mặc định:
                                <code>Thanh@123</code>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================
             🔥 BỔ SUNG: CSS UI hiện đại & đồng bộ
        ============================================= -->
    <style>
        .form-label {
            font-size: 15px;
        }

        .form-control-lg {
            padding: 10px 14px;
            border-radius: 10px;
        }

        .card {
            border-radius: 14px;
            overflow: hidden;
        }

        textarea {
            resize: none;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.querySelector('input[name="avatar"]');
            const preview = document.getElementById('avatarPreview');
            if (!input) return;
            input.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (!file) { preview.style.display = 'none'; return; }
                const url = URL.createObjectURL(file);
                preview.src = url;
                preview.style.display = 'block';
            });
        });
    </script>
@endsection
