

<?php $__env->startSection('title', 'Quản Lý Hàng Đợi'); ?>

<?php $__env->startSection('content'); ?>



<div class="container-fluid px-4 py-4">

    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="bi bi-people me-2 text-success"></i>Quản Lý Hàng Đợi</h2>
            <p class="text-muted mb-0">Theo dõi và điều phối hàng đợi khám bệnh</p>
        </div>
        <div>
            <span class="badge bg-light text-dark fs-6"><i class="bi bi-clock me-1"></i><span id="currentTime"></span></span>
        </div>
    </div>

    
    <div class="row mb-4 g-4">
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 h-100 stat-card-modern"
                 style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);">
                <div class="card-body text-white p-4">
                    <div class="d-flex align-items-center">
                        <div class="p-3 rounded-circle bg-white bg-opacity-25">
                            <i class="bi bi-hourglass-split fs-2"></i>
                        </div>
                        <div class="ms-3">
                            <p class="text-white text-opacity-75 mb-1 small">Đang chờ</p>
                            <h3 class="mb-0 fw-bold" id="stat-waiting"><?php echo e($statistics['waiting']); ?></h3>
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
                            <p class="text-white text-opacity-75 mb-1 small"><?php echo e(\App\Models\LichHen::STATUS_IN_PROGRESS_VN); ?></p>
                            <h3 class="mb-0 fw-bold" id="stat-progress"><?php echo e($statistics['in_progress']); ?></h3>
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
                            <p class="text-white text-opacity-75 mb-1 small"><?php echo e(\App\Models\LichHen::STATUS_COMPLETED_VN); ?></p>
                            <h3 class="mb-0 fw-bold" id="stat-completed"><?php echo e($statistics['completed_today']); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 h-100 stat-card-modern"
                 style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
                <div class="card-body text-white p-4">
                    <div class="d-flex align-items-center">
                        <div class="p-3 rounded-circle bg-white bg-opacity-25">
                            <i class="bi bi-stopwatch fs-2"></i>
                        </div>
                        <div class="ms-3">
                            <p class="text-white text-opacity-75 mb-1 small">Thời gian chờ TB</p>
                            <h3 class="mb-0 fw-bold"><?php echo e($statistics['avg_wait_time']); ?> phút</h3>
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

    <div class="row">
        
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-warning text-white border-0">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-hourglass-split me-2"></i>Hàng Đợi (<span id="queue-count"><?php echo e($queue->count()); ?></span>)</h5>
                </div>
                <div class="card-body p-0" style="max-height: 600px; overflow-y: auto;">
                    <div id="queueList">
                        <?php $__empty_1 = true; $__currentLoopData = $queue; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $apt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="list-group-item border-0 border-bottom p-3 queue-item" data-id="<?php echo e($apt->id); ?>">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center"
                                             style="width: 60px; height: 60px; border: 3px solid rgba(245, 158, 11, 0.3);">
                                            <div class="text-center">
                                                <small class="d-block" style="font-size: 0.7rem; line-height: 1;">STT</small>
                                                <h4 class="mb-0 fw-bold" style="line-height: 1;"><?php echo e($index + 1); ?></h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 fw-bold"><?php echo e($apt->user->name); ?></h6>
                                        <p class="mb-0 text-muted small">
                                            <i class="bi bi-person-badge me-1"></i>BS. <?php echo e($apt->bacSi->ho_ten); ?>

                                            <span class="mx-2">|</span>
                                            <i class="bi bi-stethoscope me-1"></i><?php echo e($apt->dichVu->ten_dich_vu); ?>

                                        </p>
                                        <?php if($apt->checked_in_at): ?>
                                            <p class="mb-0 text-muted small">
                                                <i class="bi bi-clock me-1"></i>Check-in: <?php echo e(\Carbon\Carbon::parse($apt->checked_in_at)->format('H:i')); ?>

                                                <span class="badge bg-warning text-dark ms-2">Chờ: <?php echo e(\Carbon\Carbon::parse($apt->checked_in_at)->diffInMinutes(now())); ?> phút</span>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-end">
                                        <form method="POST" action="<?php echo e(route('staff.queue.call_next', $apt)); ?>">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-success btn-sm"
                                                    onclick="return confirm('Gọi <?php echo e($apt->user->name); ?> vào khám?')">
                                                <i class="bi bi-telephone-forward me-1"></i>Gọi Vào
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center py-5 text-muted" id="emptyQueue">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                <p>Không có bệnh nhân đang chờ</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-primary text-white border-0">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-person-hearts me-2"></i><?php echo e(\App\Models\LichHen::STATUS_IN_PROGRESS_VN); ?> (<?php echo e($inProgress->count()); ?>)</h5>
                </div>
                <div class="card-body p-0" style="max-height: 600px; overflow-y: auto;">
                    <?php $__empty_1 = true; $__currentLoopData = $inProgress; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $apt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="list-group-item border-0 border-bottom p-3">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded p-2">
                                        <i class="bi bi-person-hearts fs-4"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1 fw-bold"><?php echo e($apt->user->name); ?></h6>
                                    <p class="mb-1 text-muted small">
                                        <i class="bi bi-person-badge me-1"></i>BS. <?php echo e($apt->bacSi->ho_ten); ?>

                                    </p>
                                    <p class="mb-0 text-muted small">
                                        <i class="bi bi-stethoscope me-1"></i><?php echo e($apt->dichVu->ten_dich_vu); ?>

                                    </p>
                                    <?php if($apt->thoi_gian_bat_dau_kham): ?>
                                        <p class="mb-0 small mt-1">
                                            <span class="badge bg-info">
                                                <i class="bi bi-clock me-1"></i>Bắt đầu: <?php echo e(\Carbon\Carbon::parse($apt->thoi_gian_bat_dau_kham)->format('H:i')); ?>

                                                (<?php echo e(\Carbon\Carbon::parse($apt->thoi_gian_bat_dau_kham)->diffInMinutes(now())); ?> phút)
                                            </span>
                                        </p>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <span class="badge bg-primary"><?php echo e(\App\Models\LichHen::STATUS_IN_PROGRESS_VN); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            <p>Chưa có bệnh nhân đang khám</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    <?php if($completed->count() > 0): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-success text-white border-0">
                <h5 class="mb-0 fw-bold"><i class="bi bi-check-circle me-2"></i>Hoàn Thành Gần Đây</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Mã BN</th>
                                <th>Bệnh Nhân</th>
                                <th>Bác Sĩ</th>
                                <th>Dịch Vụ</th>
                                <th>Thời Gian Hoàn Thành</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $completed; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $apt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><span class="badge bg-secondary">BN-<?php echo e(str_pad($apt->user_id, 4, '0', STR_PAD_LEFT)); ?></span></td>
                                    <td><?php echo e($apt->user->name); ?></td>
                                    <td>BS. <?php echo e($apt->bacSi->ho_ten); ?></td>
                                    <td><?php echo e($apt->dichVu->ten_dich_vu); ?></td>
                                    <td><small><?php echo e($apt->updated_at->format('H:i')); ?></small></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>

