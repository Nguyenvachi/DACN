<?php
    // Phân biệt layout theo role rõ ràng
    $role = auth()->user()->role ?? 'patient';

    $layout = match ($role) {
        'admin' => 'layouts.admin',
        'doctor' => 'layouts.doctor',
        'staff' => 'layouts.staff',
        'patient' => 'layouts.patient-modern',
        default => 'layouts.app',
    };

    $isPatient = $role === 'patient';
?>



<?php if($isPatient): ?>
    <?php $__env->startSection('title', 'Quản lý Hồ sơ cá nhân'); ?>
    <?php $__env->startSection('page-title', 'Quản lý Hồ sơ cá nhân'); ?>
    <?php $__env->startSection('page-subtitle', 'Cập nhật thông tin tài khoản và hồ sơ y tế'); ?>
<?php endif; ?>

<?php $__env->startSection('content'); ?>

<div class="container-fluid py-4">
    <?php if(!$isPatient): ?>
    
    <div class="mb-4">
        <h2 class="fw-bold mb-1">
            <i class="fas fa-user-cog me-2 text-primary"></i>Hồ sơ cá nhân
        </h2>
        <p class="text-muted mb-0">Cập nhật thông tin tài khoản, bảo mật và cài đặt</p>
    </div>
    <?php endif; ?>

    
    <?php if(session('status')): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?php if(session('status') === 'profile-updated'): ?>
                <strong>Thành công!</strong> Thông tin tài khoản đã được cập nhật.
            <?php elseif(session('status') === 'doctor-profile-updated'): ?>
                <strong>Thành công!</strong> Hồ sơ bác sĩ đã được cập nhật.
            <?php elseif(session('status') === 'password-updated'): ?>
                <strong>Thành công!</strong> Mật khẩu đã được thay đổi.
            <?php elseif(session('status') === 'medical-profile-updated'): ?>
                <strong>Thành công!</strong> Hồ sơ y tế đã được cập nhật.
            <?php elseif(session('status') === 'avatar-updated'): ?>
                <strong>Thành công!</strong> Ảnh đại diện đã được cập nhật.
            <?php elseif(session('status') === 'notification-preferences-updated'): ?>
                <strong>Thành công!</strong> Tùy chọn thông báo đã được lưu.
            <?php else: ?>
                <?php echo e(session('status')); ?>

            <?php endif; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    
    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Có lỗi xảy ra!</strong>
            <ul class="mb-0 mt-2">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    
    <ul class="nav nav-tabs mb-4 bg-white rounded shadow-sm" id="profileTabs" role="tablist" style="border: none;">
        <li class="nav-item" role="presentation">
            <button class="nav-link active px-4 py-3" id="account-tab" data-bs-toggle="tab" data-bs-target="#account" type="button" role="tab">
                <i class="fas fa-user-circle me-2"></i>Thông tin tài khoản
            </button>
        </li>
        <?php if($role === 'doctor'): ?>
        <li class="nav-item" role="presentation">
            <button class="nav-link px-4 py-3" id="professional-tab" data-bs-toggle="tab" data-bs-target="#professional" type="button" role="tab">
                <i class="fas fa-user-md me-2"></i>Thông tin chuyên môn
            </button>
        </li>
        <?php endif; ?>
        <?php if(auth()->user()->isPatient()): ?>
        <li class="nav-item" role="presentation">
            <button class="nav-link px-4 py-3" id="medical-tab" data-bs-toggle="tab" data-bs-target="#medical" type="button" role="tab">
                <i class="fas fa-heartbeat me-2"></i>Hồ sơ y tế
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link px-4 py-3" id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notifications" type="button" role="tab">
                <i class="fas fa-bell me-2"></i>Thông báo
            </button>
        </li>
        <?php endif; ?>
    </ul>

    
    <div class="tab-content" id="profileTabsContent">

        
        <div class="tab-pane fade show active" id="account" role="tabpanel">
            <div class="row g-4">
                <div class="col-lg-8">
                    
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-0 pt-4">
                            <h5 class="fw-bold mb-0">
                                <i class="fas fa-info-circle me-2 text-primary"></i>Thông tin cơ bản
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <?php echo $__env->make('profile.partials.update-profile-information-form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    
                    <div class="card border-0 shadow-sm mb-4 border-start border-warning border-3">
                        <div class="card-header bg-warning bg-opacity-10 border-0 pt-4">
                            <h5 class="fw-bold mb-0">
                                <i class="fas fa-shield-alt me-2 text-warning"></i>Bảo mật tài khoản
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <?php echo $__env->make('profile.partials.update-password-form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                    </div>

                    
                    <div class="card border-0 shadow-sm border-start border-danger border-3">
                        <div class="card-header bg-danger bg-opacity-10 border-0 pt-4">
                            <h5 class="fw-bold mb-0">
                                <i class="fas fa-exclamation-triangle me-2 text-danger"></i>Vùng nguy hiểm
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <?php echo $__env->make('profile.partials.delete-user-form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            </div>
        </div>

        <?php if($role === 'doctor'): ?>
        
        <div class="tab-pane fade" id="professional" role="tabpanel">
            <?php
                $bacSi = \App\Models\BacSi::where('user_id', auth()->id())->first();
            ?>
            
            <?php if(!$bacSi): ?>
                <div class="alert alert-danger">
                    <strong>Debug:</strong> Không tìm thấy hồ sơ bác sĩ cho user_id: <?php echo e(auth()->id()); ?>

                </div>
            <?php endif; ?>
            
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header pt-4" style="background: linear-gradient(135deg, #10b981, #059669);">
                            <h5 class="fw-bold mb-0 text-white">
                                <i class="fas fa-stethoscope me-2"></i>Hồ sơ bác sĩ
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <?php if($bacSi): ?>
                            <form action="<?php echo e(route('profile.updateDoctor')); ?>" method="POST" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-user me-1 text-primary"></i>Họ và tên <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="ho_ten" class="form-control <?php $__errorArgs = ['ho_ten'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('ho_ten', $bacSi->ho_ten)); ?>" required>
                                        <?php $__errorArgs = ['ho_ten'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-envelope me-1 text-primary"></i>Email <span class="text-danger">*</span>
                                        </label>
                                        <input type="email" name="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('email', $bacSi->email)); ?>" required>
                                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-phone me-1 text-success"></i>Số điện thoại
                                        </label>
                                        <input type="text" name="so_dien_thoai" class="form-control <?php $__errorArgs = ['so_dien_thoai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('so_dien_thoai', $bacSi->so_dien_thoai)); ?>" placeholder="0123456789">
                                        <?php $__errorArgs = ['so_dien_thoai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-hospital me-1 text-info"></i>Chuyên khoa
                                        </label>
                                        <input type="text" name="chuyen_khoa" class="form-control <?php $__errorArgs = ['chuyen_khoa'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('chuyen_khoa', $bacSi->chuyen_khoa)); ?>" placeholder="Nội khoa, Ngoại khoa, Tim mạch...">
                                        <?php $__errorArgs = ['chuyen_khoa'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-map-marker-alt me-1 text-danger"></i>Địa chỉ
                                    </label>
                                    <input type="text" name="dia_chi" class="form-control <?php $__errorArgs = ['dia_chi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('dia_chi', $bacSi->dia_chi)); ?>" placeholder="Địa chỉ làm việc">
                                    <?php $__errorArgs = ['dia_chi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-graduation-cap me-1 text-warning"></i>Kinh nghiệm (năm)
                                    </label>
                                    <input type="number" name="kinh_nghiem" class="form-control <?php $__errorArgs = ['kinh_nghiem'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('kinh_nghiem', $bacSi->kinh_nghiem)); ?>" placeholder="Số năm kinh nghiệm" min="0" max="99">
                                    <?php $__errorArgs = ['kinh_nghiem'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-info-circle me-1 text-secondary"></i>Mô tả ngắn
                                    </label>
                                    <textarea name="mo_ta" class="form-control <?php $__errorArgs = ['mo_ta'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="4" placeholder="Giới thiệu về bản thân, chuyên môn, thành tích, bằng cấp..."><?php echo e(old('mo_ta', $bacSi->mo_ta)); ?></textarea>
                                    <small class="text-muted">Tối đa 2000 ký tự</small>
                                    <?php $__errorArgs = ['mo_ta'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-image me-1 text-primary"></i>Ảnh đại diện
                                    </label>
                                    <?php if($bacSi->avatar_url): ?>
                                    <div class="mb-2">
                                        <img src="<?php echo e($bacSi->avatar_url); ?>" alt="Avatar" class="rounded-circle border border-3 border-primary" width="100" height="100" style="object-fit: cover;">
                                    </div>
                                    <?php endif; ?>
                                    <input type="file" name="avatar" class="form-control <?php $__errorArgs = ['avatar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" accept="image/jpeg,image/png,image/jpg">
                                    <small class="text-muted">JPG, PNG tối đa 2MB. Chọn file mới để thay đổi.</small>
                                    <?php $__errorArgs = ['avatar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="alert alert-info border-0" style="background: linear-gradient(to right, #e0f2fe, #dbeafe);">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Lưu ý:</strong> Thông tin này sẽ hiển thị công khai cho bệnh nhân khi tìm kiếm bác sĩ.
                                </div>

                                <button type="submit" class="btn btn-lg w-100" style="background: linear-gradient(135deg, #10b981, #059669); color: white;">
                                    <i class="fas fa-save me-2"></i>Cập nhật thông tin
                                </button>
                            </form>
                            <?php else: ?>
                            <div class="alert alert-warning border-0">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Chưa có hồ sơ bác sĩ.</strong> Vui lòng liên hệ quản trị viên để tạo hồ sơ.
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    
                    <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #dbeafe, #e0f2fe);">
                        <div class="card-body text-center p-4">
                            <?php if($bacSi): ?>
                            <div class="mb-3">
                                <?php if($bacSi->avatar_url): ?>
                                <img src="<?php echo e($bacSi->avatar_url); ?>" alt="Avatar" class="rounded-circle border border-4 border-white shadow-lg" width="120" height="120" style="object-fit: cover;">
                                <?php else: ?>
                                <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center border border-4 border-white shadow-lg" style="width: 120px; height: 120px; font-size: 48px; background: linear-gradient(135deg, #10b981, #059669) !important;">
                                    <i class="fas fa-user-md"></i>
                                </div>
                                <?php endif; ?>
                            </div>
                            <h5 class="fw-bold text-dark"><?php echo e($bacSi->ho_ten); ?></h5>
                            <p class="text-muted mb-0">
                                <i class="fas fa-stethoscope me-1"></i>
                                <?php echo e($bacSi->chuyen_khoa ?? 'Chưa cập nhật chuyên khoa'); ?>

                            </p>

                            <?php
                                $totalAppointments = \App\Models\LichHen::where('bac_si_id', $bacSi->id)
                                    ->whereIn('trang_thai', ['Hoàn thành'])
                                    ->count();
                                $totalPatients = \App\Models\LichHen::where('bac_si_id', $bacSi->id)
                                    ->distinct('user_id')
                                    ->count('user_id');
                                $avgRating = \App\Models\DanhGia::where('bac_si_id', $bacSi->id)
                                    ->where('trang_thai', 'approved')
                                    ->avg('rating');
                                $totalReviews = \App\Models\DanhGia::where('bac_si_id', $bacSi->id)
                                    ->where('trang_thai', 'approved')
                                    ->count();
                            ?>

                            <div class="row text-center mt-4">
                                <div class="col-6 mb-3">
                                    <div class="p-3 bg-white rounded shadow-sm">
                                        <div class="fw-bold fs-4" style="color: #10b981;"><?php echo e($totalPatients); ?></div>
                                        <small class="text-muted"><i class="fas fa-users me-1"></i>Bệnh nhân</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="p-3 bg-white rounded shadow-sm">
                                        <div class="fw-bold fs-4 text-warning">
                                            <?php echo e($avgRating ? number_format($avgRating, 1) : '0.0'); ?>

                                            <i class="fas fa-star"></i>
                                        </div>
                                        <small class="text-muted"><i class="fas fa-comment-dots me-1"></i><?php echo e($totalReviews); ?> đánh giá</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="p-3 bg-white rounded shadow-sm">
                                        <div class="fw-bold fs-4" style="color: #3b82f6;"><?php echo e($totalAppointments); ?></div>
                                        <small class="text-muted"><i class="fas fa-calendar-check me-1"></i>Lịch khám</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="p-3 bg-white rounded shadow-sm">
                                        <div class="fw-bold fs-4 text-success"><?php echo e($bacSi->kinh_nghiem ?? 0); ?> năm</div>
                                        <small class="text-muted"><i class="fas fa-graduation-cap me-1"></i>Kinh nghiệm</small>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 pt-3">
                            <h6 class="fw-bold mb-0">
                                <i class="fas fa-toggle-on me-2 text-success"></i>Trạng thái hoạt động
                            </h6>
                        </div>
                        <div class="card-body">
                            <?php if($bacSi): ?>
                            <div class="d-flex align-items-center justify-content-between p-3 rounded" style="background: <?php echo e($bacSi->trang_thai === 'active' ? '#d1fae5' : '#fee2e2'); ?>;">
                                <div>
                                    <div class="fw-bold" style="color: <?php echo e($bacSi->trang_thai === 'active' ? '#065f46' : '#991b1b'); ?>;">
                                        <i class="fas fa-circle me-2" style="font-size: 8px;"></i>
                                        <?php echo e($bacSi->trang_thai === 'active' ? 'Đang hoạt động' : 'Tạm nghỉ'); ?>

                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        <?php echo e($bacSi->trang_thai === 'active' ? 'Bệnh nhân có thể đặt lịch' : 'Tạm thời không nhận lịch'); ?>

                                    </small>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="statusToggle" style="width: 50px; height: 25px; cursor: pointer;" <?php echo e($bacSi->trang_thai === 'active' ? 'checked' : ''); ?>>
                                </div>
                            </div>
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle me-1"></i>
                                Liên hệ quản trị viên để thay đổi trạng thái
                            </small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if(auth()->user()->isPatient()): ?>
        
        <div class="tab-pane fade" id="medical" role="tabpanel">
            <div class="row g-4">
                
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 pt-4">
                            <h5 class="fw-bold mb-0 text-center">
                                <i class="fas fa-camera me-2 text-primary"></i>Ảnh đại diện
                            </h5>
                        </div>
                        <div class="card-body text-center p-4">
                            <img src="<?php echo e($profile && $profile->avatar ? asset('storage/' . $profile->avatar) : asset('images/default-avatar.svg')); ?>"
                                 alt="Avatar" class="rounded-circle mb-3 border border-3 border-primary shadow"
                                 width="150" height="150" id="avatarPreview" style="object-fit: cover;">

                            <form action="<?php echo e(route('profile.uploadAvatar')); ?>" method="POST" enctype="multipart/form-data" id="avatarForm">
                                <?php echo csrf_field(); ?>
                                <div class="mb-3">
                                    <input type="file" class="form-control" name="avatar" accept="image/*" id="avatarInput" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-upload me-2"></i>Tải lên
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 pt-4">
                            <h5 class="fw-bold mb-0">
                                <i class="fas fa-heartbeat me-2 text-danger"></i>Thông tin y tế
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="<?php echo e(route('profile.updateMedical')); ?>" method="POST" id="medicalForm">
                                <?php echo csrf_field(); ?>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-tint me-1"></i>Nhóm máu
                                        </label>
                                        <select name="nhom_mau" class="form-select">
                                                    <option value="">-- Chọn --</option>
                                                    <?php $__currentLoopData = ['A', 'B', 'AB', 'O']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($group); ?>" <?php echo e($profile->nhom_mau == $group ? 'selected' : ''); ?>><?php echo e($group); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Chiều cao (cm)</label>
                                                <input type="number" step="0.1" name="chieu_cao" class="form-control" value="<?php echo e($profile->chieu_cao); ?>">
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Cân nặng (kg)</label>
                                                <input type="number" step="0.1" name="can_nang" class="form-control" value="<?php echo e($profile->can_nang); ?>">
                                            </div>
                                        </div>

                                        <?php if($profile->bmi): ?>
                                        <div class="alert alert-info mb-3">
                                            <strong>BMI:</strong> <?php echo e(number_format($profile->bmi, 1)); ?> - <?php echo e($profile->bmi_category); ?>

                                        </div>
                                        <?php endif; ?>

                                        <div class="mb-3">
                                            <label class="form-label">Dị ứng (mỗi dòng 1 loại)</label>
                                            <textarea name="allergies" class="form-control" rows="3"><?php echo e(is_array($profile->allergies) ? implode("\n", $profile->allergies) : ''); ?></textarea>
                                            <small class="text-muted">Ví dụ: Penicillin, phấn hoa, hải sản...</small>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Tiền sử bệnh</label>
                                            <textarea name="tien_su_benh" class="form-control" rows="3"><?php echo e($profile->tien_su_benh); ?></textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Thuốc đang dùng</label>
                                            <textarea name="thuoc_dang_dung" class="form-control" rows="3"><?php echo e($profile->thuoc_dang_dung); ?></textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Bệnh mãn tính</label>
                                            <textarea name="benh_man_tinh" class="form-control" rows="2"><?php echo e($profile->benh_man_tinh); ?></textarea>
                                        </div>

                                        <hr class="my-4">
                                        <h6 class="mb-3"><i class="bi bi-telephone-fill me-2"></i>Liên hệ khẩn cấp</h6>

                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Họ tên</label>
                                                <input type="text" name="emergency_contact_name" class="form-control" value="<?php echo e($profile->emergency_contact_name); ?>">
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Số điện thoại</label>
                                                <input type="text" name="emergency_contact_phone" class="form-control" value="<?php echo e($profile->emergency_contact_phone); ?>">
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Mối quan hệ</label>
                                                <input type="text" name="emergency_contact_relation" class="form-control" value="<?php echo e($profile->emergency_contact_relation); ?>" placeholder="Vợ/chồng, con, bạn...">
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-success">
                                            <i class="bi bi-check-circle me-1"></i>Lưu thông tin y tế
                                        </button>
                                    </form>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="tab-pane fade" id="notifications" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-bell me-2 text-warning"></i>Cài đặt thông báo
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="<?php echo e(route('profile.updateNotifications')); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        <h6 class="mb-3 fw-bold">
                            <i class="fas fa-envelope me-2 text-primary"></i>Thông báo qua Email
                        </h6>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="email_appointment_reminder" value="1"
                                   id="emailReminder" <?php echo e($preferences->email_appointment_reminder ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="emailReminder">
                                Nhắc lịch hẹn sắp tới
                            </label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="email_appointment_confirmed" value="1"
                                   id="emailConfirmed" <?php echo e($preferences->email_appointment_confirmed ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="emailConfirmed">
                                Xác nhận lịch hẹn
                            </label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="email_appointment_cancelled" value="1"
                                   id="emailCancelled" <?php echo e($preferences->email_appointment_cancelled ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="emailCancelled">
                                Hủy lịch hẹn
                            </label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="email_test_results" value="1"
                                   id="emailTestResults" <?php echo e($preferences->email_test_results ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="emailTestResults">
                                Kết quả xét nghiệm
                            </label>
                        </div>

                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" name="email_promotions" value="1"
                                   id="emailPromotions" <?php echo e($preferences->email_promotions ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="emailPromotions">
                                Khuyến mãi và tin tức
                            </label>
                        </div>

                        <hr class="my-4">

                        <h6 class="mb-3 fw-bold">
                            <i class="fas fa-sms me-2 text-success"></i>Thông báo qua SMS
                        </h6>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="sms_appointment_reminder" value="1"
                                   id="smsReminder" <?php echo e($preferences->sms_appointment_reminder ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="smsReminder">
                                Nhắc lịch hẹn sắp tới
                            </label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="sms_appointment_confirmed" value="1"
                                   id="smsConfirmed" <?php echo e($preferences->sms_appointment_confirmed ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="smsConfirmed">
                                Xác nhận lịch hẹn
                            </label>
                        </div>

                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" name="sms_appointment_cancelled" value="1"
                                   id="smsCancelled" <?php echo e(($preferences->sms_appointment_cancelled ?? false) ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="smsCancelled">
                                Hủy lịch hẹn
                            </label>
                        </div>

                        <hr class="my-4">

                        <h6 class="mb-3 fw-bold">
                            <i class="fas fa-clock me-2 text-info"></i>Cài đặt nhắc nhở
                        </h6>
                        <div class="mb-4">
                            <label class="form-label">Gửi nhắc nhở trước lịch hẹn</label>
                            <select name="reminder_hours_before" class="form-select" style="max-width: 250px;">
                                <option value="2" <?php echo e($preferences->reminder_hours_before == 2 ? 'selected' : ''); ?>>2 giờ trước</option>
                                <option value="6" <?php echo e($preferences->reminder_hours_before == 6 ? 'selected' : ''); ?>>6 giờ trước</option>
                                <option value="12" <?php echo e($preferences->reminder_hours_before == 12 ? 'selected' : ''); ?>>12 giờ trước</option>
                                <option value="24" <?php echo e($preferences->reminder_hours_before == 24 ? 'selected' : ''); ?>>1 ngày trước</option>
                                <option value="48" <?php echo e($preferences->reminder_hours_before == 48 ? 'selected' : ''); ?>>2 ngày trước</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>Lưu cài đặt
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
    .nav-tabs .nav-link {
        color: #6c757d;
        font-weight: 500;
        border: none;
        transition: all 0.3s;
    }
    .nav-tabs .nav-link:hover {
        background-color: #f8f9fa;
        color: #0d6efd;
    }
    .nav-tabs .nav-link.active {
        background-color: #0d6efd;
        color: white;
        border: none;
    }
    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        // Avatar preview
        document.getElementById('avatarInput')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatarPreview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // Avatar form AJAX
        document.getElementById('avatarForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Đang tải...';

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✅ Cập nhật ảnh đại diện thành công!');
                    location.reload();
                } else {
                    alert('❌ ' + (data.message || 'Có lỗi xảy ra'));
                }
            })
            .catch(error => {
                alert('❌ Lỗi: ' + error.message);
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-upload me-1"></i>Tải ảnh lên';
            });
        });

        // Medical form AJAX
        document.getElementById('medicalForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            // Convert allergies textarea to array
            const allergiesText = formData.get('allergies');
            formData.delete('allergies');
            if (allergiesText && allergiesText.trim()) {
                const allergiesArray = allergiesText.split('\n').filter(line => line.trim());
                allergiesArray.forEach((allergy, index) => {
                    formData.append(`allergies[${index}]`, allergy.trim());
                });
            }

            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Đang lưu...';

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✅ Cập nhật thông tin y tế thành công!');
                    location.reload();
                } else {
                    alert('❌ ' + (data.message || 'Có lỗi xảy ra'));
                }
            })
            .catch(error => {
                alert('❌ Lỗi: ' + error.message);
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-check-circle me-1"></i>Lưu thông tin y tế';
            });
        });
    </script>
    <?php $__env->stopPush(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\WORKING\DACN\DACN\resources\views/profile/edit.blade.php ENDPATH**/ ?>