

<?php $__env->startSection('title', 'Đơn Thuốc Đã Cấp'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-check-circle text-success me-2"></i>
                Đơn Thuốc Đã Cấp
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('staff.dashboard')); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('staff.donthuoc.index')); ?>">Đơn thuốc</a></li>
                    <li class="breadcrumb-item active">Đã cấp</li>
                </ol>
            </nav>
        </div>
    </div>

    
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link" href="<?php echo e(route('staff.donthuoc.index')); ?>">
                <i class="bi bi-list-ul me-1"></i>Tất cả
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo e(route('staff.donthuoc.dang-cho')); ?>">
                <i class="bi bi-hourglass-split me-1"></i>Đang chờ
                <?php $dangCho = \App\Models\DonThuoc::whereNull('ngay_cap_thuoc')->count(); ?>
                <?php if($dangCho > 0): ?>
                    <span class="badge bg-warning text-dark"><?php echo e($dangCho); ?></span>
                <?php endif; ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="<?php echo e(route('staff.donthuoc.da-cap')); ?>">
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
                            <th width="18%">Bệnh nhân</th>
                            <th width="13%">Bác sĩ</th>
                            <th width="12%">Ngày kê</th>
                            <th width="10%">SL thuốc</th>
                            <th width="12%">Ngày cấp</th>
                            <th width="15%">Người cấp</th>
                            <th width="12%" class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $donThuocs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><strong>DT-<?php echo e(str_pad($dt->id, 4, '0', STR_PAD_LEFT)); ?></strong></td>
                            <td>
                                <div><?php echo e($dt->benhAn->user->name ?? 'N/A'); ?></div>
                                <small class="text-muted"><?php echo e($dt->benhAn->user->so_dien_thoai ?? ''); ?></small>
                            </td>
                            <td>BS. <?php echo e($dt->benhAn->bacSi->ho_ten ?? 'N/A'); ?></td>
                            <td><?php echo e($dt->created_at->format('d/m/Y')); ?></td>
                            <td>
                                <span class="badge bg-info"><?php echo e($dt->items->count()); ?> loại</span>
                            </td>
                            <td>
                                <div><?php echo e($dt->ngay_cap_thuoc->format('d/m/Y')); ?></div>
                                <small class="text-muted"><?php echo e($dt->ngay_cap_thuoc->format('H:i')); ?></small>
                            </td>
                            <td><?php echo e($dt->nguoiCapThuoc->name ?? 'N/A'); ?></td>
                            <td class="text-center">
                                <a href="<?php echo e(route('staff.donthuoc.show', $dt)); ?>" 
                                   class="btn btn-sm btn-primary"
                                   title="Xem chi tiết">
                                    <i class="bi bi-eye"></i>
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
                <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3">Chưa có đơn thuốc nào được cấp.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.staff', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\WORKING\DACN\DACN\resources\views/staff/donthuoc/da-cap.blade.php ENDPATH**/ ?>