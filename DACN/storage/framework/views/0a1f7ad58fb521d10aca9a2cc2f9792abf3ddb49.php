

<?php $__env->startSection('title', 'Chi Tiết Đơn Thuốc DT-' . str_pad($donThuoc->id, 4, '0', STR_PAD_LEFT)); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-prescription2 text-primary me-2"></i>
                Đơn Thuốc DT-<?php echo e(str_pad($donThuoc->id, 4, '0', STR_PAD_LEFT)); ?>

            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('staff.dashboard')); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('staff.donthuoc.index')); ?>">Đơn thuốc</a></li>
                    <li class="breadcrumb-item active">Chi tiết</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?php echo e(route('staff.donthuoc.index')); ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>Quay lại
            </a>
            <button onclick="window.print()" class="btn btn-info">
                <i class="bi bi-printer me-1"></i>In đơn
            </button>
        </div>
    </div>

    <div class="row g-4">
        
        <div class="col-lg-8">
            
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-person-badge me-2"></i>Thông Tin Bệnh Nhân</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong><i class="bi bi-person me-2"></i>Họ tên:</strong> <?php echo e($donThuoc->benhAn->user->name ?? 'N/A'); ?></p>
                            <p class="mb-2"><strong><i class="bi bi-calendar3 me-2"></i>Ngày sinh:</strong> 
                                <?php echo e($donThuoc->benhAn->user->ngay_sinh ? $donThuoc->benhAn->user->ngay_sinh->format('d/m/Y') : 'N/A'); ?>

                            </p>
                            <p class="mb-2"><strong><i class="bi bi-gender-ambiguous me-2"></i>Giới tính:</strong> 
                                <?php echo e($donThuoc->benhAn->user->gioi_tinh ?? 'N/A'); ?>

                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong><i class="bi bi-telephone me-2"></i>Số điện thoại:</strong> 
                                <?php echo e($donThuoc->benhAn->user->so_dien_thoai ?? 'N/A'); ?>

                            </p>
                            <p class="mb-2"><strong><i class="bi bi-envelope me-2"></i>Email:</strong> 
                                <?php echo e($donThuoc->benhAn->user->email ?? 'N/A'); ?>

                            </p>
                            <p class="mb-2"><strong><i class="bi bi-geo-alt me-2"></i>Địa chỉ:</strong> 
                                <?php echo e($donThuoc->benhAn->user->dia_chi ?? 'N/A'); ?>

                            </p>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-clipboard2-pulse me-2"></i>Thông Tin Khám Bệnh</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Bác sĩ khám:</strong> BS. <?php echo e($donThuoc->benhAn->bacSi->ho_ten ?? 'N/A'); ?></p>
                            <p class="mb-2"><strong>Ngày khám:</strong> <?php echo e($donThuoc->benhAn->created_at->format('d/m/Y H:i')); ?></p>
                            <p class="mb-2"><strong>Mã hồ sơ:</strong> HS-<?php echo e(str_pad($donThuoc->benh_an_id, 4, '0', STR_PAD_LEFT)); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Triệu chứng:</strong> 
                                <?php if(empty($donThuoc->benhAn->trieu_chung)): ?>
                                    <span class="text-muted">(Chưa có thông tin)</span>
                                <?php else: ?>
                                    <?php echo e($donThuoc->benhAn->trieu_chung); ?>

                                <?php endif; ?>
                            </p>
                            <p class="mb-2"><strong>Chẩn đoán:</strong> 
                                <?php if(empty($donThuoc->benhAn->chuan_doan)): ?>
                                    <span class="text-muted">(Chưa có thông tin)</span>
                                <?php else: ?>
                                    <?php echo e($donThuoc->benhAn->chuan_doan); ?>

                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <?php if($donThuoc->benhAn->ghi_chu): ?>
                    <hr>
                    <p class="mb-0"><strong>Ghi chú bác sĩ:</strong> <?php echo e($donThuoc->benhAn->ghi_chu); ?></p>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-capsule me-2"></i>Danh Sách Thuốc</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="30%">Tên Thuốc</th>
                                    <th width="15%">Hoạt chất</th>
                                    <th width="10%">SL</th>
                                    <th width="20%">Liều dùng</th>
                                    <th width="20%">Cách dùng</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $donThuoc->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="text-center"><?php echo e($index + 1); ?></td>
                                    <td>
                                        <strong><?php echo e($item->thuoc->ten ?? 'N/A'); ?></strong>
                                        <?php if($item->thuoc): ?>
                                        <br><small class="text-muted"><?php echo e($item->thuoc->ham_luong); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($item->thuoc->hoat_chat ?? 'N/A'); ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-primary"><?php echo e($item->so_luong); ?> <?php echo e($item->thuoc->don_vi ?? ''); ?></span>
                                    </td>
                                    <td><?php echo e($item->lieu_dung ?? 'N/A'); ?></td>
                                    <td><?php echo e($item->cach_dung ?? '-'); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if($donThuoc->ghi_chu): ?>
                    <div class="alert alert-info mt-3 mb-0">
                        <strong><i class="bi bi-info-circle me-2"></i>Ghi chú đơn thuốc:</strong><br>
                        <?php echo e($donThuoc->ghi_chu); ?>

                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="col-lg-4">
            
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-flag me-2"></i>Trạng Thái</h5>
                </div>
                <div class="card-body">
                    <?php if($donThuoc->ngay_cap_thuoc): ?>
                    <div class="alert alert-success mb-3">
                        <h6 class="alert-heading"><i class="bi bi-check-circle me-2"></i>Đã cấp thuốc</h6>
                        <hr>
                        <p class="mb-1"><strong>Ngày cấp:</strong> <?php echo e($donThuoc->ngay_cap_thuoc->format('d/m/Y H:i')); ?></p>
                        <p class="mb-1"><strong>Người cấp:</strong> <?php echo e($donThuoc->nguoiCapThuoc->name ?? 'N/A'); ?></p>
                        <?php if($donThuoc->ghi_chu_cap_thuoc): ?>
                        <p class="mb-0"><strong>Ghi chú:</strong> <?php echo e($donThuoc->ghi_chu_cap_thuoc); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-warning mb-3">
                        <h6 class="alert-heading"><i class="bi bi-hourglass-split me-2"></i>Chưa cấp thuốc</h6>
                        <p class="mb-0">Đơn thuốc đang chờ cấp cho bệnh nhân.</p>
                    </div>

                    <form method="POST" action="<?php echo e(route('staff.donthuoc.cap-thuoc', $donThuoc)); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label class="form-label">Ghi chú cấp thuốc (tùy chọn)</label>
                            <textarea name="ghi_chu_cap_thuoc" class="form-control" rows="3" 
                                      placeholder="Nhập ghi chú nếu có..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-check-circle me-1"></i>Xác nhận đã cấp thuốc
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Thông Tin</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Mã đơn thuốc:</strong> DT-<?php echo e(str_pad($donThuoc->id, 4, '0', STR_PAD_LEFT)); ?></p>
                    <p class="mb-2"><strong>Ngày kê đơn:</strong> <?php echo e($donThuoc->created_at->format('d/m/Y H:i')); ?></p>
                    <p class="mb-2"><strong>Số loại thuốc:</strong> <?php echo e($donThuoc->items->count()); ?></p>
                    <p class="mb-0"><strong>Tổng số lượng:</strong> <?php echo e($donThuoc->items->sum('so_luong')); ?></p>
                </div>
            </div>

            
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-gear me-2"></i>Thao Tác</h5>
                </div>
                <div class="card-body">
                    <a href="<?php echo e(route('staff.hoadon.create-from-benh-an', $donThuoc->benh_an_id)); ?>" 
                       class="btn btn-primary w-100 mb-2">
                        <i class="bi bi-receipt me-1"></i>Tạo hóa đơn
                    </a>
                    <button onclick="window.print()" class="btn btn-info w-100 mb-2">
                        <i class="bi bi-printer me-1"></i>In đơn thuốc
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
    @media print {
        .btn, .breadcrumb, nav, .card-header, .alert-dismissible .btn-close {
            display: none !important;
        }
        .card {
            border: 1px solid #ddd !important;
            box-shadow: none !important;
        }
    }
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.staff', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\WORKING\DACN\DACN\resources\views/staff/donthuoc/show.blade.php ENDPATH**/ ?>