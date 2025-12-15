

<?php $__env->startSection('title', 'Chi tiết siêu âm'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: #1f2937;">
                <i class="fas fa-baby me-2" style="color: #ec4899;"></i>
                Chi tiết kết quả siêu âm
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('doctor.dashboard')); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('doctor.benhan.show', $sieuAm->benh_an_id)); ?>">Bệnh án #<?php echo e($sieuAm->benh_an_id); ?></a></li>
                    <li class="breadcrumb-item active">Chi tiết siêu âm</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('doctor.sieu-am.edit', $sieuAm)); ?>" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Chỉnh sửa
            </a>
            <a href="<?php echo e(route('doctor.benhan.show', $sieuAm->benh_an_id)); ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        
        <div class="col-lg-8">
            <div class="card vc-card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2" style="color: #ec4899;"></i>
                        Thông tin siêu âm
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Loại siêu âm:</strong> <?php echo e($sieuAm->loai_sieu_am); ?></p>
                            <p class="mb-2"><strong>Ngày chỉ định:</strong> <?php echo e(\Carbon\Carbon::parse($sieuAm->ngay_chi_dinh)->format('d/m/Y')); ?></p>
                            <p class="mb-2"><strong>Ngày thực hiện:</strong> <?php echo e($sieuAm->ngay_thuc_hien ? \Carbon\Carbon::parse($sieuAm->ngay_thuc_hien)->format('d/m/Y') : 'Chưa thực hiện'); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Trạng thái:</strong>
                                <?php if($sieuAm->trang_thai === 'Hoàn thành' || $sieuAm->trang_thai === 'Đã có kết quả'): ?>
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>Hoàn thành
                                    </span>
                                <?php elseif($sieuAm->trang_thai === 'Đang thực hiện'): ?>
                                    <span class="badge bg-info">
                                        <i class="fas fa-spinner me-1"></i>Đang thực hiện
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-warning">
                                        <i class="fas fa-hourglass-half me-1"></i>Chờ thực hiện
                                    </span>
                                <?php endif; ?>
                            </p>
                            <p class="mb-2"><strong>Giá tiền:</strong> <span class="text-primary"><?php echo e(number_format($sieuAm->gia_tien ?? 0, 0, ',', '.')); ?> đ</span></p>
                        </div>
                    </div>

                    <?php if($sieuAm->ly_do_chi_dinh): ?>
                    <div class="alert alert-info">
                        <strong><i class="fas fa-notes-medical me-2"></i>Lý do chỉ định:</strong>
                        <p class="mb-0 mt-2"><?php echo e($sieuAm->ly_do_chi_dinh); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <?php if($sieuAm->tuoi_thai_tuan): ?>
            <div class="card vc-card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt me-2" style="color: #10b981;"></i>
                        Thông tin thai kỳ
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Tuổi thai:</strong> 
                                <?php echo e($sieuAm->tuoi_thai_tuan); ?> tuần
                                <?php if($sieuAm->tuoi_thai_ngay): ?> <?php echo e($sieuAm->tuoi_thai_ngay); ?> ngày <?php endif; ?>
                            </p>
                            <?php if($sieuAm->can_nang_uoc_tinh): ?>
                            <p class="mb-2"><strong>Cân nặng ước tính:</strong> <?php echo e($sieuAm->can_nang_uoc_tinh); ?> gram</p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <?php if($sieuAm->luong_nuoc_oi): ?>
                            <p class="mb-2"><strong>Lượng nước ối:</strong> <?php echo e($sieuAm->luong_nuoc_oi); ?> ml</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            
            <?php if($sieuAm->chieu_dai_dau_mong || $sieuAm->duong_kinh_hai_dinh || $sieuAm->chu_vi_bung || $sieuAm->chieu_dai_xuong_dui): ?>
            <div class="card vc-card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-ruler me-2" style="color: #8b5cf6;"></i>
                        Các chỉ số thai nhi
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php if($sieuAm->chieu_dai_dau_mong): ?>
                        <div class="col-md-6 mb-3">
                            <strong>Chiều dài đầu mông (CRL):</strong> <?php echo e($sieuAm->chieu_dai_dau_mong); ?> mm
                        </div>
                        <?php endif; ?>
                        <?php if($sieuAm->duong_kinh_hai_dinh): ?>
                        <div class="col-md-6 mb-3">
                            <strong>Đường kính hai đỉnh (BPD):</strong> <?php echo e($sieuAm->duong_kinh_hai_dinh); ?> mm
                        </div>
                        <?php endif; ?>
                        <?php if($sieuAm->chu_vi_bung): ?>
                        <div class="col-md-6 mb-3">
                            <strong>Chu vi bụng (AC):</strong> <?php echo e($sieuAm->chu_vi_bung); ?> mm
                        </div>
                        <?php endif; ?>
                        <?php if($sieuAm->chieu_dai_xuong_dui): ?>
                        <div class="col-md-6 mb-3">
                            <strong>Chiều dài xương đùi (FL):</strong> <?php echo e($sieuAm->chieu_dai_xuong_dui); ?> mm
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            
            <?php if($sieuAm->ket_qua || $sieuAm->nhan_xet): ?>
            <div class="card vc-card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-clipboard-check me-2" style="color: #10b981;"></i>
                        Kết quả & Nhận xét
                    </h5>
                </div>
                <div class="card-body">
                    <?php if($sieuAm->ket_qua): ?>
                    <div class="mb-3">
                        <h6 class="text-primary"><i class="fas fa-file-medical me-2"></i>Kết quả:</h6>
                        <p class="mb-0"><?php echo e($sieuAm->ket_qua); ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if($sieuAm->nhan_xet): ?>
                    <div>
                        <h6 class="text-success"><i class="fas fa-comment-medical me-2"></i>Nhận xét:</h6>
                        <p class="mb-0"><?php echo e($sieuAm->nhan_xet); ?></p>
                    </div>
                    <?php endif; ?>
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
                    <p class="mb-2"><strong>Họ tên:</strong> <?php echo e($sieuAm->benhAn->user->name ?? 'N/A'); ?></p>
                    <p class="mb-2"><strong>Mã BN:</strong> #<?php echo e($sieuAm->benhAn->user_id); ?></p>
                    <p class="mb-2"><strong>Giới tính:</strong> <?php echo e($sieuAm->benhAn->user->gioi_tinh ?? 'N/A'); ?></p>
                    <p class="mb-2"><strong>Năm sinh:</strong> <?php echo e($sieuAm->benhAn->user->ngay_sinh ? \Carbon\Carbon::parse($sieuAm->benhAn->user->ngay_sinh)->format('Y') : 'N/A'); ?></p>
                    <p class="mb-0"><strong>SĐT:</strong> <?php echo e($sieuAm->benhAn->user->so_dien_thoai ?? 'N/A'); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.doctor', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\WORKING\DACN\DACN\resources\views/doctor/sieu-am/show.blade.php ENDPATH**/ ?>