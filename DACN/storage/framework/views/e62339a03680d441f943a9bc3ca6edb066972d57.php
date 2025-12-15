

<?php $__env->startSection('title', 'Chỉ định thủ thuật'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: #1f2937;">
                <i class="fas fa-procedures me-2" style="color: #f59e0b;"></i>
                Chỉ định thủ thuật
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('doctor.dashboard')); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('doctor.benhan.edit', $benhAn->id)); ?>">Bệnh án #<?php echo e($benhAn->id); ?></a></li>
                    <li class="breadcrumb-item active">Chỉ định thủ thuật</li>
                </ol>
            </nav>
        </div>
        <a href="<?php echo e(route('doctor.benhan.edit', $benhAn->id)); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            
            <div class="card vc-card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-user-injured me-2" style="color: #f59e0b;"></i>
                        Thông tin bệnh nhân
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Họ tên:</strong> <?php echo e($benhAn->user->name); ?></p>
                            <p class="mb-2"><strong>Mã BN:</strong> #<?php echo e($benhAn->user->id); ?></p>
                            <p class="mb-2"><strong>Ngày khám:</strong> <?php echo e(\Carbon\Carbon::parse($benhAn->ngay_kham)->format('d/m/Y H:i')); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Chẩn đoán:</strong> <?php echo e($benhAn->chuan_doan ?? 'Chưa có'); ?></p>
                            <p class="mb-2"><strong>Dịch vụ:</strong> <?php echo e($benhAn->lichHen->dichVu->ten_dich_vu ?? 'N/A'); ?></p>
                            <p class="mb-2"><strong>Trạng thái:</strong>
                                <span class="vc-badge vc-badge-<?php echo e($benhAn->lichHen->trang_thai === 'Hoàn thành' ? 'success' : 'warning'); ?>">
                                    <?php echo e($benhAn->lichHen->trang_thai); ?>

                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="card vc-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-syringe me-2" style="color: #10b981;"></i>
                        Chỉ định thủ thuật
                    </h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('doctor.thu-thuat.store', $benhAn->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        <div class="mb-3">
                            <label class="form-label">Loại thủ thuật <span class="text-danger">*</span></label>
                            <select name="dich_vu_id" class="form-select" required id="thuThuatSelect">
                                <option value="">-- Chọn loại thủ thuật --</option>
                                <?php $__currentLoopData = $dichVuThuThuat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $thuThuat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($thuThuat->id); ?>" 
                                        data-gia="<?php echo e(number_format($thuThuat->gia_tien, 0, ',', '.')); ?>"
                                        <?php echo e(old('dich_vu_id') == $thuThuat->id ? 'selected' : ''); ?>>
                                    <?php echo e($thuThuat->ten); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['dich_vu_id'];
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
                            <label class="form-label">Chi tiết trước thủ thuật <span class="text-danger">*</span></label>
                            <textarea name="chi_tiet_truoc_thu_thuat" class="form-control" rows="4" required
                                      placeholder="Ghi rõ: tình trạng bệnh nhân, lý do chỉ định, các xét nghiệm chuẩn bị..."><?php echo e(old('chi_tiet_truoc_thu_thuat')); ?></textarea>
                            <small class="text-muted">Mô tả tình trạng bệnh nhân và lý do thực hiện thủ thuật</small>
                            <?php $__errorArgs = ['chi_tiet_truoc_thu_thuat'];
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
                            <label class="form-label">Ghi chú thêm</label>
                            <textarea name="ghi_chu" class="form-control" rows="2"
                                      placeholder="Các lưu ý đặc biệt, yêu cầu chuẩn bị..."><?php echo e(old('ghi_chu')); ?></textarea>
                            <?php $__errorArgs = ['ghi_chu'];
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

                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Lưu ý:</strong> Vui lòng đảm bảo bệnh nhân đã được giải thích rõ về thủ thuật, nguy cơ và đã ký cam kết đồng ý.
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="<?php echo e(route('doctor.benhan.edit', $benhAn->id)); ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Hủy
                            </a>
                            <button type="submit" class="btn vc-btn-primary">
                                <i class="fas fa-check me-2"></i>Chỉ định thủ thuật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            
            <div class="card vc-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list-alt me-2" style="color: #6366f1;"></i>
                        Thủ thuật đã chỉ định
                    </h5>
                </div>
                <div class="card-body">
                    <?php if($existingThuThuat->isEmpty()): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-procedures fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Chưa có thủ thuật nào</p>
                    </div>
                    <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php $__currentLoopData = $existingThuThuat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $thuThuat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="mb-0"><?php echo e($thuThuat->ten_thu_thuat); ?></h6>
                                <?php if($thuThuat->trang_thai === 'Đã hoàn thành'): ?>
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>Hoàn thành
                                </span>
                                <?php elseif($thuThuat->trang_thai === 'Đang thực hiện'): ?>
                                <span class="badge bg-warning">
                                    <i class="fas fa-clock me-1"></i>Đang thực hiện
                                </span>
                                <?php else: ?>
                                <span class="badge bg-secondary">
                                    <i class="fas fa-hourglass-start me-1"></i>Chờ thực hiện
                                </span>
                                <?php endif; ?>
                            </div>

                            <?php if($thuThuat->chi_tiet_truoc_thu_thuat): ?>
                            <p class="mb-2 small text-muted"><?php echo e(Str::limit($thuThuat->chi_tiet_truoc_thu_thuat, 80)); ?></p>
                            <?php endif; ?>

                            <small class="text-muted">
                                <i class="far fa-calendar me-1"></i>
                                <?php echo e(\Carbon\Carbon::parse($thuThuat->ngay_chi_dinh)->format('d/m/Y H:i')); ?>

                            </small>

                            <?php if($thuThuat->gia_tien): ?>
                            <div class="mt-2">
                                <span class="badge bg-info">
                                    <i class="fas fa-money-bill-wave me-1"></i>
                                    <?php echo e(number_format($thuThuat->gia_tien, 0, ',', '.')); ?> VNĐ
                                </span>
                                <?php if($thuThuat->trang_thai_thanh_toan === 'Đã thanh toán'): ?>
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Đã thanh toán
                                </span>
                                <?php else: ?>
                                <span class="badge bg-warning">
                                    <i class="fas fa-clock me-1"></i>Chưa thanh toán
                                </span>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>

                            <?php if($thuThuat->trang_thai !== 'Chờ thực hiện'): ?>
                            <div class="mt-2">
                                <a href="<?php echo e(route('doctor.thu-thuat.edit', $thuThuat->id)); ?>" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i>Xem chi tiết
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="card vc-card mt-3">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-info-circle me-2" style="color: #10b981;"></i>
                        Thủ thuật phổ biến
                    </h6>
                    <ul class="mb-0 small">
                        <li><strong>Chọc ối:</strong> Lấy mẫu nước ối để xét nghiệm di truyền (16-20 tuần)</li>
                        <li><strong>Sinh thiết nhau thai:</strong> Lấy mẫu mô nhau (10-13 tuần)</li>
                        <li><strong>Đo độ trong của khoang sau gáy:</strong> Sàng lọc Down (11-13 tuần)</li>
                        <li><strong>Chọc dò máu thai:</strong> Xét nghiệm máu thai nhi</li>
                        <li><strong>Cắt polyp cổ tử cung:</strong> Loại bỏ polyp</li>
                        <li><strong>Đặt/tháo vòng:</strong> Biện pháp tránh thai</li>
                    </ul>
                </div>
            </div>

            
            <div class="card vc-card mt-3">
                <div class="card-body">
                    <h6 class="card-title text-danger">
                        <i class="fas fa-shield-alt me-2"></i>
                        Lưu ý an toàn
                    </h6>
                    <ul class="mb-0 small">
                        <li>Kiểm tra kết quả xét nghiệm máu, đông máu</li>
                        <li>Đảm bảo bệnh nhân nhịn ăn đủ thời gian (nếu cần)</li>
                        <li>Có phiếu đồng ý của bệnh nhân</li>
                        <li>Chuẩn bị thuốc và dụng cụ cấp cứu</li>
                        <li>Theo dõi sát sau thủ thuật</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.doctor', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\WORKING\DACN\DACN\resources\views/doctor/thu-thuat/create.blade.php ENDPATH**/ ?>