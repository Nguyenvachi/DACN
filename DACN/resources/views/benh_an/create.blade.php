@php
    $role = auth()->user()->role ?? 'patient';
    $layout = match ($role) {
        'admin' => 'layouts.admin',
        'doctor' => 'layouts.doctor',
        'staff' => 'layouts.staff',
        'patient' => 'layouts.patient-modern',
        default => 'layouts.app',
    };
@endphp

@extends($layout)

@section('title', 'Tạo bệnh án mới')

@section('content')
    <div class="container-fluid py-4">
        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1 fw-bold" style="color: #1f2937;">
                    <i class="fas fa-file-medical-alt me-2" style="color: #10b981;"></i>
                    Tạo bệnh án mới
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route($role . '.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route($role . '.benhan.index') }}">Bệnh án</a></li>
                        <li class="breadcrumb-item active">Tạo mới</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route($role . '.benhan.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>

        {{-- ERROR MESSAGES --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Vui lòng kiểm tra lại thông tin:
                </h6>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" enctype="multipart/form-data"
            action="{{ route($role === 'admin' ? 'admin.benhan.store' : 'doctor.benhan.store') }}" id="benhAnForm">
            @csrf

            <div class="row g-4">
                {{-- LEFT COLUMN: Main Information --}}
                <div class="col-lg-8">
                    {{-- PATIENT & APPOINTMENT INFO --}}
                    <div class="card vc-card mb-4">
                        <div class="card-header bg-gradient-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-user-injured me-2"></i>
                                Thông tin bệnh nhân & Lịch hẹn
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                {{-- Bệnh nhân --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold required">
                                        <i class="fas fa-user me-1" style="color: #10b981;"></i>
                                        Bệnh nhân
                                    </label>
                                    <select name="user_id" id="user_id" class="form-select" required>
                                        <option value="">-- Chọn bệnh nhân --</option>
                                        @foreach ($patients as $p)
                                            <option value="{{ $p->id }}"
                                                data-phone="{{ $p->so_dien_thoai ?? 'N/A' }}"
                                                data-dob="{{ $p->ngay_sinh ? \Carbon\Carbon::parse($p->ngay_sinh)->format('d/m/Y') : 'N/A' }}"
                                                data-gender="{{ $p->gioi_tinh ?? 'N/A' }}" @selected(old('user_id', request('user_id')) == $p->id)>
                                                {{ $p->name }} - {{ $p->email }}
                                            </option>
                                        @endforeach
                                    </select>
                                    {{-- Patient Info Preview --}}
                                    <div id="patientInfoPreview" class="mt-2 d-none">
                                        <div class="p-3 bg-light rounded border">
                                            <div class="row g-2 small">
                                                <div class="col-6">
                                                    <strong>SĐT:</strong> <span id="preview_phone"></span>
                                                </div>
                                                <div class="col-6">
                                                    <strong>Ngày sinh:</strong> <span id="preview_dob"></span>
                                                </div>
                                                <div class="col-6">
                                                    <strong>Giới tính:</strong> <span id="preview_gender"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Lịch hẹn --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-calendar-check me-1" style="color: #3b82f6;"></i>
                                        Lịch hẹn (nếu có)
                                    </label>
                                    <select name="lich_hen_id" id="lich_hen_id" class="form-select">
                                        <option value="">-- Không chọn (khám không hẹn) --</option>
                                        @foreach ($appointments as $a)
                                                @php
                                                    // Đảm bảo chỉ lấy phần date từ `ngay_hen` rồi ghép với `thoi_gian_hen`
                                                    $dateOnly = \Carbon\Carbon::parse($a->ngay_hen)->format('Y-m-d');
                                                    $combined = $dateOnly . ' ' . ($a->thoi_gian_hen ?? '00:00:00');
                                                    $formattedDatetime = \Carbon\Carbon::parse($combined)->format('d/m/Y H:i');
                                                @endphp
                                                <option value="{{ $a->id }}"
                                                    data-service="{{ $a->dichVu->ten_dich_vu ?? 'N/A' }}"
                                                    data-datetime="{{ $formattedDatetime }}"
                                                    data-status="{{ $a->trang_thai }}" @selected(old('lich_hen_id', request('lich_hen_id')) == $a->id)>
                                                #{{ $a->id }} —
                                                {{ \Carbon\Carbon::parse($a->ngay_hen)->format('d/m/Y') }}
                                                {{ $a->thoi_gian_hen }}
                                                ({{ $a->trang_thai }})
                                            </option>
                                        @endforeach
                                    </select>
                                    {{-- Appointment Info Preview --}}
                                    <div id="appointmentInfoPreview" class="mt-2 d-none">
                                        <div class="p-3 bg-info-light rounded border border-info">
                                            <div class="row g-2 small">
                                                <div class="col-12">
                                                    <strong>Dịch vụ:</strong> <span id="preview_service"
                                                        class="badge bg-primary"></span>
                                                </div>
                                                <div class="col-12">
                                                    <strong>Thời gian:</strong> <span id="preview_datetime"></span>
                                                </div>
                                                <div class="col-12">
                                                    <strong>Trạng thái:</strong> <span id="preview_status"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Bác sĩ --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold required">
                                        <i class="fas fa-user-md me-1" style="color: #8b5cf6;"></i>
                                        Bác sĩ điều trị
                                    </label>
                                    @if ($doctorId)
                                        <input type="hidden" name="bac_si_id" value="{{ $doctorId }}">
                                        <input type="text" class="form-control"
                                            value="Bác sĩ đang đăng nhập (ID: {{ $doctorId }})" readonly>
                                        <small class="text-muted">Tự động điền theo bác sĩ đăng nhập</small>
                                    @else
                                        <input type="number" name="bac_si_id" class="form-control"
                                            value="{{ old('bac_si_id') }}" placeholder="Nhập ID bác sĩ" required>
                                        <small class="text-muted">Nhập ID bác sĩ phụ trách khám</small>
                                    @endif
                                </div>

                                {{-- Ngày khám --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold required">
                                        <i class="fas fa-calendar-day me-1" style="color: #f59e0b;"></i>
                                        Ngày khám
                                    </label>
                                    <input type="date" name="ngay_kham" class="form-control"
                                        value="{{ old('ngay_kham', now()->format('Y-m-d')) }}"
                                        max="{{ now()->format('Y-m-d') }}" required>
                                    <small class="text-muted">Ngày thực tế khám bệnh</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- CLINICAL INFORMATION --}}
                    <div class="card vc-card mb-4">
                        <div class="card-header bg-gradient-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-notes-medical me-2"></i>
                                Thông tin lâm sàng
                            </h5>
                        </div>
                        <div class="card-body">
                            {{-- Tiêu đề --}}
                            <div class="mb-4">
                                <label class="form-label fw-semibold required">
                                    <i class="fas fa-heading me-1"></i>
                                    Tiêu đề bệnh án
                                </label>
                                <input type="text" name="tieu_de" class="form-control form-control-lg"
                                    value="{{ old('tieu_de') }}"
                                    placeholder="VD: Khám tổng quát, Khám tiêu hóa, Khám nội khoa..." required>
                                <small class="text-muted">Tên mô tả ngắn gọn về ca khám</small>
                            </div>

                            {{-- 3 cột nội dung chính --}}
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-thermometer-half me-1" style="color: #ef4444;"></i>
                                        Triệu chứng
                                    </label>
                                    <textarea name="trieu_chung" class="form-control" rows="8"
                                        placeholder="Mô tả triệu chứng bệnh nhân than phiền...

VD:
- Đau đầu, chóng mặt
- Sốt nhẹ 38°C
- Ho khan kéo dài 3 ngày">{{ old('trieu_chung') }}</textarea>
                                    <small class="text-muted">Các biểu hiện bệnh nhân trình bày</small>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-stethoscope me-1" style="color: #10b981;"></i>
                                        Chẩn đoán
                                    </label>
                                    <textarea name="chuan_doan" class="form-control" rows="8"
                                        placeholder="Kết luận chẩn đoán của bác sĩ...

VD:
- Viêm đường hô hấp trên
- ICD-10: J06.9">{{ old('chuan_doan') }}</textarea>
                                    <small class="text-muted">Kết luận bệnh và mã ICD (nếu có)</small>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-pills me-1" style="color: #3b82f6;"></i>
                                        Điều trị & Hướng dẫn
                                    </label>
                                    <textarea name="dieu_tri" class="form-control" rows="8"
                                        placeholder="Phương pháp điều trị và hướng dẫn cho bệnh nhân...

VD:
- Uống thuốc theo đơn
- Nghỉ ngơi, uống nhiều nước
- Tái khám sau 3 ngày nếu không đỡ">{{ old('dieu_tri') }}</textarea>
                                    <small class="text-muted">Phác đồ và lời dặn bệnh nhân</small>
                                </div>
                            </div>

                            {{-- Ghi chú bổ sung --}}
                            <div class="mt-4">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-comment-medical me-1" style="color: #6b7280;"></i>
                                    Ghi chú bổ sung (nếu có)
                                </label>
                                <textarea name="ghi_chu" class="form-control" rows="3" placeholder="Các thông tin khác cần lưu ý...">{{ old('ghi_chu') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- FILE ATTACHMENTS --}}
                    <div class="card vc-card">
                        <div class="card-header bg-gradient-info text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-paperclip me-2"></i>
                                Tệp đính kèm
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-upload me-1"></i>
                                    Upload tệp (ảnh, PDF, tài liệu...)
                                </label>
                                <input type="file" name="files[]" multiple class="form-control"
                                    accept="image/*,.pdf,.doc,.docx" id="fileInput">
                                <small class="text-muted">
                                    Hỗ trợ: JPG, PNG, PDF, Word. Tối đa 8MB/file. Có thể chọn nhiều file.
                                </small>
                            </div>

                            {{-- File preview area --}}
                            <div id="filePreview" class="mt-3"></div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN: Guidelines & Actions --}}
                <div class="col-lg-4">
                    {{-- WORKFLOW GUIDE --}}
                    <div class="card vc-card mb-4 border-primary">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-route me-2"></i>
                                Quy trình khám bệnh
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="workflow-steps">
                                <div class="workflow-step completed">
                                    <div class="step-icon">1</div>
                                    <div class="step-content">
                                        <div class="step-title">Đặt lịch hẹn</div>
                                        <small class="text-muted">Bệnh nhân đặt lịch trực tuyến</small>
                                    </div>
                                </div>
                                <div class="workflow-step completed">
                                    <div class="step-icon">2</div>
                                    <div class="step-content">
                                        <div class="step-title">Xác nhận lịch</div>
                                        <small class="text-muted">Nhân viên/Hệ thống xác nhận</small>
                                    </div>
                                </div>
                                <div class="workflow-step completed">
                                    <div class="step-icon">3</div>
                                    <div class="step-content">
                                        <div class="step-title">Check-in</div>
                                        <small class="text-muted">Bệnh nhân đến và check-in</small>
                                    </div>
                                </div>
                                <div class="workflow-step active">
                                    <div class="step-icon"><i class="fas fa-arrow-right"></i></div>
                                    <div class="step-content">
                                        <div class="step-title fw-bold">Tạo bệnh án</div>
                                        <small class="text-primary">Bạn đang ở bước này</small>
                                    </div>
                                </div>
                                <div class="workflow-step">
                                    <div class="step-icon">5</div>
                                    <div class="step-content">
                                        <div class="step-title">Kê đơn thuốc</div>
                                        <small class="text-muted">Sau khi lưu bệnh án</small>
                                    </div>
                                </div>
                                <div class="workflow-step">
                                    <div class="step-icon">6</div>
                                    <div class="step-content">
                                        <div class="step-title">Chỉ định xét nghiệm</div>
                                        <small class="text-muted">Nếu cần thiết</small>
                                    </div>
                                </div>
                                <div class="workflow-step">
                                    <div class="step-icon">7</div>
                                    <div class="step-content">
                                        <div class="step-title">Hoàn thành khám</div>
                                        <small class="text-muted">Tạo hóa đơn thanh toán</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- QUICK TIPS --}}
                    <div class="card vc-card mb-4 border-warning">
                        <div class="card-header bg-warning bg-opacity-10">
                            <h6 class="mb-0">
                                <i class="fas fa-lightbulb me-2 text-warning"></i>
                                Lưu ý quan trọng
                            </h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0 small">
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Chọn lịch hẹn nếu bệnh nhân đã đặt trước
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Điền đầy đủ triệu chứng và chẩn đoán
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Sau khi lưu có thể kê đơn thuốc và XN
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Upload hình ảnh/tài liệu nếu cần
                                </li>
                                <li class="mb-0">
                                    <i class="fas fa-info-circle text-info me-2"></i>
                                    Bệnh án có thể chỉnh sửa sau khi lưu
                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- ACTIONS --}}
                    <div class="card vc-card border-success">
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-save me-2"></i>Lưu bệnh án
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="confirmCancel()">
                                    <i class="fas fa-times me-2"></i>Hủy bỏ
                                </button>
                            </div>

                            <hr class="my-3">

                            <div class="text-center small text-muted">
                                <i class="fas fa-shield-alt me-1"></i>
                                Thông tin được bảo mật theo quy định
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <style>
        /* VietCare Design System Styles */
        .bg-gradient-primary {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #06b6d4, #0891b2);
        }

        .bg-info-light {
            background-color: #e0f2fe !important;
        }

        .required::after {
            content: " *";
            color: #ef4444;
        }

        /* Workflow Steps */
        .workflow-steps {
            padding: 1rem 0;
        }

        .workflow-step {
            display: flex;
            padding: 0.75rem 1rem;
            border-left: 3px solid #e5e7eb;
            margin-left: 1rem;
            position: relative;
        }

        .workflow-step.completed {
            border-left-color: #10b981;
        }

        .workflow-step.completed .step-icon {
            background: #10b981;
            color: white;
        }

        .workflow-step.active {
            border-left-color: #3b82f6;
            background: #eff6ff;
        }

        .workflow-step.active .step-icon {
            background: #3b82f6;
            color: white;
            animation: pulse 2s ease-in-out infinite;
        }

        .workflow-step .step-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #e5e7eb;
            color: #6b7280;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.875rem;
            flex-shrink: 0;
            position: absolute;
            left: -18px;
        }

        .workflow-step .step-content {
            margin-left: 2rem;
        }

        .workflow-step .step-title {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.125rem;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        /* Form enhancements */
        .form-control:focus,
        .form-select:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.25);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Patient selection handler
            const userSelect = document.getElementById('user_id');
            const patientPreview = document.getElementById('patientInfoPreview');

            if (userSelect) {
                userSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    if (this.value) {
                        document.getElementById('preview_phone').textContent = selectedOption.dataset.phone;
                        document.getElementById('preview_dob').textContent = selectedOption.dataset.dob;
                        document.getElementById('preview_gender').textContent = selectedOption.dataset
                            .gender;
                        patientPreview.classList.remove('d-none');
                    } else {
                        patientPreview.classList.add('d-none');
                    }
                });

                // Trigger if pre-selected
                if (userSelect.value) {
                    userSelect.dispatchEvent(new Event('change'));
                }
            }

            // Appointment selection handler
            const apptSelect = document.getElementById('lich_hen_id');
            const apptPreview = document.getElementById('appointmentInfoPreview');

            if (apptSelect) {
                apptSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    if (this.value) {
                        document.getElementById('preview_service').textContent = selectedOption.dataset
                            .service;
                        document.getElementById('preview_datetime').textContent = selectedOption.dataset
                            .datetime;

                        const status = selectedOption.dataset.status;
                        const statusBadge = document.getElementById('preview_status');
                        statusBadge.className = 'badge bg-' + getStatusColor(status);
                        statusBadge.textContent = status;

                        apptPreview.classList.remove('d-none');
                    } else {
                        apptPreview.classList.add('d-none');
                    }
                });

                // Trigger if pre-selected
                if (apptSelect.value) {
                    apptSelect.dispatchEvent(new Event('change'));
                }
            }

            // File preview
            const fileInput = document.getElementById('fileInput');
            const filePreview = document.getElementById('filePreview');

            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    filePreview.innerHTML = '';
                    const files = Array.from(this.files);

                    if (files.length > 0) {
                        const previewHtml = files.map((file, index) => `
                    <div class="alert alert-light d-flex align-items-center mb-2">
                        <i class="fas fa-file-${getFileIcon(file.type)} me-2 text-primary"></i>
                        <div class="flex-grow-1">
                            <strong>${file.name}</strong>
                            <small class="text-muted d-block">${formatFileSize(file.size)}</small>
                        </div>
                        <button type="button" class="btn-close" onclick="removeFile(${index})"></button>
                    </div>
                `).join('');
                        filePreview.innerHTML = previewHtml;
                    }
                });
            }
        });

        function getStatusColor(status) {
            const colors = {
                '{{ \App\Models\LichHen::STATUS_PENDING_VN }}': 'warning',
                '{{ \App\Models\LichHen::STATUS_CONFIRMED_VN }}': 'info',
                '{{ \App\Models\LichHen::STATUS_CHECKED_IN_VN }}': 'primary',
                '{{ \App\Models\LichHen::STATUS_IN_PROGRESS_VN }}': 'success',
                '{{ \App\Models\LichHen::STATUS_COMPLETED_VN }}': 'success',
                '{{ \App\Models\LichHen::STATUS_CANCELLED_VN }}': 'danger'
            };
            return colors[status] || 'secondary';
        }

        function getFileIcon(mimeType) {
            if (mimeType.startsWith('image/')) return 'image';
            if (mimeType.includes('pdf')) return 'pdf';
            if (mimeType.includes('word')) return 'word';
            return 'alt';
        }

        function formatFileSize(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
        }

        function removeFile(index) {
            const fileInput = document.getElementById('fileInput');
            const dt = new DataTransfer();
            const files = Array.from(fileInput.files);
            files.splice(index, 1);
            files.forEach(file => dt.items.add(file));
            fileInput.files = dt.files;
            fileInput.dispatchEvent(new Event('change'));
        }

        function confirmCancel() {
            if (confirm('Bạn có chắc muốn hủy? Các thông tin đã nhập sẽ không được lưu.')) {
                window.history.back();
            }
        }

        // Form validation
        document.getElementById('benhAnForm')?.addEventListener('submit', function(e) {
            const userId = document.getElementById('user_id').value;
            if (!userId) {
                e.preventDefault();
                alert('Vui lòng chọn bệnh nhân!');
                return false;
            }
        });
    </script>
@endsection