<style>
.stat-card-modern {
    transition: transform 0.3s ease;
}
.stat-card-modern:hover {
    transform: translateY(-5px);
}
.queue-item {
    transition: all 0.3s ease;
}
.queue-item:hover {
    background-color: #f8f9fa;
}
</style>

<?php $__env->startPush('scripts'); ?>
<script>
// Auto-refresh every 30 seconds
let refreshInterval;

function startAutoRefresh() {
    refreshInterval = setInterval(() => {
        fetchRealtimeData();
    }, 30000); // 30 seconds
}

function fetchRealtimeData() {
    fetch('<?php echo e(route("staff.queue.realtime")); ?>')
        .then(response => response.json())
        .then(data => {
            // Update statistics
            document.getElementById('stat-waiting').textContent = data.statistics.waiting;
            document.getElementById('stat-progress').textContent = data.statistics.in_progress;
            document.getElementById('stat-completed').textContent = data.statistics.completed;
            document.getElementById('queue-count').textContent = data.statistics.waiting;

            console.log('Queue data updated:', data.timestamp);
        })
        .catch(error => {
            console.error('Error fetching realtime data:', error);
        });
}

// Update current time
function updateCurrentTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    document.getElementById('currentTime').textContent = timeString;
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updateCurrentTime();
    setInterval(updateCurrentTime, 1000);
    startAutoRefresh();
});

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (refreshInterval) clearInterval(refreshInterval);
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.staff', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\WORKING\DACN\DACN\resources\views/staff/queue/index.blade.php ENDPATH**/ ?>