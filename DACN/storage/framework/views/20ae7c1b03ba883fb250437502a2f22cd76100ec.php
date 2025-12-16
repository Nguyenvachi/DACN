

<?php $__env->startSection('title', 'Kết Quả Siêu Âm'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="fas fa-heartbeat text-primary me-2"></i>Kết quả siêu âm
            </h2>
            <p class="text-muted mb-0">Xem các kết quả siêu âm của bạn</p>
        </div>
    </div>

    <?php if($sieuAms->count() > 0): ?>
        <div class="row g-4">
            <?php $__currentLoopData = $sieuAms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sieuAm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="card-title mb-1">
                                        <?php echo e($sieuAm->loaiSieuAm->ten ?? $sieuAm->loai_sieu_am ?? 'Siêu âm'); ?>

                                    </h5>
                                    <p class="text-muted small mb-0">
                                        <i class="fas fa-calendar me-1"></i>
                                        <?php echo e(\Carbon\Carbon::parse($sieuAm->ngay_thuc_hien)->format('d/m/Y')); ?>

                                    </p>
                                </div>
                                <span class="badge bg-<?php echo e($sieuAm->trang_thai === 'Đã có kết quả' ? 'success' : 'warning'); ?>">
                                    <?php echo e($sieuAm->trang_thai); ?>

                                </span>
                            </div>

                            <div class="mb-3">
                                <p class="mb-2">
                                    <i class="fas fa-user-md text-success me-2"></i>
                                    <strong>Bác sĩ:</strong> <?php echo e($sieuAm->bacSiThucHien->ho_ten ?? 'N/A'); ?>

                                </p>
                                <?php if($sieuAm->ket_luan): ?>
                                    <p class="mb-0">
                                        <i class="fas fa-clipboard-check text-info me-2"></i>
                                        <strong>Kết luận:</strong> <?php echo e(Str::limit($sieuAm->ket_luan, 80)); ?>

                                    </p>
                                <?php endif; ?>
                            </div>

                            <a href="<?php echo e(route('patient.sieuam.show', $sieuAm)); ?>" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i>Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="mt-4">
            <?php echo e($sieuAms->links()); ?>

        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-heartbeat fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Chưa có kết quả siêu âm</h5>
                <p class="text-muted">Kết quả siêu âm của bạn sẽ được lưu tại đây sau khi thực hiện</p>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.patient-modern', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\WORKING\DACN\DACN\resources\views/patient/sieuam/index.blade.php ENDPATH**/ ?>