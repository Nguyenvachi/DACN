

<?php $__env->startSection('title', 'Cập nhật hồ sơ theo dõi thai kỳ #' . $theoDoiThaiKy->id); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: #1f2937;">
                <i class="fas fa-heart me-2" style="color: #ec4899;"></i>
                Cập nhật hồ sơ theo dõi thai kỳ
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('doctor.dashboard')); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('doctor.theo-doi-thai-ky.index')); ?>">Theo dõi thai kỳ</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('doctor.theo-doi-thai-ky.show', $theoDoiThaiKy->id)); ?>">Chi tiết #<?php echo e($theoDoiThaiKy->id); ?></a></li>
                    <li class="breadcrumb-item active">Cập nhật</li>
                </ol>
            </nav>
        </div>
        <a href="<?php echo e(route('doctor.theo-doi-thai-ky.show', $theoDoiThaiKy->id)); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <form action="<?php echo e(route('doctor.theo-doi-thai-ky.update', $theoDoiThaiKy->id)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        
        <div class="row">
            <div class="col-lg-8">
                
                <div class="card vc-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user-injured me-2" style="color: #3b82f6;"></i>
                            Thông tin bệnh nhân
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <strong>Bệnh nhân:</strong> <?php echo e($theoDoiThaiKy->user->name); ?><br>
                            <strong>Email:</strong> <?php echo e($theoDoiThaiKy->user->email); ?><br>
                            <?php if($theoDoiThaiKy->user->so_dien_thoai): ?>
                            <strong>SĐT:</strong> <?php echo e($theoDoiThaiKy->user->so_dien_thoai); ?><br>
                            <?php endif; ?>
                            <?php if($theoDoiThaiKy->benh_an_id): ?>
                            <strong>Bệnh án:</strong> #<?php echo e(str_pad($theoDoiThaiKy->benh_an_id, 4, '0', STR_PAD_LEFT)); ?><br>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                
                <div class="card vc-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-baby me-2" style="color: #ec4899;"></i>
                            Thông tin thai kỳ
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ngày kinh cuối (LMP) <span class="text-danger">*</span></label>
                                <input type="date" name="ngay_kinh_cuoi" class="form-control" 
                                       value="<?php echo e(old('ngay_kinh_cuoi', $theoDoiThaiKy->ngay_kinh_cuoi->format('Y-m-d'))); ?>" required>
                                <small class="text-muted">Last Menstrual Period - ngày đầu kỳ kinh cuối</small>
                                <?php $__errorArgs = ['ngay_kinh_cuoi'];
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

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Loại thai <span class="text-danger">*</span></label>
                                <select name="loai_thai" class="form-select" required>
                                    <option value="Đơn thai" <?php echo e(old('loai_thai', $theoDoiThaiKy->loai_thai) === 'Đơn thai' ? 'selected' : ''); ?>>Đơn thai</option>
                                    <option value="Song thai" <?php echo e(old('loai_thai', $theoDoiThaiKy->loai_thai) === 'Song thai' ? 'selected' : ''); ?>>Song thai</option>
                                    <option value="Đa thai" <?php echo e(old('loai_thai', $theoDoiThaiKy->loai_thai) === 'Đa thai' ? 'selected' : ''); ?>>Đa thai</option>
                                </select>
                                <?php $__errorArgs = ['loai_thai'];
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

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Nhóm máu</label>
                                <select name="nhom_mau" class="form-select">
                                    <option value="">-- Chọn --</option>
                                    <option value="A" <?php echo e(old('nhom_mau', $theoDoiThaiKy->nhom_mau) === 'A' ? 'selected' : ''); ?>>A</option>
                                    <option value="B" <?php echo e(old('nhom_mau', $theoDoiThaiKy->nhom_mau) === 'B' ? 'selected' : ''); ?>>B</option>
                                    <option value="AB" <?php echo e(old('nhom_mau', $theoDoiThaiKy->nhom_mau) === 'AB' ? 'selected' : ''); ?>>AB</option>
                                    <option value="O" <?php echo e(old('nhom_mau', $theoDoiThaiKy->nhom_mau) === 'O' ? 'selected' : ''); ?>>O</option>
                                </select>
                                <?php $__errorArgs = ['nhom_mau'];
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

                            <div class="col-md-4 mb-3">
                                <label class="form-label">RH</label>
                                <select name="rh" class="form-select">
                                    <option value="">-- Chọn --</option>
                                    <option value="+" <?php echo e(old('rh', $theoDoiThaiKy->rh) === '+' ? 'selected' : ''); ?>>Dương (+)</option>
                                    <option value="-" <?php echo e(old('rh', $theoDoiThaiKy->rh) === '-' ? 'selected' : ''); ?>>Âm (-)</option>
                                </select>
                                <?php $__errorArgs = ['rh'];
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

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Cân nặng trước mang thai (kg)</label>
                                <input type="number" step="0.1" name="can_nang_truoc_mang_thai" class="form-control"
                                       value="<?php echo e(old('can_nang_truoc_mang_thai', $theoDoiThaiKy->can_nang_truoc_mang_thai)); ?>"
                                       placeholder="VD: 55">
                                <?php $__errorArgs = ['can_nang_truoc_mang_thai'];
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

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Chiều cao (cm)</label>
                                <input type="number" step="0.1" name="chieu_cao" class="form-control"
                                       value="<?php echo e(old('chieu_cao', $theoDoiThaiKy->chieu_cao)); ?>"
                                       placeholder="VD: 160">
                                <?php $__errorArgs = ['chieu_cao'];
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

                            <div class="col-md-6 mb-3">
                                <label class="form-label">BMI trước mang thai</label>
                                <input type="text" class="form-control" 
                                       value="<?php echo e($theoDoiThaiKy->bmi_truoc_mang_thai ? number_format($theoDoiThaiKy->bmi_truoc_mang_thai, 2) : '-'); ?>" 
                                       readonly disabled>
                                <small class="text-muted">Sẽ được tự động tính từ cân nặng và chiều cao</small>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="card vc-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-history me-2" style="color: #f59e0b;"></i>
                            Tiền sử bệnh
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Tiền sử sản khoa</label>
                            <textarea name="tien_su_san_khoa" class="form-control" rows="3"
                                      placeholder="VD: Sẩy thai 1 lần (năm 2020), sinh mổ 1 lần (2018)..."><?php echo e(old('tien_su_san_khoa', $theoDoiThaiKy->tien_su_san_khoa)); ?></textarea>
                            <small class="text-muted">Lịch sử mang thai, sinh nở, sẩy thai, nạo thai...</small>
                            <?php $__errorArgs = ['tien_su_san_khoa'];
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
                            <label class="form-label">Tiền sử bệnh lý</label>
                            <textarea name="tien_su_benh_ly" class="form-control" rows="3"
                                      placeholder="VD: Đái tháo đường, cao huyết áp, bệnh tim mạch..."><?php echo e(old('tien_su_benh_ly', $theoDoiThaiKy->tien_su_benh_ly)); ?></textarea>
                            <small class="text-muted">Các bệnh mãn tính, bệnh nền</small>
                            <?php $__errorArgs = ['tien_su_benh_ly'];
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
                            <label class="form-label">Tiền sử dị ứng</label>
                            <textarea name="di_ung" class="form-control" rows="2"
                                      placeholder="VD: Dị ứng penicillin, hải sản..."><?php echo e(old('di_ung', $theoDoiThaiKy->di_ung)); ?></textarea>
                            <?php $__errorArgs = ['di_ung'];
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
                            <label class="form-label">Ghi chú</label>
                            <textarea name="ghi_chu" class="form-control" rows="2"
                                      placeholder="Ghi chú thêm..."><?php echo e(old('ghi_chu', $theoDoiThaiKy->ghi_chu)); ?></textarea>
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
                    </div>
                </div>

                
                <div class="card vc-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2" style="color: #6366f1;"></i>
                            Trạng thái
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Trạng thái theo dõi</label>
                                <select name="trang_thai" class="form-select">
                                    <option value="Đang theo dõi" <?php echo e(old('trang_thai', $theoDoiThaiKy->trang_thai) === 'Đang theo dõi' ? 'selected' : ''); ?>>Đang theo dõi</option>
                                    <option value="Đã hoàn thành" <?php echo e(old('trang_thai', $theoDoiThaiKy->trang_thai) === 'Đã hoàn thành' ? 'selected' : ''); ?>>Đã hoàn thành</option>
                                    <option value="Tạm dừng" <?php echo e(old('trang_thai', $theoDoiThaiKy->trang_thai) === 'Tạm dừng' ? 'selected' : ''); ?>>Tạm dừng</option>
                                </select>
                                <?php $__errorArgs = ['trang_thai'];
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

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kết quả thai kỳ</label>
                                <input type="text" name="ket_qua_thai_ky" class="form-control" 
                                       value="<?php echo e(old('ket_qua_thai_ky', $theoDoiThaiKy->ket_qua_thai_ky)); ?>"
                                       placeholder="VD: Sinh thường, sinh mổ, sẩy thai...">
                                <?php $__errorArgs = ['ket_qua_thai_ky'];
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
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mb-4">
                    <a href="<?php echo e(route('doctor.theo-doi-thai-ky.show', $theoDoiThaiKy->id)); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Hủy
                    </a>
                    <button type="submit" class="btn vc-btn-primary">
                        <i class="fas fa-save me-2"></i>Cập nhật hồ sơ
                    </button>
                </div>
            </div>

            <div class="col-lg-4">
                
                <div class="card vc-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-box me-2" style="color: #f59e0b;"></i>
                            Thông tin gói dịch vụ
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-0">
                            <p class="mb-2"><strong>Gói hiện tại:</strong> <?php echo e($theoDoiThaiKy->goi_dich_vu ?? 'Gói cơ bản'); ?></p>
                            <p class="mb-2"><strong>Giá:</strong> <span class="text-danger"><?php echo e(number_format($theoDoiThaiKy->gia_tien, 0, ',', '.')); ?> VNĐ</span></p>
                            <p class="mb-0"><strong>Trạng thái:</strong> 
                                <?php if($theoDoiThaiKy->trang_thai_thanh_toan === 'Đã thanh toán'): ?>
                                <span class="badge bg-success">Đã thanh toán</span>
                                <?php else: ?>
                                <span class="badge bg-warning">Chưa thanh toán</span>
                                <?php endif; ?>
                            </p>
                            <hr>
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Thông tin gói dịch vụ không thể chỉnh sửa. Liên hệ nhân viên để thay đổi.
                            </small>
                        </div>
                    </div>
                </div>

                
                <div class="card vc-card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-line me-2" style="color: #10b981;"></i>
                            Thống kê
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-2"><strong>Số lần khám:</strong> <?php echo e($theoDoiThaiKy->lichKhamThai->count() ?? 0); ?> lần</p>
                        <p class="mb-2"><strong>Ngày bắt đầu:</strong> <?php echo e(\Carbon\Carbon::parse($theoDoiThaiKy->ngay_bat_dau)->format('d/m/Y')); ?></p>
                        <?php if($theoDoiThaiKy->ngay_ket_thuc): ?>
                        <p class="mb-2"><strong>Ngày kết thúc:</strong> <?php echo e(\Carbon\Carbon::parse($theoDoiThaiKy->ngay_ket_thuc)->format('d/m/Y')); ?></p>
                        <?php endif; ?>
                        <hr>
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            Cập nhật lần cuối: <?php echo e($theoDoiThaiKy->updated_at->diffForHumans()); ?>

                        </small>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.doctor', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\WORKING\DACN\DACN\resources\views/doctor/theo-doi-thai-ky/edit.blade.php ENDPATH**/ ?>