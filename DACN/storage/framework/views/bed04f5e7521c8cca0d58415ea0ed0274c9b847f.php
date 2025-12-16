

<?php $__env->startSection('title', 'Tạo hồ sơ theo dõi thai kỳ'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: #1f2937;">
                <i class="fas fa-heart me-2" style="color: #ec4899;"></i>
                Tạo hồ sơ theo dõi thai kỳ
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('doctor.dashboard')); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('doctor.theo-doi-thai-ky.index')); ?>">Theo dõi thai kỳ</a></li>
                    <li class="breadcrumb-item active">Tạo mới</li>
                </ol>
            </nav>
        </div>
        <a href="<?php echo e(route('doctor.theo-doi-thai-ky.index')); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <form action="<?php echo e(route('doctor.theo-doi-thai-ky.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        
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
                        <?php if($user): ?>
                        
                        <input type="hidden" name="user_id" value="<?php echo e($user->id); ?>">
                        <?php if(isset($benhAn)): ?>
                        <input type="hidden" name="benh_an_id" value="<?php echo e($benhAn->id); ?>">
                        <?php endif; ?>
                        <div class="alert alert-info">
                            <strong>Bệnh nhân:</strong> <?php echo e($user->name); ?><br>
                            <strong>Email:</strong> <?php echo e($user->email); ?><br>
                            <?php if($user->so_dien_thoai): ?>
                            <strong>SĐT:</strong> <?php echo e($user->so_dien_thoai); ?><br>
                            <?php endif; ?>
                            <?php if(isset($benhAn)): ?>
                            <strong>Bệnh án:</strong> #<?php echo e(str_pad($benhAn->id, 4, '0', STR_PAD_LEFT)); ?><br>
                            <?php endif; ?>
                        </div>
                        <?php else: ?>
                        
                        <div class="mb-3">
                            <label class="form-label">Chọn bệnh nhân <span class="text-danger">*</span></label>
                            <select name="user_id" class="form-select" required id="patientSelect">
                                <option value="">-- Tìm kiếm bệnh nhân --</option>
                                <?php $__currentLoopData = \App\Models\User::where('role', 'patient')->orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($patient->id); ?>" <?php echo e(old('user_id') == $patient->id ? 'selected' : ''); ?>>
                                    <?php echo e($patient->name); ?> - <?php echo e($patient->email); ?> <?php echo e($patient->so_dien_thoai ? '(' . $patient->so_dien_thoai . ')' : ''); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['user_id'];
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
                        <?php endif; ?>
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
                                       value="<?php echo e(old('ngay_kinh_cuoi')); ?>" required>
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
                                    <option value="Đơn thai" <?php echo e(old('loai_thai') === 'Đơn thai' ? 'selected' : ''); ?>>Đơn thai</option>
                                    <option value="Song thai" <?php echo e(old('loai_thai') === 'Song thai' ? 'selected' : ''); ?>>Song thai</option>
                                    <option value="Đa thai" <?php echo e(old('loai_thai') === 'Đa thai' ? 'selected' : ''); ?>>Đa thai</option>
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
                                <label class="form-label">Số lần mang thai (Para) <span class="text-danger">*</span></label>
                                <input type="number" name="so_lan_mang_thai" class="form-control" 
                                       value="<?php echo e(old('so_lan_mang_thai', 1)); ?>" min="1" required>
                                <?php $__errorArgs = ['so_lan_mang_thai'];
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
                                <label class="form-label">Số lần sinh (Gravida) <span class="text-danger">*</span></label>
                                <input type="number" name="so_lan_sinh" class="form-control" 
                                       value="<?php echo e(old('so_lan_sinh', 0)); ?>" min="0" required>
                                <?php $__errorArgs = ['so_lan_sinh'];
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
                                <label class="form-label">Số con còn sống <span class="text-danger">*</span></label>
                                <input type="number" name="so_con_song" class="form-control" 
                                       value="<?php echo e(old('so_con_song', 0)); ?>" min="0" required>
                                <?php $__errorArgs = ['so_con_song'];
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

                
                <div class="card vc-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-weight me-2" style="color: #10b981;"></i>
                            Chỉ số ban đầu
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Cân nặng trước mang thai (kg)</label>
                                <input type="number" name="can_nang_truoc_mang_thai" class="form-control" 
                                       value="<?php echo e(old('can_nang_truoc_mang_thai')); ?>" step="0.1" min="0">
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

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Chiều cao (cm)</label>
                                <input type="number" name="chieu_cao" class="form-control" 
                                       value="<?php echo e(old('chieu_cao')); ?>" step="0.1" min="0">
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

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Nhóm máu</label>
                                <select name="nhom_mau" class="form-select">
                                    <option value="">-- Chọn nhóm máu --</option>
                                    <option value="A" <?php echo e(old('nhom_mau') === 'A' ? 'selected' : ''); ?>>A</option>
                                    <option value="B" <?php echo e(old('nhom_mau') === 'B' ? 'selected' : ''); ?>>B</option>
                                    <option value="AB" <?php echo e(old('nhom_mau') === 'AB' ? 'selected' : ''); ?>>AB</option>
                                    <option value="O" <?php echo e(old('nhom_mau') === 'O' ? 'selected' : ''); ?>>O</option>
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
                                <label class="form-label">Rh</label>
                                <select name="rh" class="form-select">
                                    <option value="">-- Chọn Rh --</option>
                                    <option value="+" <?php echo e(old('rh') === '+' ? 'selected' : ''); ?>>Dương (+)</option>
                                    <option value="-" <?php echo e(old('rh') === '-' ? 'selected' : ''); ?>>Âm (-)</option>
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
                                      placeholder="VD: Sẩy thai 1 lần (năm 2020), sinh mổ 1 lần (2018)..."><?php echo e(old('tien_su_san_khoa')); ?></textarea>
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
                                      placeholder="VD: Đái tháo đường, cao huyết áp, bệnh tim mạch..."><?php echo e(old('tien_su_benh_ly')); ?></textarea>
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
                                      placeholder="VD: Dị ứng penicillin, hải sản..."><?php echo e(old('di_ung')); ?></textarea>
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
                                      placeholder="Ghi chú thêm..."><?php echo e(old('ghi_chu')); ?></textarea>
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
                            <i class="fas fa-dollar-sign me-2" style="color: #f59e0b;"></i>
                            Thông tin dịch vụ
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Gói dịch vụ</label>
                            <select name="goi_dich_vu" class="form-select" id="goiDichVu">
                                <option value="Gói theo dõi thai kỳ cơ bản" data-price="3000000">Gói cơ bản - 3,000,000đ</option>
                                <option value="Gói theo dõi thai kỳ tiêu chuẩn" data-price="5000000">Gói tiêu chuẩn - 5,000,000đ</option>
                                <option value="Gói theo dõi thai kỳ cao cấp" data-price="8000000">Gói cao cấp - 8,000,000đ</option>
                                <option value="Gói theo dõi thai kỳ VIP" data-price="12000000">Gói VIP - 12,000,000đ</option>
                            </select>
                            <small class="text-muted">Chọn gói dịch vụ theo dõi thai kỳ phù hợp</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Giá tiền (VNĐ)</label>
                            <input type="number" name="gia_tien" class="form-control" 
                                   value="<?php echo e(old('gia_tien', 3000000)); ?>" min="0" step="1000" id="giaTien">
                            <?php $__errorArgs = ['gia_tien'];
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

                        <div class="alert alert-info mb-0">
                            <small>
                                <i class="fas fa-info-circle me-1"></i>
                                Gói dịch vụ bao gồm theo dõi định kỳ, tư vấn sức khỏe, lịch tiêm chủng và các dịch vụ khác theo từng gói.
                            </small>
                        </div>
                    </div>
                </div>

                
                <div class="d-flex gap-2 justify-content-end mb-4">
                    <a href="<?php echo e(route('doctor.theo-doi-thai-ky.index')); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Hủy
                    </a>
                    <button type="submit" class="btn vc-btn-primary">
                        <i class="fas fa-save me-2"></i>Tạo hồ sơ
                    </button>
                </div>
            </div>

            <div class="col-lg-4">
                
                <div class="card vc-card">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="fas fa-info-circle me-2" style="color: #10b981;"></i>
                            Hướng dẫn
                        </h6>
                        <ul class="mb-0 small">
                            <li><strong>Ngày kinh cuối (LMP):</strong> Ngày đầu tiên của kỳ kinh cuối cùng</li>
                            <li><strong>Ngày dự sinh (EDD):</strong> Sẽ được tự động tính = LMP + 280 ngày</li>
                            <li><strong>Para/Gravida:</strong> Số lần mang thai và sinh con</li>
                            <li><strong>BMI:</strong> Sẽ được tự động tính từ cân nặng và chiều cao</li>
                            <li>Hệ thống sẽ tự động tạo lịch tiêm chủng khuyến cáo</li>
                        </ul>
                    </div>
                </div>

                
                <div class="card vc-card mt-3">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="fas fa-calendar-check me-2" style="color: #6366f1;"></i>
                            Lịch khám khuyến cáo
                        </h6>
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Tuần thai</th>
                                    <th>Mục đích</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                <tr><td>6-8</td><td>Khám lần đầu</td></tr>
                                <tr><td>11-13</td><td>Sàng lọc Down</td></tr>
                                <tr><td>18-22</td><td>Siêu âm hình thái</td></tr>
                                <tr><td>24-28</td><td>Sàng lọc đái tháo đường</td></tr>
                                <tr><td>32-36</td><td>Đánh giá sức khỏe mẹ-con</td></tr>
                                <tr><td>37-40</td><td>Theo dõi trước sinh</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                
                <div class="card vc-card mt-3">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="fas fa-syringe me-2" style="color: #ef4444;"></i>
                            Vaccine khuyến cáo
                        </h6>
                        <ul class="mb-0 small">
                            <li><strong>Tdap:</strong> Tuần 27-36 (Uốn ván, Bạch hầu, Ho gà)</li>
                            <li><strong>Cúm:</strong> Bất kỳ thời điểm nào</li>
                            <li><strong>Viêm gan B:</strong> Nếu chưa tiêm đủ</li>
                            <li><strong>COVID-19:</strong> Theo khuyến cáo Y tế</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // Tự động cập nhật giá khi chọn gói dịch vụ
    document.getElementById('goiDichVu').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const price = selectedOption.getAttribute('data-price');
        document.getElementById('giaTien').value = price;
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.doctor', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\WORKING\DACN\DACN\resources\views/doctor/theo-doi-thai-ky/create.blade.php ENDPATH**/ ?>