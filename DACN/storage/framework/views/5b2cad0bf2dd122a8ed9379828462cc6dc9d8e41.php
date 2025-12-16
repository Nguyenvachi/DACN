

<?php $__env->startSection('title', 'Hồ sơ theo dõi thai kỳ #' . $theoDoiThaiKy->id); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: #1f2937;">
                <i class="fas fa-heartbeat me-2" style="color: #ec4899;"></i>
                Hồ sơ theo dõi thai kỳ #<?php echo e($theoDoiThaiKy->id); ?>

            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('doctor.dashboard')); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('doctor.theo-doi-thai-ky.index')); ?>">Theo dõi thai kỳ</a></li>
                    <li class="breadcrumb-item active">Chi tiết #<?php echo e($theoDoiThaiKy->id); ?></li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <?php if($theoDoiThaiKy->benh_an_id): ?>
            <a href="<?php echo e(route('doctor.benhan.edit', $theoDoiThaiKy->benh_an_id)); ?>" class="btn btn-outline-primary">
                <i class="fas fa-file-medical me-2"></i>Xem bệnh án
            </a>
            <?php endif; ?>
            <a href="<?php echo e(route('doctor.theo-doi-thai-ky.edit', $theoDoiThaiKy->id)); ?>" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Sửa hồ sơ
            </a>
            <a href="<?php echo e(route('doctor.theo-doi-thai-ky.index')); ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

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
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Họ tên:</strong> <?php echo e($theoDoiThaiKy->user->name); ?></p>
                            <p class="mb-2"><strong>Email:</strong> <?php echo e($theoDoiThaiKy->user->email); ?></p>
                            <?php if($theoDoiThaiKy->user->so_dien_thoai): ?>
                            <p class="mb-2"><strong>SĐT:</strong> <?php echo e($theoDoiThaiKy->user->so_dien_thoai); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <?php if($theoDoiThaiKy->user->ngay_sinh): ?>
                            <p class="mb-2"><strong>Ngày sinh:</strong> <?php echo e(\Carbon\Carbon::parse($theoDoiThaiKy->user->ngay_sinh)->format('d/m/Y')); ?></p>
                            <?php endif; ?>
                            <p class="mb-2"><strong>Nhóm máu:</strong> <?php echo e($theoDoiThaiKy->nhom_mau ?? 'Chưa xác định'); ?></p>
                            <?php if($theoDoiThaiKy->rh): ?>
                            <p class="mb-2"><strong>RH:</strong> <?php echo e($theoDoiThaiKy->rh); ?></p>
                            <?php endif; ?>
                        </div>
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
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Ngày kinh cuối (LMP):</strong> <?php echo e(\Carbon\Carbon::parse($theoDoiThaiKy->ngay_kinh_cuoi)->format('d/m/Y')); ?></p>
                            <p class="mb-2"><strong>Ngày dự sinh (EDD):</strong> <?php echo e(\Carbon\Carbon::parse($theoDoiThaiKy->ngay_du_sinh)->format('d/m/Y')); ?></p>
                            <p class="mb-2"><strong>Tuổi thai hiện tại:</strong> <span class="badge bg-info"><?php echo e($tuoiThaiHienTai['tuan']); ?> tuần <?php echo e($tuoiThaiHienTai['ngay']); ?> ngày</span></p>
                            <p class="mb-2"><strong>Loại thai:</strong> <?php echo e($theoDoiThaiKy->loai_thai); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Para/Gravida:</strong> P<?php echo e($theoDoiThaiKy->so_lan_sinh); ?> G<?php echo e($theoDoiThaiKy->so_lan_mang_thai); ?></p>
                            <p class="mb-2"><strong>Số con còn sống:</strong> <?php echo e($theoDoiThaiKy->so_con_song); ?></p>
                            <p class="mb-2"><strong>Cân nặng trước mang thai:</strong> <?php echo e($theoDoiThaiKy->can_nang_truoc_mang_thai ?? '-'); ?> kg</p>
                            <p class="mb-2"><strong>Chiều cao:</strong> <?php echo e($theoDoiThaiKy->chieu_cao ?? '-'); ?> cm</p>
                            <?php if($theoDoiThaiKy->bmi_truoc_mang_thai): ?>
                            <p class="mb-2"><strong>BMI:</strong> <?php echo e(number_format($theoDoiThaiKy->bmi_truoc_mang_thai, 2)); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if($theoDoiThaiKy->tien_su_san_khoa): ?>
                    <div class="mt-3">
                        <strong>Tiền sử sản khoa:</strong>
                        <p class="text-muted mb-0"><?php echo e($theoDoiThaiKy->tien_su_san_khoa); ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if($theoDoiThaiKy->tien_su_benh_ly): ?>
                    <div class="mt-3">
                        <strong>Tiền sử bệnh lý:</strong>
                        <p class="text-muted mb-0"><?php echo e($theoDoiThaiKy->tien_su_benh_ly); ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if($theoDoiThaiKy->di_ung): ?>
                    <div class="mt-3">
                        <strong>Dị ứng:</strong>
                        <p class="text-danger mb-0"><?php echo e($theoDoiThaiKy->di_ung); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="card vc-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-check me-2" style="color: #10b981;"></i>
                        Lịch sử khám thai (<?php echo e($theoDoiThaiKy->lichKhamThai->count()); ?> lần)
                    </h5>
                    <a href="<?php echo e(route('doctor.lich-kham-thai.create', $theoDoiThaiKy->id)); ?>" class="btn btn-sm vc-btn-primary">
                        <i class="fas fa-plus me-2"></i>Thêm lịch khám
                    </a>
                </div>
                <div class="card-body">
                    <?php if($theoDoiThaiKy->lichKhamThai->count() > 0): ?>
                    <div class="timeline">
                        <?php $__currentLoopData = $theoDoiThaiKy->lichKhamThai; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $lichKham): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="timeline-item mb-4 pb-4 <?php echo e(!$loop->last ? 'border-bottom' : ''); ?>">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="mb-1">
                                        <i class="fas fa-calendar-day me-2 text-primary"></i>
                                        Lần <?php echo e($theoDoiThaiKy->lichKhamThai->count() - $index); ?>: <?php echo e(\Carbon\Carbon::parse($lichKham->ngay_kham)->format('d/m/Y')); ?>

                                    </h6>
                                    <small class="text-muted">
                                        <i class="fas fa-user-md me-1"></i><?php echo e($lichKham->bacSi->user->name ?? 'N/A'); ?>

                                    </small>
                                </div>
                                <span class="badge bg-info"><?php echo e($lichKham->tuan_thai); ?> tuần <?php echo e($lichKham->ngay_thai); ?> ngày</span>
                            </div>

                            <div class="row g-2 mb-2">
                                <?php if($lichKham->can_nang): ?>
                                <div class="col-6 col-md-3">
                                    <small class="text-muted d-block">Cân nặng</small>
                                    <strong><?php echo e($lichKham->can_nang); ?> kg</strong>
                                </div>
                                <?php endif; ?>
                                <?php if($lichKham->huyet_ap_tam_thu && $lichKham->huyet_ap_tam_truong): ?>
                                <div class="col-6 col-md-3">
                                    <small class="text-muted d-block">Huyết áp</small>
                                    <strong><?php echo e($lichKham->huyet_ap_tam_thu); ?>/<?php echo e($lichKham->huyet_ap_tam_truong); ?></strong>
                                </div>
                                <?php endif; ?>
                                <?php if($lichKham->nhip_tim_thai): ?>
                                <div class="col-6 col-md-3">
                                    <small class="text-muted d-block">Tim thai</small>
                                    <strong><?php echo e($lichKham->nhip_tim_thai); ?> bpm</strong>
                                </div>
                                <?php endif; ?>
                                <?php if($lichKham->chieu_cao_tu_cung): ?>
                                <div class="col-6 col-md-3">
                                    <small class="text-muted d-block">Chiều cao TC</small>
                                    <strong><?php echo e($lichKham->chieu_cao_tu_cung); ?> cm</strong>
                                </div>
                                <?php endif; ?>
                            </div>

                            <?php if($lichKham->trieu_chung): ?>
                            <div class="mb-2">
                                <small class="text-muted">Triệu chứng:</small>
                                <p class="mb-0"><?php echo e($lichKham->trieu_chung); ?></p>
                            </div>
                            <?php endif; ?>

                            <?php if($lichKham->danh_gia): ?>
                            <div class="mb-2">
                                <small class="text-muted">Đánh giá:</small>
                                <p class="mb-0"><?php echo e($lichKham->danh_gia); ?></p>
                            </div>
                            <?php endif; ?>

                            <?php if($lichKham->tu_van): ?>
                            <div class="mb-2">
                                <small class="text-muted">Tư vấn:</small>
                                <p class="mb-0"><?php echo e($lichKham->tu_van); ?></p>
                            </div>
                            <?php endif; ?>

                            <?php if($lichKham->hen_kham_lai): ?>
                            <div class="alert alert-info mb-0 py-2">
                                <i class="fas fa-clock me-2"></i>
                                <strong>Hẹn khám lại:</strong> <?php echo e(\Carbon\Carbon::parse($lichKham->hen_kham_lai)->format('d/m/Y')); ?>

                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-3">Chưa có lịch khám nào</p>
                        <a href="<?php echo e(route('doctor.lich-kham-thai.create', $theoDoiThaiKy->id)); ?>" class="btn btn-sm vc-btn-primary">
                            <i class="fas fa-plus me-2"></i>Tạo lịch khám đầu tiên
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            
            <div class="card vc-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-box me-2" style="color: #f59e0b;"></i>
                        Gói dịch vụ
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-primary fs-6"><?php echo e($theoDoiThaiKy->goi_dich_vu ?? 'Gói cơ bản'); ?></span>
                        <?php if($theoDoiThaiKy->trang_thai_thanh_toan === 'Đã thanh toán'): ?>
                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Đã thanh toán</span>
                        <?php else: ?>
                        <span class="badge bg-warning"><i class="fas fa-clock me-1"></i>Chưa thanh toán</span>
                        <?php endif; ?>
                    </div>
                    <p class="mb-2"><strong>Giá gói:</strong> <span class="text-danger fs-5"><?php echo e(number_format($theoDoiThaiKy->gia_tien, 0, ',', '.')); ?> VNĐ</span></p>
                    <p class="mb-2"><strong>Ngày bắt đầu:</strong> <?php echo e(\Carbon\Carbon::parse($theoDoiThaiKy->ngay_bat_dau)->format('d/m/Y')); ?></p>
                    <?php if($theoDoiThaiKy->ngay_ket_thuc): ?>
                    <p class="mb-2"><strong>Ngày kết thúc:</strong> <?php echo e(\Carbon\Carbon::parse($theoDoiThaiKy->ngay_ket_thuc)->format('d/m/Y')); ?></p>
                    <?php endif; ?>
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
                    <p class="mb-2">
                        <strong>Trạng thái theo dõi:</strong>
                        <?php if($theoDoiThaiKy->trang_thai === 'Đang theo dõi'): ?>
                        <span class="badge bg-success">Đang theo dõi</span>
                        <?php elseif($theoDoiThaiKy->trang_thai === 'Đã hoàn thành'): ?>
                        <span class="badge bg-info">Đã hoàn thành</span>
                        <?php else: ?>
                        <span class="badge bg-secondary"><?php echo e($theoDoiThaiKy->trang_thai); ?></span>
                        <?php endif; ?>
                    </p>
                    <?php if($theoDoiThaiKy->ket_qua_thai_ky): ?>
                    <p class="mb-2"><strong>Kết quả:</strong> <?php echo e($theoDoiThaiKy->ket_qua_thai_ky); ?></p>
                    <?php endif; ?>
                    <?php if($theoDoiThaiKy->ghi_chu): ?>
                    <div class="mt-3">
                        <strong>Ghi chú:</strong>
                        <p class="text-muted mb-0"><?php echo e($theoDoiThaiKy->ghi_chu); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="card vc-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-lightbulb me-2" style="color: #f59e0b;"></i>
                        Lịch khám khuyến cáo
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless mb-0">
                            <thead>
                                <tr>
                                    <th>Tuần thai</th>
                                    <th>Mục đích</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="badge bg-info">6-8</span></td>
                                    <td><small>Khám lần đầu</small></td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-info">11-13</span></td>
                                    <td><small>Sàng lọc Down</small></td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-info">18-22</span></td>
                                    <td><small>Siêu âm hình thái</small></td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-info">24-28</span></td>
                                    <td><small>Sàng lọc đái tháo đường</small></td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-info">32-36</span></td>
                                    <td><small>Đánh giá thai nhi</small></td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-info">37-40</span></td>
                                    <td><small>Chuẩn bị sinh</small></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline-item {
    position: relative;
}
.timeline-item::before {
    content: '';
    position: absolute;
    left: -20px;
    top: 8px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #3b82f6;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #3b82f6;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.doctor', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\WORKING\DACN\DACN\resources\views/doctor/theo-doi-thai-ky/show.blade.php ENDPATH**/ ?>