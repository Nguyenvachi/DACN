

<?php $__env->startSection('title', 'Theo dõi thai kỳ'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: #1f2937;">
                <i class="fas fa-heart me-2" style="color: #ec4899;"></i>
                Theo dõi thai kỳ
            </h4>
            <p class="text-muted mb-0">Quản lý hồ sơ theo dõi thai kỳ của bệnh nhân</p>
        </div>
        <a href="<?php echo e(route('doctor.theo-doi-thai-ky.create')); ?>" class="btn vc-btn-primary">
            <i class="fas fa-plus me-2"></i>Tạo hồ sơ mới
        </a>
    </div>

    
    <div class="card vc-card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('doctor.theo-doi-thai-ky.index')); ?>">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Tìm theo tên, email, SĐT..." 
                               value="<?php echo e(request('search')); ?>">
                    </div>
                    <div class="col-md-3">
                        <select name="trang_thai" class="form-select">
                            <option value="">-- Tất cả trạng thái --</option>
                            <option value="Đang theo dõi" <?php echo e(request('trang_thai') === 'Đang theo dõi' ? 'selected' : ''); ?>>Đang theo dõi</option>
                            <option value="Đã sinh" <?php echo e(request('trang_thai') === 'Đã sinh' ? 'selected' : ''); ?>>Đã sinh</option>
                            <option value="Sẩy thai" <?php echo e(request('trang_thai') === 'Sẩy thai' ? 'selected' : ''); ?>>Sẩy thai</option>
                            <option value="Nạo thai" <?php echo e(request('trang_thai') === 'Nạo thai' ? 'selected' : ''); ?>>Nạo thai</option>
                            <option value="Chuyển viện" <?php echo e(request('trang_thai') === 'Chuyển viện' ? 'selected' : ''); ?>>Chuyển viện</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i>Tìm kiếm
                        </button>
                        <a href="<?php echo e(route('doctor.theo-doi-thai-ky.index')); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-redo me-1"></i>Đặt lại
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    
    <div class="card vc-card">
        <div class="card-body">
            <?php if($theoDoiList->count() > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Bệnh nhân</th>
                            <th>Ngày kinh cuối</th>
                            <th>Ngày dự sinh</th>
                            <th>Tuổi thai</th>
                            <th>Loại thai</th>
                            <th>Trạng thái</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $theoDoiList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $tuoiThai = $item->tuoiThaiHienTai();
                            $soNgayConLai = $item->soNgayConLai();
                        ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        <i class="fas fa-user-circle fa-2x text-primary"></i>
                                    </div>
                                    <div>
                                        <strong><?php echo e($item->user->name); ?></strong><br>
                                        <small class="text-muted"><?php echo e($item->user->email); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo e(\Carbon\Carbon::parse($item->ngay_kinh_cuoi)->format('d/m/Y')); ?></td>
                            <td>
                                <?php echo e(\Carbon\Carbon::parse($item->ngay_du_sinh)->format('d/m/Y')); ?>

                                <?php if($item->trang_thai === 'Đang theo dõi'): ?>
                                <br><small class="text-muted">
                                    <?php if($soNgayConLai > 0): ?>
                                        Còn <?php echo e($soNgayConLai); ?> ngày
                                    <?php elseif($soNgayConLai == 0): ?>
                                        <span class="text-danger fw-bold">Hôm nay!</span>
                                    <?php else: ?>
                                        <span class="text-danger">Quá <?php echo e(abs($soNgayConLai)); ?> ngày</span>
                                    <?php endif; ?>
                                </small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($tuoiThai && $item->trang_thai === 'Đang theo dõi'): ?>
                                    <span class="badge bg-info">
                                        <?php echo e($tuoiThai['tuan']); ?> tuần <?php echo e($tuoiThai['ngay']); ?> ngày
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo e($item->loai_thai === 'Đơn thai' ? 'primary' : 'warning'); ?>">
                                    <?php echo e($item->loai_thai); ?>

                                </span>
                            </td>
                            <td>
                                <?php if($item->trang_thai === 'Đang theo dõi'): ?>
                                <span class="badge bg-success"><?php echo e($item->trang_thai); ?></span>
                                <?php elseif($item->trang_thai === 'Đã sinh'): ?>
                                <span class="badge bg-primary"><?php echo e($item->trang_thai); ?></span>
                                <?php else: ?>
                                <span class="badge bg-secondary"><?php echo e($item->trang_thai); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="<?php echo e(route('doctor.theo-doi-thai-ky.show', $item->id)); ?>" 
                                   class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo e(route('doctor.theo-doi-thai-ky.edit', $item->id)); ?>" 
                                   class="btn btn-sm btn-outline-secondary" title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            
            <div class="mt-3">
                <?php echo e($theoDoiList->links()); ?>

            </div>
            <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-heart fa-4x text-muted mb-3"></i>
                <p class="text-muted">Chưa có hồ sơ theo dõi thai kỳ nào</p>
                <a href="<?php echo e(route('doctor.theo-doi-thai-ky.create')); ?>" class="btn vc-btn-primary">
                    <i class="fas fa-plus me-2"></i>Tạo hồ sơ đầu tiên
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.doctor', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\WORKING\DACN\DACN\resources\views/doctor/theo-doi-thai-ky/index.blade.php ENDPATH**/ ?>