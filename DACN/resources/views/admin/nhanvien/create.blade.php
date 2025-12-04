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

        .note {
            font-size: 14px;
        }
    </style>

    <div class="container-fluid py-4">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">
                    <i class="bi bi-person-plus me-2 text-primary"></i>
                    Thêm nhân viên
                </h2>
                <small class="text-muted">Tạo thông tin nhân viên và tự động tạo tài khoản đăng nhập</small>
            </div>

            <a href="{{ route('admin.nhanvien.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Quay lại danh sách
            </a>
        </div>

        {{-- FORM CARD --}}
        <div class="card card-custom">
            <div class="card-body">

                {{-- LƯU Ý --}}
                <div class="alert alert-info">
                    <strong>📌 Lưu ý:</strong>
                    Hệ thống sẽ <strong>tự động tạo tài khoản đăng nhập</strong> cho nhân viên và gửi email hướng dẫn đặt
                    mật khẩu đến
                    <strong>Email công việc</strong>.
                </div>

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
                <form method="POST" action="{{ route('admin.nhanvien.store') }}" enctype="multipart/form-data" novalidate>
                    @csrf

                    <div class="row g-3">

                        {{-- Họ tên --}}
                        <div class="col-md-6">
                            <label class="form-label">Họ tên <span class="text-danger">*</span></label>
                            <input name="ho_ten" class="form-control" placeholder="Nguyễn Văn A" required
                                value="{{ old('ho_ten') }}">
                            @error('ho_ten')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Chức vụ --}}
                        <div class="col-md-6">
                            <label class="form-label">Chức vụ</label>
                            <input name="chuc_vu" class="form-control" placeholder="Lễ tân, Điều dưỡng..."
                                value="{{ old('chuc_vu') }}">
                            @error('chuc_vu')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Số điện thoại --}}
                        <div class="col-md-6">
                            <label class="form-label">Số điện thoại</label>
                            <input name="so_dien_thoai" class="form-control" placeholder="0987654321"
                                value="{{ old('so_dien_thoai') }}">
                            @error('so_dien_thoai')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Email công việc --}}
                        <div class="col-md-6">
                            <label class="form-label">Email công việc (đăng nhập) <span class="text-danger">*</span></label>
                            <input type="email" name="email_cong_viec" class="form-control"
                                placeholder="nhanvien@phongkham.com" required value="{{ old('email_cong_viec') }}">
                            @error('email_cong_viec')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <div class="form-text">
                                Email này sẽ dùng để đăng nhập hệ thống. Hệ thống sẽ gửi email đặt mật khẩu tự động.
                            </div>
                        </div>

                        {{-- Ngày sinh --}}
                        <div class="col-md-4">
                            <label class="form-label">Ngày sinh</label>
                            <input type="date" name="ngay_sinh" class="form-control" value="{{ old('ngay_sinh') }}">
                            @error('ngay_sinh')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Giới tính --}}
                        <div class="col-md-4">
                            <label class="form-label">Giới tính</label>
                            <select name="gioi_tinh" class="form-select">
                                <option value="">-- Chọn --</option>
                                <option value="nam" {{ old('gioi_tinh') == 'nam' ? 'selected' : '' }}>Nam</option>
                                <option value="nu" {{ old('gioi_tinh') == 'nu' ? 'selected' : '' }}>Nữ</option>
                                <option value="khac" {{ old('gioi_tinh') == 'khac' ? 'selected' : '' }}>Khác</option>
                            </select>
                            @error('gioi_tinh')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Avatar --}}
                        <div class="col-md-4">
                            <label class="form-label">Ảnh đại diện</label>
                            <input type="file" name="avatar" class="form-control" accept="image/*">
                            @error('avatar')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <div class="form-text">Tối đa 2MB. Định dạng: jpg, png.</div>
                        </div>

                        {{-- TRẠNG THÁI (ĐỒNG BỘ HOÀN TOÀN VỚI DB + INDEX) --}}
                        <div class="col-md-4">
                            <label class="form-label">Trạng thái</label>

                            @php
                                $statusLabels = [
                                    'active' => 'Đang làm',
                                    'inactive' => 'Ngừng hoạt động',
                                ];
                            @endphp

                            <select name="trang_thai" class="form-select">
                                @foreach ($statusLabels as $key => $label)
                                    <option value="{{ $key }}" @selected(old('trang_thai', 'active') == $key)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>

                            <div class="form-text">
                                Trạng thái này xác định nhân viên có thể đăng nhập hệ thống hay không.
                            </div>
                        </div>

                        {{-- BUTTONS --}}
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i>
                                    Lưu và tạo tài khoản
                                </button>

                                <a class="btn btn-outline-secondary" href="{{ route('admin.nhanvien.index') }}">
                                    Hủy
                                </a>
                            </div>
                        </div>

                    </div>
                </form>

            </div>
        </div>

    </div>

@endsection
