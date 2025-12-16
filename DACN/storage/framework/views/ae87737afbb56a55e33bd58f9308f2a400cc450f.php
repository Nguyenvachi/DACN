

<?php $__env->startSection('title', 'Check-in Bệnh Nhân'); ?>

<?php $__env->startSection('content'); ?>



<div class="container-fluid px-4 py-4">

    
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

    
    <div class="row mb-4 g-4">
        <div class="col-lg-4 col-md-4">
            <div class="card border-0 h-100 stat-card-modern"
                 style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
                <div class="card-body text-white p-4">
                    <div class="d-flex align-items-center">
                        <div class="p-3 rounded-circle bg-white bg-opacity-25">
                            <i class="bi bi-calendar-check fs-2"></i>
                        </div>
                        <div class="ms-3">
                            <p class="text-white text-opacity-75 mb-1 small">Tổng lịch hôm nay</p>
                            <h3 class="mb-0 fw-bold"><?php echo e($statistics['total']); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-4">
            <div class="card border-0 h-100 stat-card-modern"
                 style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);">
                <div class="card-body text-white p-4">
                    <div class="d-flex align-items-center">
                        <div class="p-3 rounded-circle bg-white bg-opacity-25">
                            <i class="bi bi-hourglass-split fs-2"></i>
                        </div>
                        <div class="ms-3">
                            <p class="text-white text-opacity-75 mb-1 small">Chưa check-in</p>
                            <h3 class="mb-0 fw-bold"><?php echo e($statistics['waiting']); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-4">
            <div class="card border-0 h-100 stat-card-modern"
                 style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);">
                <div class="card-body text-white p-4">
                    <div class="d-flex align-items-center">
                        <div class="p-3 rounded-circle bg-white bg-opacity-25">
                            <i class="bi bi-check-circle fs-2"></i>
                        </div>
                        <div class="ms-3">
                            <p class="text-white text-opacity-75 mb-1 small">Đã check-in</p>
                            <h3 class="mb-0 fw-bold"><?php echo e($statistics['checked_in']); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i><?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('staff.checkin.index')); ?>" class="row g-3">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control"
                           placeholder="Tìm theo tên, mã lịch hẹn, số điện thoại..."
                           value="<?php echo e($search ?? ''); ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i>Tìm kiếm
                    </button>
                </div>
            </form>
        </div>
    </div>

    
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 fw-bold"><i class="bi bi-list-ul me-2"></i>Danh Sách Lịch Hẹn Hôm Nay</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Mã Lịch Hẹn</th>
                                <th>Bệnh Nhân</th>
                                <th>Số Điện Thoại</th>
                                <th>Bác Sĩ</th>
                                <th>Phòng</th>
                                <th>Dịch Vụ</th>
                                <th>Thời Gian</th>
                                <th>STT Khám</th>
                                <th>Trạng Thái</th>
                                <th width="150">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $apt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><span class="badge bg-secondary">LH-<?php echo e(str_pad($apt->id, 4, '0', STR_PAD_LEFT)); ?></span></td>
                                    <td>
                                        <strong><?php echo e($apt->user->name); ?></strong>
                                        <?php if($apt->user->ngay_sinh): ?>
                                            <br><small class="text-muted"><?php echo e(\Carbon\Carbon::parse($apt->user->ngay_sinh)->age); ?> tuổi</small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($apt->user->so_dien_thoai ?? 'N/A'); ?></td>
                                    <td><?php echo e($apt->bacSi->ho_ten); ?></td>
                                    <td>
                                        <?php if($apt->bacSi->phong): ?>
                                            <span class="badge bg-secondary" title="<?php echo e($apt->bacSi->phong->mo_ta); ?>">
                                                <i class="bi bi-door-closed me-1"></i><?php echo e($apt->bacSi->phong->ten); ?>

                                            </span>
                                        <?php elseif($apt->bacSi->so_phong): ?>
                                            <span class="badge bg-secondary">
                                                <i class="bi bi-door-closed me-1"></i><?php echo e($apt->bacSi->so_phong); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($apt->dichVu->ten_dich_vu); ?></td>
                                    <td><i class="bi bi-clock me-1"></i><?php echo e($apt->thoi_gian_hen); ?></td>
                                    <td>
                                        <?php if($apt->stt_kham): ?>
                                            <span class="badge bg-info fs-6">
                                                <i class="bi bi-hash me-1"></i><?php echo e($apt->stt_kham); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($apt->trang_thai === \App\Models\LichHen::STATUS_CHECKED_IN_VN): ?>
                                            <span class="badge bg-success">Đã check-in</span>
                                        <?php elseif($apt->trang_thai === \App\Models\LichHen::STATUS_IN_PROGRESS_VN): ?>
                                            <span class="badge bg-success">Đã check-in</span>
                                        <?php elseif($apt->trang_thai === \App\Models\LichHen::STATUS_CONFIRMED_VN): ?>
                                            <span class="badge bg-warning">Chưa check-in</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">Chưa check-in</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($apt->trang_thai === \App\Models\LichHen::STATUS_CONFIRMED_VN): ?>
                                            <button type="button" class="btn btn-sm btn-primary" 
                                                    onclick="if(confirm('Xác nhận check-in cho bệnh nhân <?php echo e($apt->user->name); ?>?')) directCheckIn(<?php echo e($apt->id); ?>)">
                                                <i class="bi bi-check-circle me-1"></i>Check-in
                                            </button>
                                        <?php elseif($apt->trang_thai === \App\Models\LichHen::STATUS_CHECKED_IN_VN): ?>
                                            <span class="text-success">
                                                <i class="bi bi-check-circle-fill me-1"></i>Đã check-in
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="10" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        Không có lịch hẹn nào hôm nay
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
            </div>
        </div>
    </div>

    
    <div class="mt-4">
        <?php echo e($appointments->links()); ?>

    </div>

</div>


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

<?php $__env->startPush('scripts'); ?>
<script>
// Quick search
function performQuickSearch() {
    const keyword = document.getElementById('quickSearchInput').value.trim();
    const resultDiv = document.getElementById('quickSearchResult');

    if (!keyword) {
        resultDiv.innerHTML = '<div class="alert alert-warning">Vui lòng nhập mã lịch hẹn hoặc số điện thoại</div>';
        return;
    }

    resultDiv.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"></div></div>';

    fetch(`<?php echo e(route('staff.checkin.quick_search')); ?>?keyword=${encodeURIComponent(keyword)}`)
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
                                <form method="POST" action="<?php echo e(url('staff/checkin/checkin')); ?>/${apt.id}">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-primary btn-sm mt-2">
                                        <i class="bi bi-check-circle me-1"></i>Check-in Ngay
                                    </button>
                                </form>
                                        ` : '<span class="badge bg-success"><?php echo e(\App\Models\LichHen::STATUS_CHECKED_IN_VN); ?></span>'}
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

// Direct check-in function
function directCheckIn(appointmentId) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?php echo e(url("staff/checkin/checkin")); ?>/' + appointmentId;
    
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = '<?php echo e(csrf_token()); ?>';
    
    form.appendChild(csrfInput);
    document.body.appendChild(form);
    form.submit();
}
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.staff', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\WORKING\DACN\DACN\resources\views/staff/checkin/index.blade.php ENDPATH**/ ?>