<?php
    $role = auth()->user()->role ?? 'patient';
    $layout = match ($role) {
        'admin' => 'layouts.admin',
        'doctor' => 'layouts.doctor',
        'staff' => 'layouts.staff',
        'patient' => 'layouts.patient-modern',
        default => 'layouts.app',
    };
?>



<?php $__env->startSection('content'); ?>
<style>
    .simple-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .info-table {
        width: 100%;
    }
    
    .info-table td {
        padding: 0.75rem;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .info-table tr:last-child td {
        border-bottom: none;
    }
    
    .info-table td:first-child {
        color: #6b7280;
        font-weight: 500;
        width: 35%;
    }
    
    .info-table td:last-child {
        color: #111827;
    }
    
    .clinical-section {
        padding: 1rem;
        background: #f9fafb;
        border-radius: 6px;
        margin-bottom: 1rem;
    }
    
    .clinical-section h6 {
        color: #374151;
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .clinical-section p {
        color: #1f2937;
        margin: 0;
        line-height: 1.6;
    }
    
    .action-btn-simple {
        display: block;
        padding: 1rem;
        text-align: center;
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 6px;
        color: #374151;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .action-btn-simple:hover {
        border-color: #3b82f6;
        background: #eff6ff;
        color: #2563eb;
    }
    
    .medicine-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .medicine-list li {
        padding: 0.75rem;
        background: #f9fafb;
        border-radius: 4px;
        margin-bottom: 0.5rem;
    }
    
    .service-count {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem;
        background: #f9fafb;
        border-radius: 4px;
        margin-bottom: 0.5rem;
    }
</style>

<div class="container py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Chi tiết bệnh án</h2>
            <p class="text-muted mb-0">Mã: <?php echo e(str_pad($benhAn->id, 4, '0', STR_PAD_LEFT)); ?> • <?php echo e($benhAn->tieu_de); ?></p>
        </div>
        
        <div class="d-flex gap-2">
            <?php if($benhAn->trang_thai === 'Hoàn thành'): ?>
                <span class="badge bg-success">
                    <i class="bi bi-check-circle me-1"></i>Hoàn thành
                </span>
            <?php else: ?>
                <span class="badge bg-warning text-dark">
                    <i class="bi bi-clock me-1"></i>Đang khám
                </span>
            <?php endif; ?>
            
            <?php if(in_array($role, ['admin', 'doctor'])): ?>
                <a href="<?php echo e(route($role . '.benhan.edit', $benhAn)); ?>" class="btn btn-primary btn-sm">
                    <i class="bi bi-pencil"></i> Chỉnh sửa
                </a>
                
                <?php if($benhAn->trang_thai === 'Đang khám'): ?>
                <form action="<?php echo e(route($role . '.benhan.hoanthanh', $benhAn)); ?>" method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <button type="submit" class="btn btn-success btn-sm" 
                            onclick="return confirm('Xác nhận hoàn thành khám?')">
                        <i class="bi bi-check-circle"></i> Hoàn thành
                    </button>
                </form>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="row g-4">
        
        <div class="col-lg-8">
            
            <div class="simple-card mb-4">
                <div class="p-3 border-bottom">
                    <h5 class="mb-0"><i class="bi bi-person-circle text-primary me-2"></i>Thông tin bệnh nhân</h5>
                </div>
                <table class="info-table">
                    <tr>
                        <td>Bệnh nhân</td>
                        <td><strong><?php echo e($benhAn->benhNhan->name ?? 'N/A'); ?></strong></td>
                    </tr>
                    <tr>
                        <td>Bác sĩ điều trị</td>
                        <td><strong><?php echo e($benhAn->bacSi->ho_ten ?? 'N/A'); ?></strong></td>
                    </tr>
                    <tr>
                        <td>Ngày khám</td>
                        <td><strong><?php echo e($benhAn->ngay_kham->format('d/m/Y')); ?></strong></td>
                    </tr>
                    <tr>
                        <td>Trạng thái</td>
                        <td><strong><?php echo e($benhAn->trang_thai); ?></strong></td>
                    </tr>
                </table>
            </div>

            
            <div class="simple-card mb-4">
                <div class="p-3 border-bottom">
                    <h5 class="mb-0"><i class="bi bi-heart-pulse text-danger me-2"></i>Chỉ số sinh tồn</h5>
                </div>
                <div class="p-3">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="text-center p-3" style="background: #fef2f2; border-radius: 8px;">
                                <i class="bi bi-droplet text-danger fs-4"></i>
                                <div class="mt-2">
                                    <small class="text-muted d-block">Huyết áp</small>
                                    <strong class="text-danger"><?php echo e($benhAn->huyet_ap ?? '--'); ?></strong>
                                    <small class="text-muted d-block">mmHg</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3" style="background: #fef2f2; border-radius: 8px;">
                                <i class="bi bi-heart text-danger fs-4"></i>
                                <div class="mt-2">
                                    <small class="text-muted d-block">Nhịp tim</small>
                                    <strong class="text-danger"><?php echo e($benhAn->nhip_tim ?? '--'); ?></strong>
                                    <small class="text-muted d-block">lần/phút</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3" style="background: #fef3f2; border-radius: 8px;">
                                <i class="bi bi-thermometer-half text-warning fs-4"></i>
                                <div class="mt-2">
                                    <small class="text-muted d-block">Nhiệt độ</small>
                                    <strong class="text-warning"><?php echo e($benhAn->nhiet_do ?? '--'); ?></strong>
                                    <small class="text-muted d-block">°C</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3" style="background: #f0f9ff; border-radius: 8px;">
                                <i class="bi bi-wind text-info fs-4"></i>
                                <div class="mt-2">
                                    <small class="text-muted d-block">Nhịp thở</small>
                                    <strong class="text-info"><?php echo e($benhAn->nhip_tho ?? '--'); ?></strong>
                                    <small class="text-muted d-block">lần/phút</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3" style="background: #f0fdf4; border-radius: 8px;">
                                <i class="bi bi-person text-success fs-4"></i>
                                <div class="mt-2">
                                    <small class="text-muted d-block">Cân nặng</small>
                                    <strong class="text-success"><?php echo e($benhAn->can_nang ?? '--'); ?></strong>
                                    <small class="text-muted d-block">kg</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3" style="background: #f0fdf4; border-radius: 8px;">
                                <i class="bi bi-arrows-vertical text-success fs-4"></i>
                                <div class="mt-2">
                                    <small class="text-muted d-block">Chiều cao</small>
                                    <strong class="text-success"><?php echo e($benhAn->chieu_cao ?? '--'); ?></strong>
                                    <small class="text-muted d-block">cm</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3" style="background: #fefce8; border-radius: 8px;">
                                <i class="bi bi-calculator text-warning fs-4"></i>
                                <div class="mt-2">
                                    <small class="text-muted d-block">BMI</small>
                                    <strong class="text-warning"><?php echo e($benhAn->bmi ?? '--'); ?></strong>
                                    <small class="text-muted d-block">kg/m²</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3" style="background: #eff6ff; border-radius: 8px;">
                                <i class="bi bi-activity text-primary fs-4"></i>
                                <div class="mt-2">
                                    <small class="text-muted d-block">SpO2</small>
                                    <strong class="text-primary"><?php echo e($benhAn->spo2 ?? '--'); ?></strong>
                                    <small class="text-muted d-block">%</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="simple-card mb-4">
                <div class="p-3 border-bottom">
                    <h5 class="mb-0"><i class="bi bi-clipboard2-pulse text-success me-2"></i>Thông tin lâm sàng</h5>
                </div>
                <div class="p-3">
                    <div class="clinical-section">
                        <h6><i class="bi bi-thermometer-half text-danger me-2"></i>Triệu chứng</h6>
                        <p><?php echo e($benhAn->trieu_chung ?? 'Chưa có thông tin'); ?></p>
                    </div>
                    
                    <div class="clinical-section">
                        <h6><i class="bi bi-stethoscope text-primary me-2"></i>Chẩn đoán</h6>
                        <p><?php echo e($benhAn->chuan_doan ?? 'Chưa có thông tin'); ?></p>
                    </div>
                    
                    <div class="clinical-section">
                        <h6><i class="bi bi-heart-pulse text-success me-2"></i>Điều trị</h6>
                        <p><?php echo e($benhAn->dieu_tri ?? 'Chưa có thông tin'); ?></p>
                    </div>
                </div>
            </div>

            
            <?php if($benhAn->donThuocs && count($benhAn->donThuocs) > 0): ?>
            <div class="simple-card mb-4">
                <div class="p-3 border-bottom">
                    <h5 class="mb-0"><i class="bi bi-capsule text-danger me-2"></i>Đơn thuốc</h5>
                </div>
                <div class="p-3">
                    <?php $__currentLoopData = $benhAn->donThuocs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="mb-3 pb-3" style="border-bottom: 1px dashed #e5e7eb;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-primary">Đơn #<?php echo e(str_pad($dt->id, 4, '0', STR_PAD_LEFT)); ?></span>
                            <small class="text-muted"><?php echo e($dt->created_at->format('d/m/Y')); ?></small>
                        </div>
                        
                        <ul class="medicine-list">
                            <?php $__currentLoopData = $dt->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong><?php echo e($it->thuoc->ten ?? 'N/A'); ?></strong>
                                        <div class="text-muted small"><?php echo e($it->lieu_dung); ?></div>
                                    </div>
                                    <span class="badge bg-secondary">SL: <?php echo e($it->so_luong); ?></span>
                                </div>
                            </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        
        <div class="col-lg-4">
            <?php if($role === 'doctor'): ?>
            
            <div class="simple-card mb-4">
                <div class="p-3 border-bottom">
                    <h5 class="mb-0"><i class="bi bi-lightning text-warning me-2"></i>Thao tác nhanh</h5>
                </div>
                <div class="p-3">
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('doctor.donthuoc.create', $benhAn->id)); ?>" class="action-btn-simple">
                            <i class="bi bi-prescription2 fs-5 d-block mb-1"></i>
                            <strong>Kê đơn thuốc</strong>
                        </a>
                        
                        <a href="<?php echo e(route('doctor.xet-nghiem.create', ['benh_an_id' => $benhAn->id])); ?>" class="action-btn-simple">
                            <i class="bi bi-droplet-fill fs-5 d-block mb-1"></i>
                            <strong>Xét nghiệm</strong>
                        </a>

                        <a href="<?php echo e(route('doctor.sieu-am.create', $benhAn->id)); ?>" class="action-btn-simple">
                            <i class="bi bi-soundwave fs-5 d-block mb-1"></i>
                            <strong>Siêu âm</strong>
                        </a>
                        
                        <a href="<?php echo e(route('doctor.noi-soi.create', ['benh_an_id' => $benhAn->id])); ?>" class="action-btn-simple">
                            <i class="bi bi-camera-video-fill fs-5 d-block mb-1"></i>
                            <strong>Nội soi</strong>
                        </a>
                        
                        <a href="<?php echo e(route('doctor.x-quang.create', ['benh_an_id' => $benhAn->id])); ?>" class="action-btn-simple">
                            <i class="bi bi-file-medical-fill fs-5 d-block mb-1"></i>
                            <strong>X-quang</strong>
                        </a>

                        <a href="<?php echo e(route('doctor.thu-thuat.create', $benhAn->id)); ?>" class="action-btn-simple">
                            <i class="bi bi-scissors fs-5 d-block mb-1"></i>
                            <strong>Thủ thuật</strong>
                        </a>
                    </div>
                </div>
            </div>

            
            <div class="simple-card">
                <div class="p-3 border-bottom">
                    <h5 class="mb-0"><i class="bi bi-list-check text-info me-2"></i>Dịch vụ đã chỉ định</h5>
                </div>
                <div class="p-3">
                    <?php
                        $hasServices = false;
                    ?>

                    
                    <?php if(isset($benhAn->xetNghiems) && count($benhAn->xetNghiems) > 0): ?>
                    <?php $hasServices = true; ?>
                    <div class="mb-3">
                        <h6 class="text-danger mb-2"><i class="bi bi-droplet me-2"></i>Xét nghiệm (<?php echo e(count($benhAn->xetNghiems)); ?>)</h6>
                        <?php $__currentLoopData = $benhAn->xetNghiems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $xn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                            <div class="flex-grow-1">
                                <small class="fw-bold d-block"><?php echo e($xn->loai ?? $xn->loai_xet_nghiem ?? 'XN'); ?></small>
                                <?php if(in_array($xn->trang_thai, ['Có kết quả', 'Đã có kết quả', 'completed'])): ?>
                                <span class="badge bg-success" style="font-size: 0.7rem;">Có KQ</span>
                                <?php else: ?>
                                <span class="badge bg-secondary" style="font-size: 0.7rem;">Chờ</span>
                                <?php endif; ?>
                            </div>
                            <?php if(in_array($xn->trang_thai, ['Có kết quả', 'Đã có kết quả', 'completed'])): ?>
                            <a href="<?php echo e(route('doctor.xet-nghiem.edit', $xn->id)); ?>" class="btn btn-sm btn-success" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                <i class="bi bi-eye"></i> Xem KQ
                            </a>
                            <?php else: ?>
                            <a href="<?php echo e(route('doctor.xet-nghiem.edit', $xn->id)); ?>" class="btn btn-sm btn-warning" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                <i class="bi bi-pencil"></i> Nhập KQ
                            </a>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php endif; ?>
                    
                    
                    <?php if(isset($benhAn->sieuAms) && count($benhAn->sieuAms) > 0): ?>
                    <?php $hasServices = true; ?>
                    <div class="mb-3">
                        <h6 class="text-success mb-2"><i class="bi bi-soundwave me-2"></i>Siêu âm (<?php echo e(count($benhAn->sieuAms)); ?>)</h6>
                        <?php $__currentLoopData = $benhAn->sieuAms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                            <div class="flex-grow-1">
                                <small class="fw-bold d-block"><?php echo e($sa->loai_sieu_am ?? 'Siêu âm'); ?></small>
                                <?php if(in_array($sa->trang_thai, ['Hoàn thành', 'Đã có kết quả'])): ?>
                                <span class="badge bg-success" style="font-size: 0.7rem;">Có KQ</span>
                                <?php else: ?>
                                <span class="badge bg-secondary" style="font-size: 0.7rem;">Chờ</span>
                                <?php endif; ?>
                            </div>
                            <?php if(in_array($sa->trang_thai, ['Hoàn thành', 'Đã có kết quả'])): ?>
                            <a href="<?php echo e(route('doctor.sieu-am.edit', $sa->id)); ?>" class="btn btn-sm btn-success" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                <i class="bi bi-eye"></i> Xem KQ
                            </a>
                            <?php else: ?>
                            <a href="<?php echo e(route('doctor.sieu-am.edit', $sa->id)); ?>" class="btn btn-sm btn-warning" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                <i class="bi bi-pencil"></i> Nhập KQ
                            </a>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php endif; ?>

                    
                    <?php if(isset($benhAn->noiSois) && count($benhAn->noiSois) > 0): ?>
                    <?php $hasServices = true; ?>
                    <div class="mb-3">
                        <h6 class="text-info mb-2"><i class="bi bi-camera-video me-2"></i>Nội soi (<?php echo e(count($benhAn->noiSois)); ?>)</h6>
                        <?php $__currentLoopData = $benhAn->noiSois; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ns): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                            <div class="flex-grow-1">
                                <small class="fw-bold d-block"><?php echo e($ns->loai_noi_soi ?? 'Nội soi'); ?></small>
                                <?php if(in_array($ns->trang_thai, ['Hoàn thành', 'Đã có kết quả'])): ?>
                                <span class="badge bg-success" style="font-size: 0.7rem;">Có KQ</span>
                                <?php else: ?>
                                <span class="badge bg-secondary" style="font-size: 0.7rem;">Chờ</span>
                                <?php endif; ?>
                            </div>
                            <?php if(in_array($ns->trang_thai, ['Hoàn thành', 'Đã có kết quả'])): ?>
                            <a href="<?php echo e(route('doctor.noi-soi.edit', $ns->id)); ?>" class="btn btn-sm btn-success" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                <i class="bi bi-eye"></i> Xem KQ
                            </a>
                            <?php else: ?>
                            <a href="<?php echo e(route('doctor.noi-soi.edit', $ns->id)); ?>" class="btn btn-sm btn-warning" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                <i class="bi bi-pencil"></i> Nhập KQ
                            </a>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php endif; ?>
                    
                    
                    <?php if(isset($benhAn->xQuangs) && count($benhAn->xQuangs) > 0): ?>
                    <?php $hasServices = true; ?>
                    <div class="mb-3">
                        <h6 class="text-warning mb-2"><i class="bi bi-file-medical me-2"></i>X-quang (<?php echo e(count($benhAn->xQuangs)); ?>)</h6>
                        <?php $__currentLoopData = $benhAn->xQuangs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $xq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                            <div class="flex-grow-1">
                                <small class="fw-bold d-block"><?php echo e($xq->loai_x_quang ?? 'X-quang'); ?></small>
                                <?php if(in_array($xq->trang_thai, ['Hoàn thành', 'Đã có kết quả'])): ?>
                                <span class="badge bg-success" style="font-size: 0.7rem;">Có KQ</span>
                                <?php else: ?>
                                <span class="badge bg-secondary" style="font-size: 0.7rem;">Chờ</span>
                                <?php endif; ?>
                            </div>
                            <?php if(in_array($xq->trang_thai, ['Hoàn thành', 'Đã có kết quả'])): ?>
                            <a href="<?php echo e(route('doctor.x-quang.edit', $xq->id)); ?>" class="btn btn-sm btn-success" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                <i class="bi bi-eye"></i> Xem KQ
                            </a>
                            <?php else: ?>
                            <a href="<?php echo e(route('doctor.x-quang.edit', $xq->id)); ?>" class="btn btn-sm btn-warning" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                <i class="bi bi-pencil"></i> Nhập KQ
                            </a>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php endif; ?>

                    
                    <?php if(isset($benhAn->thuThuats) && count($benhAn->thuThuats) > 0): ?>
                    <?php $hasServices = true; ?>
                    <div class="mb-3">
                        <h6 class="text-primary mb-2"><i class="bi bi-scissors me-2"></i>Thủ thuật (<?php echo e(count($benhAn->thuThuats)); ?>)</h6>
                        <?php $__currentLoopData = $benhAn->thuThuats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                            <div class="flex-grow-1">
                                <small class="fw-bold d-block"><?php echo e($tt->ten_thu_thuat ?? 'Thủ thuật'); ?></small>
                                <?php if(in_array($tt->trang_thai, ['Đã hoàn thành', 'Hoàn thành'])): ?>
                                <span class="badge bg-success" style="font-size: 0.7rem;">Có KQ</span>
                                <?php else: ?>
                                <span class="badge bg-secondary" style="font-size: 0.7rem;">Chờ</span>
                                <?php endif; ?>
                            </div>
                            <?php if(in_array($tt->trang_thai, ['Đã hoàn thành', 'Hoàn thành'])): ?>
                            <a href="<?php echo e(route('doctor.thu-thuat.edit', $tt->id)); ?>" class="btn btn-sm btn-success" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                <i class="bi bi-eye"></i> Xem KQ
                            </a>
                            <?php else: ?>
                            <a href="<?php echo e(route('doctor.thu-thuat.edit', $tt->id)); ?>" class="btn btn-sm btn-warning" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                <i class="bi bi-pencil"></i> Nhập KQ
                            </a>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if(!$hasServices): ?>
                    <p class="text-muted text-center mb-0 py-4">
                        <i class="bi bi-inbox" style="font-size: 2rem; opacity: 0.3;"></i><br>
                        <small>Chưa có dịch vụ nào</small>
                    </p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\WORKING\DACN\DACN\resources\views/benh_an/show.blade.php ENDPATH**/ ?>