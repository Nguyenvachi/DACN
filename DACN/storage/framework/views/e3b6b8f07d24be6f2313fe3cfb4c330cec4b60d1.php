

<?php $__env->startSection('title', 'Chỉ định X-quang'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">
                <i class="bi bi-file-medical me-2 text-warning"></i>
                Chỉ định X-quang
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('doctor.dashboard')); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('doctor.benhan.edit', $benhAn->id)); ?>">Bệnh án #<?php echo e($benhAn->id); ?></a></li>
                    <li class="breadcrumb-item active">Chỉ định X-quang</li>
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
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="bi bi-clipboard-plus me-2"></i>
                        Chỉ định X-quang
                    </h5>
                </div>
                <div class="card-body">
                    <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?php echo e(session('error')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('doctor.x-quang.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="benh_an_id" value="<?php echo e($benhAn->id); ?>">

                        <div class="mb-3">
                            <label class="form-label">Loại X-quang <span class="text-danger">*</span></label>
                            <select name="loai_x_quang" class="form-select" required>
                                <option value="">-- Chọn loại X-quang --</option>
                                <option value="X-quang ngực" <?php echo e(old('loai_x_quang') == 'X-quang ngực' ? 'selected' : ''); ?>>X-quang ngực</option>
                                <option value="X-quang bụng" <?php echo e(old('loai_x_quang') == 'X-quang bụng' ? 'selected' : ''); ?>>X-quang bụng</option>
                                <option value="X-quang xương" <?php echo e(old('loai_x_quang') == 'X-quang xương' ? 'selected' : ''); ?>>X-quang xương</option>
                                <option value="X-quang sọ não" <?php echo e(old('loai_x_quang') == 'X-quang sọ não' ? 'selected' : ''); ?>>X-quang sọ não</option>
                                <option value="X-quang cột sống" <?php echo e(old('loai_x_quang') == 'X-quang cột sống' ? 'selected' : ''); ?>>X-quang cột sống</option>
                                <option value="X-quang khớp" <?php echo e(old('loai_x_quang') == 'X-quang khớp' ? 'selected' : ''); ?>>X-quang khớp</option>
                                <option value="X-quang răng hàm mặt" <?php echo e(old('loai_x_quang') == 'X-quang răng hàm mặt' ? 'selected' : ''); ?>>X-quang răng hàm mặt</option>
                                <option value="Khác">Khác (ghi rõ trong ghi chú)</option>
                            </select>
                            <?php $__errorArgs = ['loai_x_quang'];
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
                            <label class="form-label">Vị trí chụp <span class="text-danger">*</span></label>
                            <input type="text" name="vi_tri" class="form-control" 
                                   value="<?php echo e(old('vi_tri')); ?>" 
                                   placeholder="VD: Ngực thẳng, Cột sống cổ 2 tư thế..." required>
                            <?php $__errorArgs = ['vi_tri'];
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
                            <label class="form-label">Lý do chỉ định</label>
                            <textarea name="chi_dinh" class="form-control" rows="3"
                                      placeholder="VD: Đau ngực, ho kéo dài, nghi ngờ viêm phổi..."><?php echo e(old('chi_dinh')); ?></textarea>
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

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning">
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
            <div class="card shadow-sm border-warning">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Lưu ý</h6>
                </div>
                <div class="card-body">
                    <ul class="mb-0 small">
                        <li class="mb-2">Kiểm tra chống chỉ định (thai phụ, trẻ em...)</li>
                        <li class="mb-2">Ghi rõ vị trí và tư thế chụp</li>
                        <li class="mb-2">Yêu cầu bệnh nhân tháo đồ kim loại</li>
                        <li class="mb-2">Hướng dẫn giữ yên khi chụp</li>
                        <li>Sử dụng chì bảo vệ nếu cần thiết</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.doctor', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\WORKING\DACN\DACN\resources\views/doctor/x-quang/create.blade.php ENDPATH**/ ?>