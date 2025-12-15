

<?php $__env->startSection('title', 'Chi tiết thủ thuật'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: #1f2937;">
                <i class="fas fa-procedures me-2" style="color: #f59e0b;"></i>
                Chi tiết thủ thuật
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('doctor.dashboard')); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('doctor.benhan.show', $thuThuat->benh_an_id)); ?>">Bệnh án #<?php echo e($thuThuat->benh_an_id); ?></a></li>
                    <li class="breadcrumb-item active">Chi tiết thủ thuật</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('doctor.thu-thuat.edit', $thuThuat)); ?>" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Nhập kết quả
            </a>
            <a href="<?php echo e(route('doctor.benhan.show', $thuThuat->benh_an_id)); ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        
        <div class="col-lg-8">
            <div class="card vc-card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2" style="color: #f59e0b;"></i>
                        Thông tin thủ thuật
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Tên thủ thuật:</strong> <?php echo e($thuThuat->ten_thu_thuat); ?></p>
                            <p class="mb-2"><strong>Ngày chỉ định:</strong> <?php echo e(\Carbon\Carbon::parse($thuThuat->ngay_chi_dinh)->format('d/m/Y')); ?></p>
                            <p class="mb-2"><strong>Bác sĩ thực hiện:</strong> <?php echo e($thuThuat->bacSi->ho_ten ?? 'N/A'); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Trạng thái:</strong>
                                <?php if($thuThuat->trang_thai === 'Đã hoàn thành'): ?>
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>Đã hoàn thành
                                    </span>
                                <?php elseif($thuThuat->trang_thai === 'Đang thực hiện'): ?>
                                    <span class="badge bg-info">
                                        <i class="fas fa-spinner me-1"></i>Đang thực hiện
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-warning">
                                        <i class="fas fa-hourglass-half me-1"></i>Chờ thực hiện
                                    </span>
                                <?php endif; ?>
                            </p>
                            <p class="mb-2"><strong>Giá tiền:</strong> <span class="text-primary"><?php echo e(number_format($thuThuat->gia_tien ?? 0, 0, ',', '.')); ?> đ</span></p>
                        </div>
                    </div>

                    <?php if($thuThuat->chi_tiet_truoc_thu_thuat): ?>
                    <div class="alert alert-info">
                        <strong><i class="fas fa-notes-medical me-2"></i>Chi tiết trước thủ thuật:</strong>
                        <p class="mb-0 mt-2"><?php echo e($thuThuat->chi_tiet_truoc_thu_thuat); ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if($thuThuat->chi_tiet_sau_thu_thuat): ?>
                    <div class="mt-3">
                        <h6 class="text-success"><i class="fas fa-check-circle me-2"></i>Chi tiết sau thủ thuật:</h6>
                        <p class="mb-0"><?php echo e($thuThuat->chi_tiet_sau_thu_thuat); ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if($thuThuat->ghi_chu): ?>
                    <div class="mt-3">
                        <h6 class="text-muted"><i class="fas fa-sticky-note me-2"></i>Ghi chú:</h6>
                        <p class="mb-0"><?php echo e($thuThuat->ghi_chu); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <?php if($thuThuat->trang_thai === 'Đã hoàn thành'): ?>
            <div class="card vc-card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-file-medical me-2" style="color: #10b981;"></i>
                        Kết quả thủ thuật
                    </h5>
                </div>
                <div class="card-body">
                    <?php if($thuThuat->chi_tiet_sau_thu_thuat): ?>
                        <p><?php echo e($thuThuat->chi_tiet_sau_thu_thuat); ?></p>
                    <?php else: ?>
                        <p class="text-muted">Chưa có kết quả chi tiết</p>
                    <?php endif; ?>
                </div>
            </div>
            <?php else: ?>
            <div class="card vc-card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-hourglass-half fa-3x text-warning mb-3"></i>
                    <p class="text-muted">Thủ thuật chưa hoàn thành. Vui lòng nhập kết quả sau khi thực hiện.</p>
                    <a href="<?php echo e(route('doctor.thu-thuat.edit', $thuThuat)); ?>" class="btn btn-primary mt-2">
                        <i class="fas fa-edit me-2"></i>Nhập kết quả
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>

        
        <div class="col-lg-4">
            <div class="card vc-card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-user-injured me-2" style="color: #3b82f6;"></i>
                        Thông tin bệnh nhân
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Họ tên:</strong> <?php echo e($thuThuat->benhAn->user->name ?? 'N/A'); ?></p>
                    <p class="mb-2"><strong>Mã BN:</strong> #<?php echo e($thuThuat->benhAn->user_id); ?></p>
                    <p class="mb-2"><strong>Giới tính:</strong> <?php echo e($thuThuat->benhAn->user->gioi_tinh ?? 'N/A'); ?></p>
                    <p class="mb-2"><strong>Năm sinh:</strong> <?php echo e($thuThuat->benhAn->user->ngay_sinh ? \Carbon\Carbon::parse($thuThuat->benhAn->user->ngay_sinh)->format('Y') : 'N/A'); ?></p>
                    <p class="mb-0"><strong>SĐT:</strong> <?php echo e($thuThuat->benhAn->user->so_dien_thoai ?? 'N/A'); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.doctor', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\WORKING\DACN\DACN\resources\views/doctor/thu-thuat/show.blade.php ENDPATH**/ ?>