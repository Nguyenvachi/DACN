

<?php $__env->startSection('title', 'Đơn Thuốc Chờ Cấp'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-hourglass-split text-warning me-2"></i>
                Đơn Thuốc Chờ Cấp
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('staff.dashboard')); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('staff.donthuoc.index')); ?>">Đơn thuốc</a></li>
                    <li class="breadcrumb-item active">Chờ cấp</li>
                </ol>
            </nav>
        </div>
    </div>

    
    <?php if($donThuocs->count() > 0): ?>
    <div class="alert alert-warning alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong>Có <?php echo e($donThuocs->total()); ?> đơn thuốc đang chờ cấp.</strong> Vui lòng xử lý kịp thời.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link" href="<?php echo e(route('staff.donthuoc.index')); ?>">
                <i class="bi bi-list-ul me-1"></i>Tất cả
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="<?php echo e(route('staff.donthuoc.dang-cho')); ?>">
                <i class="bi bi-hourglass-split me-1"></i>Đang chờ
                <span class="badge bg-warning text-dark"><?php echo e($donThuocs->total()); ?></span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo e(route('staff.donthuoc.da-cap')); ?>">
                <i class="bi bi-check-circle me-1"></i>Đã cấp
            </a>
        </li>
    </ul>

    
    <div class="card shadow-sm">
        <div class="card-body">
            <?php if($donThuocs->count() > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="8%">Mã ĐT</th>
                            <th width="20%">Bệnh nhân</th>
                            <th width="15%">Bác sĩ</th>
                            <th width="12%">Ngày kê</th>
                            <th width="10%">SL thuốc</th>
                            <th width="15%">Thời gian chờ</th>
                            <th width="10%" class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $donThuocs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><strong>DT-<?php echo e(str_pad($dt->id, 4, '0', STR_PAD_LEFT)); ?></strong></td>
                            <td>
                                <div><strong><?php echo e($dt->benhAn->user->name ?? 'N/A'); ?></strong></div>
                                <small class="text-muted"><?php echo e($dt->benhAn->user->so_dien_thoai ?? ''); ?></small>
                            </td>
                            <td>BS. <?php echo e($dt->benhAn->bacSi->ho_ten ?? 'N/A'); ?></td>
                            <td><?php echo e($dt->created_at->format('d/m/Y H:i')); ?></td>
                            <td>
                                <span class="badge bg-info"><?php echo e($dt->items->count()); ?> loại</span>
                                <br>
                                <small class="text-muted"><?php echo e($dt->items->sum('so_luong')); ?> tổng</small>
                            </td>
                            <td>
                                <?php
                                    $minutes = $dt->created_at->diffInMinutes(now());
                                    $hours = floor($minutes / 60);
                                    $mins = $minutes % 60;
                                ?>
                                <?php if($hours > 0): ?>
                                    <span class="badge bg-warning text-dark"><?php echo e($hours); ?>h <?php echo e($mins); ?>p</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark"><?php echo e($mins); ?> phút</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="<?php echo e(route('staff.donthuoc.show', $dt)); ?>" 
                                   class="btn btn-sm btn-success"
                                   title="Cấp thuốc">
                                    <i class="bi bi-check-circle"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            
            <div class="mt-3">
                <?php echo e($donThuocs->links()); ?>

            </div>
            <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3">Tuyệt vời! Không có đơn thuốc nào đang chờ cấp.</p>
                <a href="<?php echo e(route('staff.donthuoc.index')); ?>" class="btn btn-primary">
                    <i class="bi bi-list-ul me-1"></i>Xem tất cả đơn thuốc
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.staff', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\WORKING\DACN\DACN\resources\views/staff/donthuoc/dang-cho.blade.php ENDPATH**/ ?>