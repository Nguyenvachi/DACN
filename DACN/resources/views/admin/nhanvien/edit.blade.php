@extends('layouts.admin')

@section('content')

    <style>
        .card-custom {
            border-radius: 12px;
            border: none;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        }

        .form-label {
            font-weight: 600;
        }
    </style>

    <div class="container-fluid py-4">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-pencil-square me-2 text-primary"></i>
                Sửa nhân viên #{{ $nhanvien->id }}
            </h2>

            <a href="{{ route('admin.nhanvien.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Quay lại danh sách
            </a>
        </div>

        {{-- CARD --}}
        <div class="card card-custom">
            <div class="card-body">

                {{-- ERROR LIST --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Lỗi:</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- FORM --}}
                <form method="POST" action="{{ route('admin.nhanvien.update', $nhanvien) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        {{-- HỌ TÊN --}}
                        <div class="col-md-6">
                            <label class="form-label">Họ tên <span class="text-danger">*</span></label>
                            <input name="ho_ten" class="form-control" required
                                value="{{ old('ho_ten', $nhanvien->ho_ten) }}">
                        </div>

                        {{-- CHỨC VỤ --}}
                        <div class="col-md-6">
                            <label class="form-label">Chức vụ</label>
                            <input name="chuc_vu" class="form-control" value="{{ old('chuc_vu', $nhanvien->chuc_vu) }}">
                        </div>

                        {{-- SỐ ĐIỆN THOẠI --}}
                        <div class="col-md-6">
                            <label class="form-label">Số điện thoại</label>
                            <input name="so_dien_thoai" class="form-control"
                                value="{{ old('so_dien_thoai', $nhanvien->so_dien_thoai) }}">
                        </div>

                        {{-- EMAIL ĐĂNG NHẬP --}}
                        <div class="col-md-6">
                            <label class="form-label">Email công việc (đăng nhập)</label>
                            <input type="email" class="form-control" value="{{ $nhanvien->email_cong_viec }}" disabled>
                            <small class="text-muted">
                                ⚠️ Email không thể thay đổi (dùng để đăng nhập hệ thống).
                            </small>
                        </div>

                        {{-- NGÀY SINH --}}
                        <div class="col-md-4">
                            <label class="form-label">Ngày sinh</label>
                            <input type="date" name="ngay_sinh" class="form-control"
                                value="{{ old('ngay_sinh', $nhanvien->ngay_sinh) }}">
                        </div>

                        {{-- GIỚI TÍNH --}}
                        <div class="col-md-4">
                            <label class="form-label">Giới tính</label>
                            <select name="gioi_tinh" class="form-select">
                                @foreach (['' => '-- Giới tính --', 'nam' => 'Nam', 'nu' => 'Nữ', 'khac' => 'Khác'] as $k => $v)
                                    <option value="{{ $k }}" @selected(old('gioi_tinh', $nhanvien->gioi_tinh) === $k)>
                                        {{ $v }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- TRẠNG THÁI (Đồng bộ hoàn toàn) --}}
                        <div class="col-md-4">
                            <label class="form-label">Trạng thái</label>

                            @php
                                // Đồng bộ hoàn toàn như create/index
                                $statusLabels = [
                                    'active' => 'Đang làm',
                                    'inactive' => 'Ngừng hoạt động',
                                ];
                            @endphp

                            <select name="trang_thai" class="form-select">
                                @foreach ($statusLabels as $key => $label)
                                    <option value="{{ $key }}" @selected(old('trang_thai', $nhanvien->trang_thai) === $key)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- USER RELATED --}}
                        @if ($nhanvien->user)
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <strong>🔐 Tài khoản liên kết:</strong>
                                    {{ $nhanvien->user->email }}
                                    ({{ $nhanvien->user->name }})
                                </div>
                            </div>
                        @endif

                        {{-- AVATAR --}}
                        <div class="col-md-6">
                            <label class="form-label">Ảnh đại diện</label>
                            <input type="file" name="avatar" class="form-control" accept="image/*">

                            @if ($nhanvien->avatar_path)
                                <div class="mt-2">
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($nhanvien->avatar_path) }}" alt="avatar" style="max-width:140px;border-radius:8px;object-fit:cover;">
                                </div>
                                <small class="text-muted d-block mt-1">
                                    Hiện tại: {{ basename($nhanvien->avatar_path) }}
                                </small>
                            @endif
                        </div>

                        {{-- BUTTONS --}}
                        <div class="col-12 mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Cập nhật
                            </button>

                            <a href="{{ route('admin.nhanvien.index') }}" class="btn btn-outline-secondary">
                                Hủy
                            </a>
                        </div>

                    </div>

                </form>

            </div>
        </div>

    </div>

@endsection
