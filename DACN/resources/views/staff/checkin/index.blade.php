@extends('layouts.staff')

@section('title', 'Check-in Bệnh Nhân')

@section('content')
{{-- File: resources/views/staff/checkin/index.blade.php --}}
{{-- Parent: resources/views/staff/ --}}

<div class="container-fluid px-4 py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="bi bi-person-check me-2 text-primary"></i>Check-in Bệnh Nhân</h2>
            <p class="text-muted mb-0">Quản lý check-in cho lịch hẹn hôm nay</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#quickSearchModal">
                <i class="bi bi-search me-1"></i>Tìm Nhanh
            </button>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row mb-4 g-4">
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 h-100 stat-card-modern"
                 style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
                <div class="card-body text-white p-4">
                    <div class="d-flex align-items-center">
                        <div class="p-3 rounded-circle bg-white bg-opacity-25">
                            <i class="bi bi-calendar-check fs-2"></i>
                        </div>
                        <div class="ms-3">
                            <p class="text-white text-opacity-75 mb-1 small">Tổng lịch hôm nay</p>
                            <h3 class="mb-0 fw-bold">{{ $statistics['total'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 h-100 stat-card-modern"
                 style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);">
                <div class="card-body text-white p-4">
                    <div class="d-flex align-items-center">
                        <div class="p-3 rounded-circle bg-white bg-opacity-25">
                            <i class="bi bi-check-circle fs-2"></i>
                        </div>
                        <div class="ms-3">
                            <p class="text-white text-opacity-75 mb-1 small">Đã check-in</p>
                            <h3 class="mb-0 fw-bold">{{ $statistics['checked_in'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 h-100 stat-card-modern"
                 style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);">
                <div class="card-body text-white p-4">
                    <div class="d-flex align-items-center">
                        <div class="p-3 rounded-circle bg-white bg-opacity-25">
                            <i class="bi bi-hourglass-split fs-2"></i>
                        </div>
                        <div class="ms-3">
                            <p class="text-white text-opacity-75 mb-1 small">Chờ check-in</p>
                            <h3 class="mb-0 fw-bold">{{ $statistics['waiting'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 h-100 stat-card-modern"
                 style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);">
                <div class="card-body text-white p-4">
                    <div class="d-flex align-items-center">
                        <div class="p-3 rounded-circle bg-white bg-opacity-25">
                            <i class="bi bi-person-hearts fs-2"></i>
                        </div>
                        <div class="ms-3">
                            <p class="text-white text-opacity-75 mb-1 small">Đang khám</p>
                            <h3 class="mb-0 fw-bold">{{ $statistics['in_progress'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Search & Filters --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('staff.checkin.index') }}" class="row g-3">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control"
                           placeholder="Tìm theo tên, mã lịch hẹn, số điện thoại..."
                           value="{{ $search ?? '' }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i>Tìm kiếm
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Appointments Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="bi bi-list-ul me-2"></i>Danh Sách Lịch Hẹn Hôm Nay</h5>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAllCheckboxes()">
                    <i class="bi bi-check-all me-1"></i>Chọn tất cả
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            <form id="bulkCheckInForm" method="POST" action="{{ route('staff.checkin.bulk') }}">
                @csrf
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="50"><input type="checkbox" id="selectAll" class="form-check-input"></th>
                                <th>Mã Lịch Hẹn</th>
                                <th>Bệnh Nhân</th>
                                <th>Số Điện Thoại</th>
                                <th>Bác Sĩ</th>
                                <th>Dịch Vụ</th>
                                <th>Thời Gian</th>
                                <th>Trạng Thái</th>
                                <th width="150">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($appointments as $apt)
                                <tr>
                                    <td>
                                        @if($apt->trang_thai === 'Đã xác nhận')
                                            <input type="checkbox" name="appointment_ids[]" value="{{ $apt->id }}" class="form-check-input appointment-checkbox">
                                        @endif
                                    </td>
                                    <td><span class="badge bg-secondary">{{ $apt->ma_lich_hen }}</span></td>
                                    <td>
                                        <strong>{{ $apt->user->name }}</strong>
                                        @if($apt->user->ngay_sinh)
                                            <br><small class="text-muted">{{ \Carbon\Carbon::parse($apt->user->ngay_sinh)->age }} tuổi</small>
                                        @endif
                                    </td>
                                    <td>{{ $apt->user->so_dien_thoai ?? 'N/A' }}</td>
                                    <td>{{ $apt->bacSi->ho_ten }}</td>
                                    <td>{{ $apt->dichVu->ten_dich_vu }}</td>
                                    <td><i class="bi bi-clock me-1"></i>{{ $apt->thoi_gian_hen }}</td>
                                    <td>
                                        @if($apt->trang_thai === 'Đã check-in')
                                            <span class="badge bg-success">Đã check-in</span>
                                        @elseif($apt->trang_thai === 'Đang khám')
                                            <span class="badge bg-info">Đang khám</span>
                                        @else
                                            <span class="badge bg-warning">Chờ check-in</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($apt->trang_thai === 'Đã xác nhận')
                                            <form method="POST" action="{{ route('staff.checkin.checkin', $apt->id) }}" class="d-inline checkin-form-{{ $apt->id }}">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary checkin-btn-{{ $apt->id }}">
                                                    <i class="bi bi-check-circle me-1"></i>Check-in
                                                </button>
                                            </form>
                                        @elseif($apt->trang_thai === 'Đã check-in')
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i>Đã check-in</span>
                                                <form method="POST" action="{{ route('staff.queue.call_next', $apt->id) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Gọi {{ $apt->user->name }} vào khám?')">
                                                        <i class="bi bi-telephone-forward me-1"></i>Gọi vào
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-muted">Đang khám</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        Không có lịch hẹn nào hôm nay
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($appointments->isNotEmpty())
                    <div class="card-footer bg-white border-top">
                        <button type="submit" class="btn btn-primary" id="bulkCheckInBtn" style="display:none;">
                            <i class="bi bi-check-all me-1"></i>Check-in Hàng Loạt (<span id="selectedCount">0</span>)
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $appointments->links() }}
    </div>

</div>

{{-- Quick Search Modal --}}
<div class="modal fade" id="quickSearchModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-search me-2"></i>Tìm Kiếm Nhanh</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="quickSearchInput" class="form-control"
                       placeholder="Nhập mã lịch hẹn hoặc số điện thoại">
                <div id="quickSearchResult" class="mt-3"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" onclick="performQuickSearch()">Tìm Kiếm</button>
            </div>
        </div>
    </div>
</div>

<style>
.stat-card-modern {
    transition: transform 0.3s ease;
}
.stat-card-modern:hover {
    transform: translateY(-5px);
}
</style>

@push('scripts')
<script>
// ========== CHECK-IN FORMS HANDLER ==========
document.addEventListener('DOMContentLoaded', function() {
    // Handle all check-in forms
    document.querySelectorAll('[class^="checkin-form-"]').forEach(form => {
        const btn = form.querySelector('button[type="submit"]');

        // Force enable button (remove disabled if exists)
        if (btn) {
            btn.removeAttribute('disabled');
        }

        // Handle form submit
        form.addEventListener('submit', function(e) {
            const patientName = this.closest('tr').querySelector('td:nth-child(3) strong').textContent;

            if (!confirm('Xác nhận check-in cho bệnh nhân ' + patientName + '?')) {
                e.preventDefault();
                return false;
            }

            // Disable button and show loading
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Đang xử lý...';
            }
        });
    });
});

// Select all checkbox
document.getElementById('selectAll')?.addEventListener('change', function() {
    document.querySelectorAll('.appointment-checkbox').forEach(cb => cb.checked = this.checked);
    updateBulkButton();
});

function selectAllCheckboxes() {
    document.getElementById('selectAll').checked = true;
    document.getElementById('selectAll').dispatchEvent(new Event('change'));
}

// Update bulk button visibility
document.querySelectorAll('.appointment-checkbox').forEach(cb => {
    cb.addEventListener('change', updateBulkButton);
});

function updateBulkButton() {
    const checked = document.querySelectorAll('.appointment-checkbox:checked').length;
    const btn = document.getElementById('bulkCheckInBtn');
    const countSpan = document.getElementById('selectedCount');

    if (checked > 0) {
        btn.style.display = 'inline-block';
        countSpan.textContent = checked;
    } else {
        btn.style.display = 'none';
    }
}

// Quick search
function performQuickSearch() {
    const keyword = document.getElementById('quickSearchInput').value.trim();
    const resultDiv = document.getElementById('quickSearchResult');

    if (!keyword) {
        resultDiv.innerHTML = '<div class="alert alert-warning">Vui lòng nhập mã lịch hẹn hoặc số điện thoại</div>';
        return;
    }

    resultDiv.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"></div></div>';

    fetch(`{{ route('staff.checkin.quick_search') }}?keyword=${encodeURIComponent(keyword)}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                resultDiv.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
            } else {
                const apt = data.appointment;
                resultDiv.innerHTML = `
                    <div class="card border-primary">
                        <div class="card-body">
                            <h6 class="card-title">${apt.patient_name}</h6>
                            <p class="mb-1"><strong>Mã:</strong> ${apt.ma_lich_hen}</p>
                            <p class="mb-1"><strong>SĐT:</strong> ${apt.patient_phone}</p>
                            <p class="mb-1"><strong>Bác sĩ:</strong> ${apt.doctor_name}</p>
                            <p class="mb-1"><strong>Dịch vụ:</strong> ${apt.service_name}</p>
                            <p class="mb-1"><strong>Thời gian:</strong> ${apt.time}</p>
                            ${apt.can_checkin ? `
                                <form method="POST" action="{{ url('staff/checkin/checkin') }}/${apt.id}">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm mt-2">
                                        <i class="bi bi-check-circle me-1"></i>Check-in Ngay
                                    </button>
                                </form>
                            ` : '<span class="badge bg-success">Đã check-in</span>'}
                        </div>
                    </div>
                `;
            }
        })
        .catch(error => {
            resultDiv.innerHTML = '<div class="alert alert-danger">Có lỗi xảy ra. Vui lòng thử lại!</div>';
        });
}

// Enter key for quick search
document.getElementById('quickSearchInput')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') performQuickSearch();
});
</script>
@endpush

@endsection
