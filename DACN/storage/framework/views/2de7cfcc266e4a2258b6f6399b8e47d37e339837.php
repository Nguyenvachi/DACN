

<?php $__env->startSection('title', 'Chi Tiết Kết Quả Siêu Âm'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4 py-4">
    <div class="mb-4">
        <a href="<?php echo e(route('patient.sieuam.index')); ?>" class="btn btn-sm btn-outline-secondary mb-3">
            <i class="fas fa-arrow-left me-1"></i>Quay lại
        </a>
        <h2 class="fw-bold mb-1">
            <i class="fas fa-heartbeat text-primary me-2"></i>Chi tiết kết quả siêu âm
        </h2>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Thông tin siêu âm</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Loại siêu âm</label>
                            <p class="fw-semibold"><?php echo e($sieuAm->loaiSieuAm->ten ?? $sieuAm->loai_sieu_am ?? 'Siêu âm'); ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Ngày thực hiện</label>
                            <p class="fw-semibold"><?php echo e(\Carbon\Carbon::parse($sieuAm->ngay_thuc_hien)->format('d/m/Y H:i')); ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Bác sĩ thực hiện</label>
                            <p class="fw-semibold"><?php echo e($sieuAm->bacSiThucHien->ho_ten ?? 'N/A'); ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Trạng thái</label>
                            <p>
                                <span class="badge bg-<?php echo e($sieuAm->trang_thai === 'Đã có kết quả' ? 'success' : 'warning'); ?>">
                                    <?php echo e($sieuAm->trang_thai); ?>

                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <?php if($sieuAm->ket_qua): ?>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">Kết quả</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0 text-break"><?php echo e($sieuAm->ket_qua); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if($sieuAm->ket_luan): ?>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">Kết luận</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0 text-break"><?php echo e($sieuAm->ket_luan); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if($sieuAm->tai_bien): ?>
                <div class="card border-0 shadow-sm mb-4 border-danger">
                    <div class="card-header bg-danger text-white py-3">
                        <h5 class="mb-0">Tai biến</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0 text-break text-danger"><?php echo e($sieuAm->tai_bien); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if($sieuAm->ghi_chu): ?>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">Ghi chú</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0 text-break"><?php echo e($sieuAm->ghi_chu); ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Thông tin bệnh án</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>Mã hồ sơ:</strong><br>
                        <span class="text-muted">HS-<?php echo e(str_pad($sieuAm->benhAn->id, 4, '0', STR_PAD_LEFT)); ?></span>
                    </p>
                    <p class="mb-2">
                        <strong>Ngày khám:</strong><br>
                        <span class="text-muted"><?php echo e(\Carbon\Carbon::parse($sieuAm->benhAn->created_at)->format('d/m/Y')); ?></span>
                    </p>
                    <p class="mb-0">
                        <strong>Chẩn đoán:</strong><br>
                        <span class="text-muted"><?php echo e($sieuAm->benhAn->chuan_doan ?? 'Chưa có'); ?></span>
                    </p>
                    <hr>
                    <a href="<?php echo e(route('patient.benhan.show', $sieuAm->benhAn)); ?>" class="btn btn-sm btn-outline-primary w-100">
                        <i class="fas fa-file-medical me-1"></i>Xem bệnh án
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.patient-modern', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\WORKING\DACN\DACN\resources\views/patient/sieuam/show.blade.php ENDPATH**/ ?>