@extends('layouts.admin')

@section('title', 'Gửi Thông Báo Hệ Thống')

@section('content')
    <div class="container-fluid">
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
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <form action="{{ route('admin.notifications.send.store') }}" method="POST">
                            @csrf

                            <div class="row">
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

                            <div class="row mt-4">
                                <div class="col-12 text-right">
                                    <a href="{{ url()->previous() }}" class="btn btn-secondary mr-2">
                                        <i class="fas fa-arrow-left"></i> Quay lại
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4 py-2">
                                        <i class="fas fa-paper-plane"></i> Gửi Ngay
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script: Giữ nguyên logic, thêm DOMContentLoaded để an toàn hơn --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const recipientSelect = document.getElementById('recipient_type');
            const userSelectDiv = document.getElementById('user-select');

            // Hàm xử lý hiển thị
            function toggleUserSelect() {
                if (recipientSelect.value === 'specific') {
                    userSelectDiv.style.display = 'block';
                    // Thêm hiệu ứng fade-in nhẹ nếu muốn
                    userSelectDiv.style.opacity = 0;
                    setTimeout(() => userSelectDiv.style.opacity = 1, 50);
                } else {
                    userSelectDiv.style.display = 'none';
                }
            }

            // Lắng nghe sự kiện change
            recipientSelect.addEventListener('change', toggleUserSelect);

            // Chạy một lần khi load trang (đề phòng trường hợp trình duyệt lưu cache giá trị select)
            toggleUserSelect();
        });
    </script>

    {{-- Thêm style nội bộ nhỏ để đảm bảo hiển thị đúng --}}
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
    </style>

@endsection
