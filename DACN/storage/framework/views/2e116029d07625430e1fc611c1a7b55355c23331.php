

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-1" style="color: var(--vc-dark);">
                        <i class="fas fa-clock me-2" style="color: #f59e0b;"></i>
                        Lịch Hẹn Chờ Xác Nhận
                    </h2>
                    <p class="text-muted mb-0">Danh sách lịch hẹn cần xác nhận từ bệnh nhân</p>
                </div>
                <div>
                    <a href="<?php echo e(route('doctor.dashboard')); ?>" class="vc-btn-outline btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Quay lại Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="vc-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Tổng chờ xác nhận</small>
                            <h3 class="fw-bold mb-0 mt-1" style="color: #f59e0b;"><?php echo e($stats['total_pending']); ?></h3>
                        </div>
                        <div class="rounded p-3" style="background: #fef3c7;">
                            <i class="fas fa-clock fa-2x" style="color: #f59e0b;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="vc-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Hôm nay</small>
                            <h3 class="fw-bold mb-0 mt-1" style="color: #10b981;"><?php echo e($stats['today_pending']); ?></h3>
                        </div>
                        <div class="rounded p-3" style="background: #d1fae5;">
                            <i class="fas fa-calendar-day fa-2x" style="color: #10b981;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="vc-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Tuần này</small>
                            <h3 class="fw-bold mb-0 mt-1" style="color: #3b82f6;"><?php echo e($stats['this_week']); ?></h3>
                        </div>
                        <div class="rounded p-3" style="background: #dbeafe;">
                            <i class="fas fa-calendar-week fa-2x" style="color: #3b82f6;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="vc-card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('doctor.lichhen.pending')); ?>" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Từ ngày</label>
                    <input type="date" name="from_date" class="form-control" value="<?php echo e(request('from_date')); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Đến ngày</label>
                    <input type="date" name="to_date" class="form-control" value="<?php echo e(request('to_date')); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Dịch vụ</label>
                    <select name="dich_vu_id" class="form-select">
                        <option value="">Tất cả dịch vụ</option>
                        <?php $__currentLoopData = $dichVus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($dv->id); ?>" <?php echo e(request('dich_vu_id') == $dv->id ? 'selected' : ''); ?>>
                                <?php echo e($dv->ten_dich_vu); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn vc-btn-primary w-100">
                        <i class="fas fa-filter me-1"></i>Lọc
                    </button>
                </div>
            </form>
        </div>
    </div>

    
    <div class="vc-card">
        <div class="card-header bg-white border-0 pt-3 pb-0">
            <h5 class="fw-bold mb-0">
                <i class="fas fa-list me-2" style="color: #10b981;"></i>
                Danh sách lịch hẹn (<?php echo e($appointments->total()); ?>)
            </h5>
        </div>
        <div class="card-body p-0">
            <?php if($appointments->isEmpty()): ?>
                <div class="text-center py-5">
                    <i class="fas fa-check-circle fa-3x mb-3" style="color: #10b981; opacity: 0.3;"></i>
                    <p class="text-muted mb-0 fw-semibold">Tuyệt vời! Không có lịch hẹn nào chờ xác nhận.</p>
                    <small class="text-muted">Tất cả lịch hẹn đã được xử lý</small>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="appointment-today-table">
                        <thead>
                            <tr>
                                <th>Ngày & Giờ</th>
                                <th>Bệnh nhân</th>
                                <th>Dịch vụ</th>
                                <th>Ghi chú</th>
                                <th>Đặt lúc</th>
                                <th class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr id="appointment-row-<?php echo e($appt->id); ?>">
                                <td>
                                    <div class="fw-bold text-success-vc">
                                        <i class="far fa-calendar me-1"></i>
                                        <?php echo e(\Carbon\Carbon::parse($appt->ngay_hen)->format('d/m/Y')); ?>

                                    </div>
                                    <small class="text-muted">
                                        <i class="far fa-clock me-1"></i>
                                        <?php echo e(\Carbon\Carbon::parse($appt->thoi_gian_hen)->format('H:i')); ?>

                                    </small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-2" style="width: 38px; height: 38px; border-radius: 50%; background: linear-gradient(135deg, #10b981, #059669); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                                            <?php echo e(strtoupper(substr($appt->user->name ?? 'N', 0, 1))); ?>

                                        </div>
                                        <div>
                                            <div class="fw-semibold"><?php echo e($appt->user->name ?? 'N/A'); ?></div>
                                            <small class="text-muted"><?php echo e($appt->user->email ?? ''); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <?php echo e($appt->dichVu->ten_dich_vu ?? 'N/A'); ?>

                                    </span>
                                    <?php if($appt->tong_tien): ?>
                                        <div class="small text-success-vc mt-1">
                                            <?php echo e(number_format($appt->tong_tien)); ?>đ
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?php echo e($appt->ghi_chu ? Str::limit($appt->ghi_chu, 50) : 'Không có ghi chú'); ?>

                                    </small>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?php echo e($appt->created_at->diffForHumans()); ?>

                                    </small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button type="button"
                                                class="btn btn-sm vc-btn-primary confirm-btn"
                                                data-id="<?php echo e($appt->id); ?>"
                                                title="Xác nhận">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger reject-btn"
                                                data-id="<?php echo e($appt->id); ?>"
                                                data-patient="<?php echo e($appt->user->name); ?>"
                                                title="Từ chối">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <a href="<?php echo e(route('doctor.lichhen.show', $appt->id)); ?>"
                                           class="btn btn-sm vc-btn-outline"
                                           title="Chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                
                <div class="card-footer bg-white border-0">
                    <?php echo e($appointments->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div>


<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-times-circle text-danger me-2"></i>
                    Từ chối lịch hẹn
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <p class="mb-3">Bạn có chắc muốn từ chối lịch hẹn của <strong id="patientName"></strong>?</p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Lý do từ chối (không bắt buộc)</label>
                        <textarea name="reason" class="form-control" rows="3" placeholder="Nhập lý do từ chối..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-1"></i>Từ chối
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'));

    // Xác nhận
    document.querySelectorAll('.confirm-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const appointmentId = this.dataset.id;

            if (!confirm('Xác nhận lịch hẹn này?')) return;

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch(`/doctor/lich-hen/${appointmentId}/confirm`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById(`appointment-row-${appointmentId}`).remove();
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message || 'Có lỗi xảy ra');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-check"></i>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi xác nhận');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-check"></i>';
            });
        });
    });

    // Từ chối
    document.querySelectorAll('.reject-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const appointmentId = this.dataset.id;
            const patientName = this.dataset.patient;

            document.getElementById('patientName').textContent = patientName;
            document.getElementById('rejectForm').action = `/doctor/lich-hen/${appointmentId}/reject`;

            rejectModal.show();
        });
    });

    // Submit reject form
    document.getElementById('rejectForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const submitBtn = form.querySelector('[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Đang xử lý...';

        console.log('Submitting reject form to:', form.action);

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                reason: form.querySelector('[name="reason"]').value
            })
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers.get('content-type'));
            
            // Kiểm tra nếu response không phải JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                // Có thể là redirect hoặc HTML error page
                if (response.ok) {
                    // Thành công nhưng không trả về JSON - reload page
                    alert('Đã từ chối lịch hẹn thành công!');
                    location.reload();
                    return;
                }
                throw new Error('Response không phải JSON');
            }
            
            return response.json();
        })
        .then(data => {
            if (!data) return; // Đã handle ở trên
            
            console.log('Response data:', data);
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message || 'Có lỗi xảy ra');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-times me-1"></i>Từ chối';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra: ' + error.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-times me-1"></i>Từ chối';
        });
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.doctor', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\WORKING\DACN\DACN\resources\views/doctor/lichhen/pending.blade.php ENDPATH**/ ?>