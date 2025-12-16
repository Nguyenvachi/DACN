

<?php $__env->startSection('title', 'Chỉ định xét nghiệm'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">
                <i class="bi bi-heart-pulse me-2 text-danger"></i>
                Chỉ định xét nghiệm
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('doctor.dashboard')); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('doctor.benhan.edit', $benhAn->id)); ?>">Bệnh án #<?php echo e($benhAn->id); ?></a></li>
                    <li class="breadcrumb-item active">Chỉ định xét nghiệm</li>
                </ol>
            </nav>
        </div>
        <a href="<?php echo e(route('doctor.benhan.edit', $benhAn->id)); ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="bi bi-person-badge me-2 text-primary"></i>
                        Thông tin bệnh nhân
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Họ tên:</strong> <?php echo e($benhAn->benhNhan->name); ?></p>
                            <p class="mb-2"><strong>Mã BN:</strong> #<?php echo e($benhAn->benhNhan->id); ?></p>
                            <p class="mb-2"><strong>Ngày khám:</strong> <?php echo e(\Carbon\Carbon::parse($benhAn->ngay_kham)->format('d/m/Y')); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Chẩn đoán:</strong> <?php echo e($benhAn->chuan_doan ?? 'Chưa có'); ?></p>
                            <p class="mb-2"><strong>Triệu chứng:</strong> <?php echo e($benhAn->trieu_chung ?? 'N/A'); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-clipboard-plus me-2"></i>
                        Chỉ định xét nghiệm
                    </h5>
                </div>
                <div class="card-body">
                    <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?php echo e(session('error')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('doctor.xet-nghiem.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="benh_an_id" value="<?php echo e($benhAn->id); ?>">

                        <div class="mb-3">
                            <label class="form-label">Chọn xét nghiệm <span class="text-danger">*</span></label>
                            <select name="danh_muc_xet_nghiem_id" class="form-select" required>
                                <option value="">-- Chọn xét nghiệm --</option>
                                <?php $__currentLoopData = $danhMucXetNghiem; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $xn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($xn->id); ?>" data-gia="<?php echo e($xn->gia_tien); ?>" <?php echo e(old('danh_muc_xet_nghiem_id') == $xn->id ? 'selected' : ''); ?>>
                                    <?php echo e($xn->ten_xet_nghiem); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['danh_muc_xet_nghiem_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ngày chỉ định <span class="text-danger">*</span></label>
                            <input type="date" name="ngay_chi_dinh" class="form-control" 
                                   value="<?php echo e(old('ngay_chi_dinh', date('Y-m-d'))); ?>" required>
                            <?php $__errorArgs = ['ngay_chi_dinh'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="can_nhin_an" value="1" id="canNhinAn" <?php echo e(old('can_nhin_an') ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="canNhinAn">
                                    Yêu cầu nhịn ăn
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Lý do chỉ định</label>
                            <textarea name="chi_dinh" class="form-control" rows="3"
                                      placeholder="VD: Theo dõi tình trạng bệnh, kiểm tra trước phẫu thuật..."><?php echo e(old('chi_dinh')); ?></textarea>
                            <?php $__errorArgs = ['chi_dinh'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Hướng dẫn chuẩn bị</label>
                            <textarea name="chuan_bi" class="form-control" rows="3"
                                      placeholder="VD: Nhịn ăn 8 tiếng trước khi lấy mẫu, uống nước..."><?php echo e(old('chuan_bi')); ?></textarea>
                            <small class="text-muted">Hướng dẫn bệnh nhân chuẩn bị trước xét nghiệm</small>
                            <?php $__errorArgs = ['chuan_bi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-check-circle me-2"></i>Chỉ định
                            </button>
                            <a href="<?php echo e(route('doctor.benhan.edit', $benhAn->id)); ?>" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-2"></i>Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-danger">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Lưu ý</h6>
                </div>
                <div class="card-body">
                    <ul class="mb-0 small">
                        <li class="mb-2">Kiểm tra chỉ định phù hợp với triệu chứng</li>
                        <li class="mb-2">Ghi rõ lý do và mục đích xét nghiệm</li>
                        <li class="mb-2">Hướng dẫn bệnh nhân chuẩn bị đúng cách</li>
                        <li class="mb-2">Lưu ý điều kiện bảo quản mẫu nếu cần</li>
                        <li>Thời gian có kết quả thường 2-24 giờ</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.doctor', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\WORKING\DACN\DACN\resources\views/doctor/xet-nghiem/create.blade.php ENDPATH**/ ?>