

<?php $__env->startSection('page-title', 'Tổng Quan'); ?>

<?php $__env->startSection('content'); ?>

<?php if(!$nhanVien): ?>
    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.alert','data' => ['type' => 'warning','title' => 'Chưa có hồ sơ nhân viên','dismissible' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'warning','title' => 'Chưa có hồ sơ nhân viên','dismissible' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
        Tài khoản của bạn chưa được liên kết với hồ sơ nhân viên. Vui lòng liên hệ quản trị viên.
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
<?php else: ?>
    <?php
        $roleLabels = [
            'admin' => 'Quản trị viên',
            'doctor' => 'Bác sĩ',
            'staff' => 'Nhân viên',
            'patient' => 'Bệnh nhân',
        ];
        $currentRole = optional($nhanVien->user)->role;
        $currentRoleLabel = $roleLabels[$currentRole ?? ''] ?? 'Nhân viên';
    ?>

    
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="rounded-circle bg-white bg-opacity-25 p-3 d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                                        <i class="bi bi-person-circle fs-1 text-white"></i>
                                    </div>
                                </div>
                                <div class="text-white">
                                    <h3 class="mb-1 fw-bold">Xin chào, <?php echo e($nhanVien->ho_ten); ?>! 👋</h3>
                                    <p class="mb-0 opacity-90 fs-6">
                                        <span class="badge bg-white bg-opacity-25 text-white me-2"><?php echo e($currentRoleLabel); ?></span>
                                        <?php echo e(now()->locale('vi')->isoFormat('dddd, D MMMM YYYY')); ?>

                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 text-lg-end text-start mt-3 mt-lg-0">
                            <div class="text-white">
                                <div class="fs-2 fw-bold mb-1" id="currentTime"><?php echo e(now()->format('H:i:s')); ?></div>
                                <small class="opacity-90">Giờ hệ thống</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 56px; height: 56px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <i class="bi bi-calendar-check text-white fs-3"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <small class="text-muted text-uppercase d-block mb-1" style="font-size: 0.75rem; font-weight: 600;">Lịch hẹn hôm nay</small>
                            <h3 class="mb-0 fw-bold"><?php echo e($statistics['lich_hen_hom_nay'] ?? 0); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body p-4">
                    <a href="<?php echo e(route('staff.donthuoc.dang-cho')); ?>" class="text-decoration-none text-dark">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 56px; height: 56px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                                    <i class="bi bi-prescription2 text-white fs-3"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <small class="text-muted text-uppercase d-block mb-1" style="font-size: 0.75rem; font-weight: 600;">Đơn thuốc chờ cấp</small>
                                <h3 class="mb-0 fw-bold"><?php echo e(\App\Models\DonThuoc::whereNull('ngay_cap_thuoc')->count()); ?></h3>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 56px; height: 56px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                                <i class="bi bi-exclamation-triangle text-white fs-3"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <small class="text-muted text-uppercase d-block mb-1" style="font-size: 0.75rem; font-weight: 600;">HĐ chưa thanh toán</small>
                            <h3 class="mb-0 fw-bold"><?php echo e($statistics['hoa_don_chua_thanh_toan'] ?? 0); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 56px; height: 56px; background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                                <i class="bi bi-check-circle text-white fs-3"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <small class="text-muted text-uppercase d-block mb-1" style="font-size: 0.75rem; font-weight: 600;">Đã cấp hôm nay</small>
                            <h3 class="mb-0 fw-bold"><?php echo e(\App\Models\DonThuoc::whereDate('ngay_cap_thuoc', today())->count()); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row g-4 mb-4">
        
        <div class="col-lg-6">
            
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-receipt text-warning me-2"></i>Bệnh án cần xử lý
                        </h5>
                        <span class="badge bg-warning rounded-pill px-3"><?php echo e($benhAnCanXuLy->count()); ?></span>
                    </div>
                </div>
                <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                    <?php if($benhAnCanXuLy->isEmpty()): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-check-circle-fill text-success d-block mb-3" style="font-size: 3rem;"></i>
                            <h6 class="text-muted">Không có bệnh án cần xử lý</h6>
                            <p class="text-muted small mb-0">Tất cả bệnh án đã có hóa đơn</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php $__currentLoopData = $benhAnCanXuLy; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ba): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="list-group-item border-0 px-0 py-3">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                                <i class="bi bi-person-fill text-primary fs-5"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-semibold"><?php echo e($ba->benhNhan->name ?? 'N/A'); ?></h6>
                                            <div class="small text-muted mb-2">
                                                <i class="bi bi-calendar3 me-1"></i><?php echo e($ba->ngay_kham); ?>

                                                <span class="mx-2">•</span>
                                                <i class="bi bi-person-badge me-1"></i><?php echo e($ba->bacSi->ho_ten ?? 'N/A'); ?>

                                            </div>
                                            <span class="badge bg-success-subtle text-success border border-success">
                                                <i class="bi bi-check-circle me-1"></i>Hoàn thành khám
                                            </span>
                                        </div>
                                        <div class="flex-shrink-0 ms-3">
                                            <div class="btn-group-vertical btn-group-sm">
                                                <a href="<?php echo e(route('staff.benhan.toa-thuoc', $ba)); ?>" 
                                                   class="btn btn-outline-info" title="Xem toa thuốc">
                                                    <i class="bi bi-prescription2 me-1"></i>Toa
                                                </a>
                                                <a href="<?php echo e(route('staff.hoadon.create-from-benh-an', $ba)); ?>" 
                                                   class="btn btn-outline-warning" title="Tạo hóa đơn">
                                                    <i class="bi bi-receipt me-1"></i>HĐ
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php if($statistics['benh_an_can_tao_hoa_don'] > 5): ?>
                            <div class="text-center border-top pt-3 mt-2">
                                <a href="<?php echo e(route('admin.benhan.index')); ?>" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-arrow-right-circle me-1"></i>
                                    Xem tất cả <?php echo e($statistics['benh_an_can_tao_hoa_don']); ?> bệnh án
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="col-lg-6">
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-person-badge text-success me-2"></i>Thông tin cá nhân
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-person-circle fs-2 text-primary"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <small class="text-muted text-uppercase d-block mb-1" style="font-size: 0.7rem;">Họ và tên</small>
                                    <div class="fw-semibold"><?php echo e($nhanVien->ho_ten); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-briefcase fs-2 text-success"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <small class="text-muted text-uppercase d-block mb-1" style="font-size: 0.7rem;">Chức vụ</small>
                                    <div class="fw-semibold"><?php echo e($currentRoleLabel); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-envelope fs-4 text-info"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <small class="text-muted text-uppercase d-block mb-1" style="font-size: 0.7rem;">Email</small>
                                    <div class="fw-semibold small"><?php echo e($nhanVien->email_cong_viec); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-telephone fs-4 text-warning"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <small class="text-muted text-uppercase d-block mb-1" style="font-size: 0.7rem;">Điện thoại</small>
                                    <div class="fw-semibold"><?php echo e($nhanVien->so_dien_thoai ?? 'Chưa cập nhật'); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-lightning-charge text-warning me-2"></i>Thao tác nhanh
                    </h5>
                </div>
                <div class="card-body p-3">
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="<?php echo e(route('staff.checkin.index')); ?>" class="btn btn-outline-primary w-100 py-3 d-flex flex-column align-items-center">
                                <i class="bi bi-person-check-fill fs-2 mb-2"></i>
                                <span class="small">Check-in</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="<?php echo e(route('staff.hoadon.index')); ?>" class="btn btn-outline-success w-100 py-3 d-flex flex-column align-items-center">
                                <i class="bi bi-receipt fs-2 mb-2"></i>
                                <span class="small">Hóa đơn</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="<?php echo e(route('admin.benhan.index')); ?>" class="btn btn-outline-info w-100 py-3 d-flex flex-column align-items-center">
                                <i class="bi bi-journal-medical fs-2 mb-2"></i>
                                <span class="small">Bệnh án</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="<?php echo e(route('staff.queue.index')); ?>" class="btn btn-outline-warning w-100 py-3 d-flex flex-column align-items-center">
                                <i class="bi bi-list-ol fs-2 mb-2"></i>
                                <span class="small">Hàng đợi</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 fw-bold">
                                <i class="bi bi-calendar3 text-info me-2"></i>Lịch làm việc tuần này
                            </h5>
                            <small class="text-muted">
                                Từ <?php echo e(\Carbon\Carbon::now()->startOfWeek()->format('d/m')); ?> 
                                đến <?php echo e(\Carbon\Carbon::now()->endOfWeek()->format('d/m/Y')); ?>

                            </small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php if($caTuanNay->isEmpty()): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-calendar2-x text-muted d-block mb-3" style="font-size: 3rem;"></i>
                            <h6 class="text-muted">Không có ca làm việc</h6>
                            <p class="text-muted small mb-0">Không có lịch nào trong tuần này</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th><i class="bi bi-calendar3 me-1"></i>Ngày</th>
                                        <th><i class="bi bi-calendar-day me-1"></i>Thứ</th>
                                        <th><i class="bi bi-clock me-1"></i>Giờ làm việc</th>
                                        <th><i class="bi bi-sticky me-1"></i>Ghi chú</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $caTuanNay; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ca): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="<?php echo e(\Carbon\Carbon::parse($ca->ngay)->isToday() ? 'table-warning' : ''); ?>">
                                            <td>
                                                <strong><?php echo e(\Carbon\Carbon::parse($ca->ngay)->format('d/m/Y')); ?></strong>
                                                <?php if(\Carbon\Carbon::parse($ca->ngay)->isToday()): ?>
                                                    <span class="badge bg-warning text-dark ms-2">
                                                        <i class="bi bi-star-fill me-1"></i>Hôm nay
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e(\Carbon\Carbon::parse($ca->ngay)->locale('vi')->isoFormat('dddd')); ?></td>
                                            <td>
                                                <span class="badge bg-primary rounded-pill"><?php echo e(\Carbon\Carbon::parse($ca->bat_dau)->format('H:i')); ?></span>
                                                <i class="bi bi-arrow-right mx-2"></i>
                                                <span class="badge bg-success rounded-pill"><?php echo e(\Carbon\Carbon::parse($ca->ket_thuc)->format('H:i')); ?></span>
                                            </td>
                                            <td><?php echo e($ca->ghi_chu ?? '—'); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Update time every second
function updateTime() {
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    document.getElementById('currentTime').textContent = `${hours}:${minutes}:${seconds}`;
}

setInterval(updateTime, 1000);
updateTime();
</script>
<?php $__env->stopPush(); ?>

<style>
.hover-lift {
    transition: all 0.3s ease;
}
.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
.card {
    transition: all 0.2s ease;
}
.list-group-item {
    transition: background-color 0.2s ease;
}
.list-group-item:hover {
    background-color: #f8f9fa;
}
</style>

<?php echo $__env->make('layouts.staff', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\WORKING\DACN\DACN\resources\views/staff/dashboard.blade.php ENDPATH**/ ?>