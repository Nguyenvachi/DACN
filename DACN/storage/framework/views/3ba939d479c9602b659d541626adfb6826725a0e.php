

<?php $__env->startSection('content'); ?>
    <div class="container-fluid py-4">

        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="fas fa-file-invoice-dollar me-2"></i>
                Hóa đơn <?php echo e($hoaDon->ma_hoa_don ?? 'HD-' . str_pad($hoaDon->id, 4, '0', STR_PAD_LEFT)); ?>

            </h2>
            <div class="d-flex gap-2">
                <a href="<?php echo e(route('staff.hoadon.index')); ?>" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>
        </div>

        
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">

                <?php
                    $statusColor = match ($hoaDon->trang_thai) {
                        'Đã thanh toán' => 'success',
                        'Chưa thanh toán' => 'warning',
                        'Hủy' => 'danger',
                        default => 'secondary',
                    };
                ?>

                <div class="row g-4">

                    <div class="col-md-6">
                        <h6 class="text-muted">Mã hóa đơn</h6>
                        <p class="fw-bold mb-2"><?php echo e($hoaDon->ma_hoa_don ?? 'HD-' . str_pad($hoaDon->id, 4, '0', STR_PAD_LEFT)); ?></p>

                        <h6 class="text-muted">Lịch hẹn</h6>
                        <p class="fw-bold mb-2">LH-<?php echo e(str_pad($hoaDon->lich_hen_id, 4, '0', STR_PAD_LEFT)); ?></p>

                        <h6 class="text-muted">Bệnh nhân</h6>
                        <p class="fw-bold mb-2"><?php echo e(optional($hoaDon->user)->name ?? 'BN-' . str_pad($hoaDon->user_id, 4, '0', STR_PAD_LEFT)); ?></p>

                        <h6 class="text-muted">Tổng tiền</h6>
                        <p class="fw-bold text-primary fs-5 mb-2">
                            <?php echo e(number_format($hoaDon->tong_tien, 0, ',', '.')); ?> đ
                        </p>

                        <?php if(isset($hoaDon->so_tien_da_thanh_toan)): ?>
                            <h6 class="text-muted">Đã thanh toán</h6>
                            <p class="fw-bold text-success mb-2">
                                <?php echo e(number_format($hoaDon->so_tien_da_thanh_toan, 0, ',', '.')); ?> đ
                            </p>
                        <?php endif; ?>

                        <?php if(isset($hoaDon->so_tien_da_hoan) && $hoaDon->so_tien_da_hoan > 0): ?>
                            <h6 class="text-muted">Đã hoàn</h6>
                            <p class="fw-bold text-warning mb-2">
                                <?php echo e(number_format($hoaDon->so_tien_da_hoan, 0, ',', '.')); ?> đ
                            </p>
                        <?php endif; ?>

                        <?php if(isset($hoaDon->so_tien_con_lai)): ?>
                            <h6 class="text-muted">Còn lại</h6>
                            <p class="fw-bold text-danger mb-2">
                                <?php echo e(number_format($hoaDon->so_tien_con_lai, 0, ',', '.')); ?> đ
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6">
                        <h6 class="text-muted">Trạng thái</h6>
                        <p>
                            <span class="badge bg-<?php echo e($statusColor); ?> fs-6 px-3 py-2">
                                <?php echo e($hoaDon->trang_thai); ?>

                            </span>
                            <?php if(isset($hoaDon->status)): ?>
                                <span class="badge bg-secondary fs-6 px-3 py-2 ms-2">
                                    <?php echo e(strtoupper($hoaDon->status)); ?>

                                </span>
                            <?php endif; ?>
                        </p>

                        <h6 class="text-muted">Phương thức thanh toán</h6>
                        <p class="fw-bold"><?php echo e($hoaDon->phuong_thuc ?? '—'); ?></p>

                        <h6 class="text-muted">Ghi chú</h6>
                        <p><?php echo e($hoaDon->ghi_chu ?? '—'); ?></p>

                        <h6 class="text-muted">Ngày tạo</h6>
                        <p><?php echo e($hoaDon->created_at->format('d/m/Y H:i:s')); ?></p>
                    </div>

                </div>

            </div>
        </div>



        
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-0">
                <h5 class="fw-semibold mb-0">
                    <i class="bi bi-list-check me-1"></i> Chi tiết dịch vụ
                </h5>
            </div>

            <div class="card-body">
                <?php if($hoaDon->chiTiets && $hoaDon->chiTiets->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="15%">Loại dịch vụ</th>
                                <th width="30%">Tên dịch vụ</th>
                                <th width="15%">Số lượng</th>
                                <th width="15%">Đơn giá</th>
                                <th width="20%" class="text-end">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $hoaDon->chiTiets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $ct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="text-center"><?php echo e($index + 1); ?></td>
                                    <td>
                                        <?php
                                            $badgeColor = match($ct->loai_dich_vu) {
                                                'thuoc' => 'secondary',
                                                'noi_soi' => 'info',
                                                'x_quang' => 'warning',
                                                'xet_nghiem' => 'primary',
                                                'thu_thuat' => 'danger',
                                                'sieu_am' => 'purple',
                                                'dich_vu_kham' => 'success',
                                                default => 'dark'
                                            };
                                            $labelText = match($ct->loai_dich_vu) {
                                                'thuoc' => 'Thuốc',
                                                'noi_soi' => 'Nội soi',
                                                'x_quang' => 'X-quang',
                                                'xet_nghiem' => 'Xét nghiệm',
                                                'thu_thuat' => 'Thủ thuật',
                                                'sieu_am' => 'Siêu âm',
                                                'dich_vu_kham' => 'Khám bệnh',
                                                default => ucfirst($ct->loai_dich_vu)
                                            };
                                        ?>
                                        <span class="badge bg-<?php echo e($badgeColor); ?>" <?php if($ct->loai_dich_vu == 'sieu_am'): ?> style="background-color: #6f42c1 !important;" <?php endif; ?>><?php echo e($labelText); ?></span>
                                    </td>
                                    <td>
                                        <strong><?php echo e($ct->ten_dich_vu); ?></strong>
                                        <?php if($ct->mo_ta): ?>
                                            <br><small class="text-muted"><?php echo e($ct->mo_ta); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center"><strong><?php echo e($ct->so_luong); ?></strong></td>
                                    <td class="text-end"><?php echo e(number_format($ct->don_gia, 0, ',', ',')); ?> đ</td>
                                    <td class="text-end fw-bold text-primary"><?php echo e(number_format($ct->thanh_tien, 0, ',', ',')); ?> đ</td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="5" class="text-end fs-6">TỔNG TIỀN:</th>
                                <th class="text-end text-danger fs-5"><?php echo e(number_format($hoaDon->tong_tien, 0, ',', ',')); ?> đ</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <?php else: ?>
                <div class="alert alert-warning mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Không có chi tiết dịch vụ!</strong>
                    <p class="mb-2 mt-2">Hóa đơn này được tạo trước khi có tính năng lưu chi tiết dịch vụ.</p>
                    <?php if($hoaDon->lichHen): ?>
                        <p class="mb-2">
                            <i class="bi bi-info-circle me-1"></i>
                            Thông tin dịch vụ có thể xem tại: <strong>Lịch hẹn LH-<?php echo e(str_pad($hoaDon->lich_hen_id, 4, '0', STR_PAD_LEFT)); ?></strong>
                        </p>
                        <?php
                            $benhAn = \App\Models\BenhAn::where('lich_hen_id', $hoaDon->lich_hen_id)->first();
                        ?>
                        <?php if($benhAn): ?>
                            <a href="<?php echo e(route('staff.hoadon.create-from-benh-an', $benhAn->id)); ?>" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-circle me-1"></i>
                                Cập nhật chi tiết dịch vụ từ bệnh án
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>



        
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-0">
                <h5 class="fw-semibold mb-0">
                    <i class="fas fa-receipt me-1"></i> Lịch sử thanh toán
                </h5>
            </div>

            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="15%">Provider</th>
                                <th width="15%">Số tiền</th>
                                <th width="15%">Trạng thái</th>
                                <th width="20%">Mã giao dịch</th>
                                <th width="20%">Thời gian</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $hoaDon->thanhToans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($tt->id); ?></td>
                                    <td><span class="badge bg-info"><?php echo e(strtoupper($tt->provider)); ?></span></td>
                                    <td class="fw-bold text-success">
                                        <?php echo e(number_format($tt->so_tien, 0, ',', '.')); ?> đ
                                    </td>
                                    <td>
                                        <span
                                            class="badge
                                    <?php if($tt->trang_thai === 'success'): ?> bg-success
                                    <?php elseif($tt->trang_thai === 'pending'): ?> bg-warning
                                    <?php else: ?> bg-danger <?php endif; ?>">
                                            <?php echo e($tt->trang_thai); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e($tt->transaction_ref ?? '-'); ?></td>
                                    <td><?php echo e($tt->paid_at ?? $tt->created_at); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-3 text-muted">
                                        <i class="fas fa-inbox me-2"></i> Chưa có thanh toán
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>


        
        <div class="row g-4 mb-4">

            
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h5 class="fw-semibold mb-3">
                            <i class="fas fa-hand-holding-usd me-2 text-success"></i>Thu tiền mặt
                        </h5>

                        <?php
                            $daThanhtoan = $hoaDon->trang_thai === 'Đã thanh toán';
                        ?>

                        <?php if($daThanhtoan): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>Hóa đơn đã được thanh toán
                            </div>
                        <?php else: ?>
                        <form method="POST" action="<?php echo e(route('staff.hoadon.cash_collect', $hoaDon)); ?>" class="row g-3">
                            <?php echo csrf_field(); ?>
                            <div class="col-12">
                                <label class="form-label">Số tiền</label>
                                <input type="number" name="so_tien" class="form-control"
                                    value="<?php echo e((int) $hoaDon->tong_tien); ?>" min="0" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Ghi chú (tùy chọn)</label>
                                <input type="text" name="ghi_chu" class="form-control">
                            </div>

                            <div class="col-12 text-end">
                                <button class="btn btn-success">
                                    <i class="fas fa-check-circle me-1"></i> Xác nhận thanh toán
                                </button>
                            </div>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h5 class="fw-semibold mb-3">
                            <i class="fas fa-credit-card me-2 text-primary"></i>Thanh toán Online
                        </h5>

                        <?php
                            $daThanhtoan = $hoaDon->trang_thai === 'Đã thanh toán';
                        ?>

                        
                        <form method="POST" action="<?php echo e(route('vnpay.create')); ?>" class="mb-2">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="hoa_don_id" value="<?php echo e($hoaDon->id); ?>">
                            <input type="hidden" name="amount" value="<?php echo e($hoaDon->tong_tien); ?>">
                            <button class="btn btn-primary w-100" <?php echo e(($hoaDon->tong_tien == 0 || $daThanhtoan) ? 'disabled' : ''); ?>>
                                <?php echo e($daThanhtoan ? 'Đã thanh toán' : 'Thanh toán qua VNPay'); ?>

                            </button>
                        </form>

                        
                        <form method="POST" action="<?php echo e(route('momo.create')); ?>">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="hoa_don_id" value="<?php echo e($hoaDon->id); ?>">
                            <input type="hidden" name="amount" value="<?php echo e($hoaDon->tong_tien); ?>">
                            <button class="btn btn-danger w-100" <?php echo e(($hoaDon->tong_tien == 0 || $daThanhtoan) ? 'disabled' : ''); ?>>
                                <?php echo e($daThanhtoan ? 'Đã thanh toán' : 'Thanh toán qua MoMo'); ?>

                            </button>
                        </form>

                    </div>
                </div>
            </div>

        </div>


        
        <div class="d-flex gap-2">
            <a class="btn btn-outline-dark" href="<?php echo e(route('staff.hoadon.receipt', $hoaDon)); ?>">
                <i class="fas fa-file-pdf me-1"></i> Tải biên lai (PDF)
            </a>

            
            <div class="btn-group" role="group" aria-label="Hoá đơn theo loại">
                <a class="btn btn-outline-secondary" href="<?php echo e(route('staff.hoadon.receipt.type', [$hoaDon, 'phieu-thu'])); ?>">
                    <i class="fas fa-receipt me-1"></i> Phiếu thu khám
                </a>
                <a class="btn btn-outline-secondary" href="<?php echo e(route('staff.hoadon.receipt.type', [$hoaDon, 'dich-vu'])); ?>">
                    <i class="fas fa-stethoscope me-1"></i> Hóa đơn dịch vụ
                </a>
                <a class="btn btn-outline-secondary" href="<?php echo e(route('staff.hoadon.receipt.type', [$hoaDon, 'thuoc'])); ?>">
                    <i class="fas fa-pills me-1"></i> Hóa đơn thuốc
                </a>
                <a class="btn btn-outline-secondary" href="<?php echo e(route('staff.hoadon.receipt.type', [$hoaDon, 'tong-hop'])); ?>">
                    <i class="fas fa-layer-group me-1"></i> Hóa đơn tổng hợp
                </a>
            </div>
        </div>

    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.staff', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\WORKING\DACN\DACN\resources\views/staff/hoadon/show.blade.php ENDPATH**/ ?>