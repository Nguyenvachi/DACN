

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4 fade-up">
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-1" style="color: var(--vc-dark);">
                        Chào mừng, BS. <?php echo e($bacSi->ho_ten); ?>

                        <i class="fas fa-hand-sparkles" style="color: #10b981;"></i>
                    </h2>
                    <p class="text-muted mb-0">Đây là tổng quan hoạt động của bạn</p>
                </div>
                <div class="text-end">
                    <p class="mb-0 text-muted small"><?php echo e(now()->locale('vi')->isoFormat('dddd, D MMMM YYYY')); ?></p>
                    <p class="mb-0 fw-bold fs-5" style="color: #10b981;"><?php echo e(now()->format('H:i')); ?></p>
                </div>
            </div>
        </div>
    </div>

    
    <div class="quick-actions-bar mb-4">
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <div class="me-2">
                <i class="fas fa-bolt" style="color: #10b981;"></i>
                <strong>Hành động nhanh:</strong>
            </div>

            <a href="<?php echo e(route('doctor.lichhen.pending')); ?>" class="quick-action-btn primary">
                <i class="fas fa-clock"></i>
                <span>Lịch chờ xác nhận</span>
                <?php if($pendingAppointments > 0): ?>
                    <span class="badge bg-danger ms-1"><?php echo e($pendingAppointments); ?></span>
                <?php endif; ?>
            </a>

            <a href="<?php echo e(route('doctor.queue.index')); ?>" class="quick-action-btn outline">
                <i class="fas fa-users"></i>
                <span>Hàng đợi khám</span>
            </a>

            <a href="<?php echo e(route('doctor.benhan.create')); ?>" class="quick-action-btn outline">
                <i class="fas fa-file-medical"></i>
                <span>Tạo bệnh án mới</span>
            </a>

            <a href="<?php echo e(route('doctor.calendar.index')); ?>" class="quick-action-btn outline">
                <i class="fas fa-calendar-check"></i>
                <span>Lịch làm việc</span>
            </a>
        </div>
    </div>

    
    <?php
        $inProgressAppointments = \App\Models\LichHen::where('bac_si_id', $bacSi->id)
            ->whereIn('trang_thai', [\App\Models\LichHen::STATUS_IN_PROGRESS_VN])
            ->with(['user', 'dichVu', 'benhAn'])
            ->orderBy('updated_at', 'desc')
            ->get();
    ?>

    <?php if($inProgressAppointments->isNotEmpty()): ?>
    <div class="alert" style="background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border: 2px solid #10b981; border-radius: 12px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);" role="alert">
        <div class="d-flex justify-content-between align-items-center">
            <div class="flex-grow-1">
                <h5 class="mb-2 fw-bold" style="color: #065f46;">
                    <i class="fas fa-stethoscope fa-spin me-2"></i>
                    Bạn đang khám <?php echo e($inProgressAppointments->count()); ?> bệnh nhân
                </h5>
                <div class="row g-2">
                    <?php $__currentLoopData = $inProgressAppointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $startTime = $appt->thoi_gian_bat_dau_kham ?? $appt->updated_at;
                        $duration = $startTime ? \Carbon\Carbon::parse($startTime)->diffInMinutes(now()) : 0;
                    ?>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm" style="background: white;">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center flex-grow-1">
                                        <div class="me-3" style="width: 45px; height: 45px; border-radius: 50%; background: linear-gradient(135deg, #10b981, #059669); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.2rem;">
                                            <?php echo e($appt->stt_kham ?? '?'); ?>

                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark"><?php echo e($appt->user->name); ?></div>
                                            <small class="text-muted">
                                                <?php echo e($appt->dichVu->ten_dich_vu ?? 'N/A'); ?> • 
                                                <i class="fas fa-clock me-1"></i><?php echo e($duration); ?>p
                                            </small>
                                        </div>
                                    </div>
                                    <div>
                                        <?php if($appt->benhAn): ?>
                                            <a href="<?php echo e(route('doctor.benhan.edit', $appt->benhAn->id)); ?>"
                                               class="btn btn-success btn-sm">
                                                <i class="fas fa-arrow-right me-1"></i>
                                                Tiếp tục
                                            </a>
                                        <?php else: ?>
                                            <form action="<?php echo e(route('doctor.queue.start', $appt->id)); ?>" method="POST" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="fas fa-play me-1"></i>
                                                    Bắt đầu
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <div class="ms-3">
                <a href="<?php echo e(route('doctor.queue.index')); ?>" class="btn btn-success">
                    <i class="fas fa-list me-2"></i>
                    Xem hàng đợi
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    
    <div class="row g-3 mb-4">
        
        <div class="col-md-6 col-xl-3">
            <div class="vc-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Lịch hẹn hôm nay</p>
                            <h3 class="fw-bold mb-0" style="color: #10b981;"><?php echo e($appointmentsToday); ?></h3>
                            <small class="text-success"><i class="fas fa-calendar-day me-1"></i>Hôm nay</small>
                        </div>
                        <div class="rounded p-3" style="background: linear-gradient(135deg, #10b981, #059669);">
                            <i class="fas fa-calendar-check fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-md-6 col-xl-3">
            <div class="vc-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Tổng bệnh nhân</p>
                            <h3 class="fw-bold mb-0" style="color: #3b82f6;"><?php echo e($totalPatients); ?></h3>
                            <small class="text-info"><i class="fas fa-users me-1"></i>Đã khám</small>
                        </div>
                        <div class="bg-info bg-opacity-10 rounded p-3">
                            <i class="fas fa-user-injured fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-md-6 col-xl-3">
            <div class="vc-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Đánh giá</p>
                            <h3 class="fw-bold mb-0" style="color: #f59e0b;"><?php echo e($avgRating); ?> <span class="fs-6 text-warning">★</span></h3>
                            <small class="text-warning"><i class="fas fa-star me-1"></i><?php echo e($totalReviews); ?> đánh giá</small>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded p-3">
                            <i class="fas fa-star fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-md-6 col-xl-3">
            <div class="vc-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Tháng này</p>
                            <h3 class="fw-bold mb-0" style="color: #10b981;"><?php echo e($appointmentsThisMonth); ?></h3>
                            <small class="text-success"><i class="fas fa-chart-line me-1"></i>Lịch hẹn</small>
                        </div>
                        <div class="rounded p-3" style="background: linear-gradient(135deg, #34d399, #10b981);">
                            <i class="fas fa-calendar-alt fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row g-3 mb-4">
        
        <div class="col-lg-8">
            <div class="vc-card">
                <div class="card-header bg-white border-0 pt-3 pb-0">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-chart-line me-2" style="color: #10b981;"></i>
                        Lịch hẹn 7 ngày qua
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="appointmentsChart" height="80"></canvas>
                </div>
            </div>
        </div>

        
        <div class="col-lg-4">
            <div class="vc-card h-100">
                <div class="card-header bg-white border-0 pt-3 pb-0">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-chart-pie me-2" style="color: #10b981;"></i>
                        Trạng thái tháng này
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="vc-card">
                <div class="card-header bg-white border-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-calendar-day me-2" style="color: #10b981;"></i>
                        Lịch hẹn hôm nay
                        <span class="badge" style="background: linear-gradient(135deg, #10b981, #059669); color: white;">
                            <?php echo e($todayAppointments->count()); ?>

                        </span>
                    </h5>
                    <a href="<?php echo e(route('doctor.calendar.index')); ?>" class="vc-btn-outline btn-sm">
                        <i class="fas fa-calendar me-1"></i>Xem lịch đầy đủ
                    </a>
                </div>
                <div class="card-body p-0">
                    <?php if($todayAppointments->isEmpty()): ?>
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-calendar-times fa-3x mb-3" style="opacity: 0.2; color: #10b981;"></i>
                            <p class="mb-0 fw-semibold">Không có lịch hẹn nào hôm nay</p>
                            <small>Hãy nghỉ ngơi và chuẩn bị cho ngày mai!</small>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="appointment-today-table">
                                <thead>
                                    <tr>
                                        <th>Thời gian</th>
                                        <th>Bệnh nhân</th>
                                        <th>Dịch vụ</th>
                                        <th>Trạng thái</th>
                                        <th>Thanh toán</th>
                                        <th class="text-center">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $todayAppointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <strong class="text-success-vc">
                                                <i class="far fa-clock me-1"></i>
                                                <?php echo e(\Carbon\Carbon::parse($appt->thoi_gian_hen)->format('H:i')); ?>

                                            </strong>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-2" style="width: 38px; height: 38px; border-radius: 50%; background: linear-gradient(135deg, #10b981, #059669); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700;">
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
                                        </td>
                                        <td>
                                            <?php
                                                $statusConfig = match($appt->trang_thai) {
                                                    'Đã xác nhận' => ['class' => 'status-confirmed', 'icon' => 'check-circle'],
                                                    'Chờ xác nhận' => ['class' => 'status-pending', 'icon' => 'clock'],
                                                    'Đã hủy' => ['class' => 'status-cancelled', 'icon' => 'times-circle'],
                                                    'Hoàn thành' => ['class' => 'status-completed', 'icon' => 'check-double'],
                                                    'Đang khám' => ['class' => 'status-in-progress', 'icon' => 'stethoscope'],
                                                    default => ['class' => 'status-badge', 'icon' => 'info-circle']
                                                };
                                            ?>
                                            <span class="status-badge <?php echo e($statusConfig['class']); ?>">
                                                <i class="fas fa-<?php echo e($statusConfig['icon']); ?>"></i>
                                                <?php echo e($appt->trang_thai); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <?php if($appt->payment_status === 'paid'): ?>
                                                <span class="badge" style="background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0;">
                                                    <i class="fas fa-check-circle"></i> Đã thanh toán
                                                </span>
                                            <?php elseif($appt->payment_status === 'partial'): ?>
                                                <span class="badge" style="background: #fef3c7; color: #92400e; border: 1px solid #fde68a;">
                                                    <i class="fas fa-exclamation-circle"></i> Một phần
                                                </span>
                                            <?php else: ?>
                                                <span class="badge" style="background: #f3f4f6; color: #374151; border: 1px solid #e5e7eb;">
                                                    <i class="fas fa-clock"></i> Chưa thanh toán
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <?php if($appt->trang_thai === 'Đã xác nhận'): ?>
                                                    <a href="<?php echo e(route('doctor.benhan.create', ['lich_hen_id' => $appt->id])); ?>"
                                                       class="btn btn-sm vc-btn-primary"
                                                       title="Tạo bệnh án">
                                                        <i class="fas fa-file-medical"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if($appt->conversation): ?>
                                                    <a href="<?php echo e(route('doctor.chat.show', $appt->conversation->id)); ?>"
                                                       class="btn btn-sm vc-btn-outline"
                                                       title="Chat với bệnh nhân">
                                                        <i class="fas fa-comments"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row g-3 mb-4">
        
        <div class="col-lg-6">
            <div class="vc-card">
                <div class="card-header bg-white border-0 pt-3 pb-0">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-calendar-alt me-2" style="color: #10b981;"></i>
                        Lịch hẹn sắp tới (7 ngày)
                    </h5>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    <?php if($upcomingAppointments->isEmpty()): ?>
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-calendar-check fa-2x mb-2" style="opacity: 0.2; color: #10b981;"></i>
                            <p class="mb-0">Chưa có lịch hẹn sắp tới</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php $__currentLoopData = $upcomingAppointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $upcoming): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="list-group-item px-0 border-start-0 border-end-0 hover-bg-light" style="transition: background 0.2s;">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="me-2" style="width: 34px; height: 34px; border-radius: 50%; background: linear-gradient(135deg, #10b981, #059669); color: white; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 700;">
                                                <?php echo e(strtoupper(substr($upcoming->user->name ?? 'N', 0, 1))); ?>

                                            </div>
                                            <strong><?php echo e($upcoming->user->name ?? 'N/A'); ?></strong>
                                        </div>
                                        <div class="mb-1">
                                            <span class="badge bg-light text-dark border">
                                                <i class="fas fa-calendar me-1"></i><?php echo e(\Carbon\Carbon::parse($upcoming->ngay_hen)->format('d/m/Y')); ?>

                                            </span>
                                            <span class="badge bg-light text-dark border">
                                                <i class="fas fa-clock me-1"></i><?php echo e(\Carbon\Carbon::parse($upcoming->thoi_gian_hen)->format('H:i')); ?>

                                            </span>
                                        </div>
                                        <small class="text-muted">
                                            <i class="fas fa-stethoscope me-1"></i>
                                            <?php echo e($upcoming->dichVu->ten_dich_vu ?? 'N/A'); ?>

                                        </small>
                                    </div>
                                    <div>
                                        <?php
                                            $statusConfig = match($upcoming->trang_thai) {
                                                'Đã xác nhận' => ['class' => 'status-confirmed', 'icon' => 'check-circle'],
                                                'Chờ xác nhận' => ['class' => 'status-pending', 'icon' => 'clock'],
                                                default => ['class' => 'status-badge', 'icon' => 'info-circle']
                                            };
                                        ?>
                                        <span class="status-badge <?php echo e($statusConfig['class']); ?>">
                                            <i class="fas fa-<?php echo e($statusConfig['icon']); ?>"></i>
                                            <?php echo e($upcoming->trang_thai); ?>

                                        </span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="col-lg-6">
            <div class="vc-card">
                <div class="card-header bg-white border-0 pt-3 pb-0">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-star me-2" style="color: #f59e0b;"></i>
                        Đánh giá gần đây
                    </h5>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    <?php if($recentReviews->isEmpty()): ?>
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-star fa-2x mb-2" style="opacity: 0.2; color: #f59e0b;"></i>
                            <p class="mb-0">Chưa có đánh giá nào</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php $__currentLoopData = $recentReviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="list-group-item px-0 border-start-0 border-end-0">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <strong><?php echo e($review->user->name ?? 'N/A'); ?></strong>
                                        <div class="text-warning">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <?php if($i <= $review->rating): ?>
                                                    <i class="fas fa-star"></i>
                                                <?php else: ?>
                                                    <i class="far fa-star"></i>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    <small class="text-muted"><?php echo e($review->created_at->diffForHumans()); ?></small>
                                </div>
                                <p class="mb-0 text-muted small"><?php echo e($review->noi_dung); ?></p>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row g-3">
        
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">📋 Bệnh án gần đây</h5>
                    <a href="<?php echo e(route('doctor.benhan.index')); ?>" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-list me-1"></i>Xem tất cả
                    </a>
                </div>
                <div class="card-body">
                    <?php if($recentMedicalRecords->isEmpty()): ?>
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-file-medical fa-2x mb-2 opacity-25"></i>
                            <p class="mb-0">Chưa có bệnh án nào</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Ngày khám</th>
                                        <th>Bệnh nhân</th>
                                        <th>Chẩn đoán</th>
                                        <th class="text-center">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $recentMedicalRecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e(\Carbon\Carbon::parse($record->ngay_kham)->format('d/m/Y')); ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle bg-success text-white me-2" style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 13px;">
                                                    <?php echo e(strtoupper(substr($record->benhNhan->name ?? 'N', 0, 1))); ?>

                                                </div>
                                                <span><?php echo e($record->benhNhan->name ?? 'N/A'); ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-truncate d-inline-block" style="max-width: 250px;" title="<?php echo e($record->chuan_doan); ?>">
                                                <?php echo e($record->chuan_doan); ?>

                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?php echo e(route('doctor.benhan.show', $record->id)); ?>"
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-3">
                    <h5 class="fw-bold mb-0">⭐ Phân phối đánh giá</h5>
                </div>
                <div class="card-body">
                    <?php if($totalReviews > 0): ?>
                        <?php $__currentLoopData = $ratingDistribution; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $star => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-2" style="width: 60px;">
                                <span class="text-warning"><?php echo e($star); ?> <i class="fas fa-star"></i></span>
                            </div>
                            <div class="flex-grow-1">
                                <div class="progress" style="height: 8px;">
                                    <?php
                                        $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                                    ?>
                                    <div class="progress-bar bg-warning" role="progressbar"
                                         style="width: <?php echo e($percentage); ?>%"></div>
                                </div>
                            </div>
                            <div class="ms-2" style="width: 50px;">
                                <small class="text-muted"><?php echo e($count); ?></small>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-chart-bar fa-2x mb-2 opacity-25"></i>
                            <p class="mb-0">Chưa có dữ liệu đánh giá</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .card {
        transition: transform 0.2s;
    }
    .card:hover {
        transform: translateY(-2px);
    }
    .avatar-circle {
        flex-shrink: 0;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
    // BIỂU ĐỒ LỊCH HẸN 7 NGÀY
    const appointmentsChartData = <?php echo json_encode($appointmentsChart, 15, 512) ?>;
    const ctxAppointments = document.getElementById('appointmentsChart').getContext('2d');
    new Chart(ctxAppointments, {
        type: 'line',
        data: {
            labels: appointmentsChartData.map(d => d.date),
            datasets: [{
                label: 'Số lịch hẹn',
                data: appointmentsChartData.map(d => d.count),
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // BIỂU ĐỒ TRẠNG THÁI
    const statusStats = <?php echo json_encode($statusStats, 15, 512) ?>;
    const statusLabels = Object.keys(statusStats);
    const statusValues = Object.values(statusStats);

    const ctxStatus = document.getElementById('statusChart').getContext('2d');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusValues,
                backgroundColor: [
                    '#198754', // Đã xác nhận - green
                    '#ffc107', // Chờ xác nhận - yellow
                    '#dc3545', // Đã hủy - red
                    '#0d6efd', // Hoàn thành - blue
                    '#6c757d'  // Khác - gray
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.doctor', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\WORKING\DACN\DACN\resources\views/doctor/dashboard.blade.php ENDPATH**/ ?>