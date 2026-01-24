@extends('layouts.admin')

@section('title', 'Gửi Thông Báo Hệ Thống')

@section('content')
    <div class="container-fluid">
        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">Quản lý thông báo</a></li>
                <li class="breadcrumb-item active" aria-current="page">Soạn thông báo mới</li>
            </ol>
        </nav>

        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-paper-plane mr-2"></i> Gửi Thông Báo Mới</h5>
                        <small>Soạn tin nhắn gửi đến các thành viên trong hệ thống</small>
                    </div>

                    <div class="card-body">
                        {{-- Alert Success --}}
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        {{-- Form --}}
                        <form action="{{ route('admin.notifications.send.store') }}" method="POST"
                            id="send-notification-form">
                            @csrf

                            <div class="row">
                                {{-- Cột Trái: Chọn đối tượng --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">
                                            <i class="fas fa-users mr-1"></i> Đối tượng nhận:
                                        </label>
                                        <select name="recipient_type" id="recipient_type" class="form-control custom-select"
                                            required>
                                            <option value="all">📢 Tất cả thành viên</option>
                                            <option value="patients">🤰 Bệnh nhân</option>
                                            <option value="doctors">👨‍⚕️ Bác sĩ</option>
                                            <option value="staff">🏥 Nhân viên phòng khám</option>
                                            <option value="specific">👤 Người dùng cụ thể</option>
                                        </select>
                                        <small class="form-text text-muted">Chọn nhóm đối tượng sẽ nhận được thông báo
                                            này.</small>
                                    </div>

                                    {{-- Select User (Ẩn/Hiện) --}}
                                    <div class="form-group" id="user-select"
                                        style="display:none; transition: all 0.3s ease;">
                                        <label class="font-weight-bold text-primary">
                                            <i class="fas fa-user-check mr-1"></i> Chọn người dùng:
                                        </label>
                                        <select name="user_id" class="form-control">
                                            <option value="">-- Chọn người nhận --</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">
                                                    {{ $user->name }} ({{ $user->email ?? 'N/A' }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">Tìm và chọn tên người nhận trong danh
                                            sách.</small>
                                    </div>
                                </div>

                                {{-- Cột Phải: Nội dung --}}
                                <div class="col-md-8 border-left">
                                    <div class="form-group">
                                        <label class="font-weight-bold">
                                            <i class="fas fa-heading mr-1"></i> Tiêu đề thông báo:
                                        </label>
                                        <input type="text" name="title" class="form-control form-control-lg"
                                            placeholder="Ví dụ: Lịch nghỉ lễ, Thay đổi giờ khám..." required>
                                    </div>

                                    <div class="form-group">
                                        <label class="font-weight-bold">
                                            <i class="fas fa-edit mr-1"></i> Nội dung chi tiết:
                                        </label>
                                        <textarea name="message" class="form-control" rows="8" placeholder="Nhập nội dung thông báo chi tiết tại đây..."
                                            required style="resize: none;"></textarea>
                                    </div>
                                </div>
                            </div>

                            {{-- Footer Buttons --}}
                            <div class="row mt-4">
                                <div class="col-12 text-right">
                                    <a href="{{ url()->previous() }}" class="btn btn-secondary mr-2">
                                        <i class="fas fa-arrow-left"></i> Quay lại
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4 py-2" id="btn-submit">
                                        <span class="btn-text"><i class="fas fa-paper-plane"></i> Gửi Ngay</span>
                                        <span class="btn-loading d-none"><i class="fas fa-spinner fa-spin"></i> Đang
                                            gửi...</span>
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script: Xử lý UX --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const recipientSelect = document.getElementById('recipient_type');
            const userSelectDiv = document.getElementById('user-select');
            const form = document.getElementById('send-notification-form');
            const btnSubmit = document.getElementById('btn-submit');

            // 1. Hàm xử lý hiển thị Select User
            function toggleUserSelect() {
                if (recipientSelect.value === 'specific') {
                    // Hiệu ứng Fade In
                    userSelectDiv.style.display = 'block';
                    userSelectDiv.style.opacity = 0;
                    setTimeout(() => userSelectDiv.style.opacity = 1, 50);
                    // Required field khi chọn specific
                    userSelectDiv.querySelector('select').setAttribute('required', 'required');
                } else {
                    userSelectDiv.style.display = 'none';
                    // Bỏ required để không bị lỗi form
                    userSelectDiv.querySelector('select').removeAttribute('required');
                }
            }

            // Lắng nghe sự kiện change
            recipientSelect.addEventListener('change', toggleUserSelect);

            // Chạy một lần khi load trang
            toggleUserSelect();

            // 2. Xử lý nút Submit (Tránh double click)
            form.addEventListener('submit', function() {
                const btnText = btnSubmit.querySelector('.btn-text');
                const btnLoading = btnSubmit.querySelector('.btn-loading');

                // Disable nút và hiện loading
                btnSubmit.disabled = true;
                btnSubmit.classList.add('disabled');
                btnText.classList.add('d-none');
                btnLoading.classList.remove('d-none');
            });
        });
    </script>

    {{-- Style nội bộ --}}
    <style>
        .card {
            border-radius: 10px;
            overflow: hidden;
        }

        .card-header {
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            border-color: #80bdff;
        }

        #user-select {
            transition: opacity 0.3s ease-in-out;
        }

        /* Loading button style */
        .btn-loading {
            font-weight: 600;
        }
    </style>

@endsection
