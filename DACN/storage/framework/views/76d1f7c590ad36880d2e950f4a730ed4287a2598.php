

<?php $__env->startSection('content'); ?>

    <style>
        .card-custom {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
        }

        .section-title {
            font-weight: 700;
            font-size: 22px;
        }

        .form-label {
            font-weight: 600;
        }
    </style>

    <div class="container-fluid py-4">

        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">
                    <i class="bi bi-calendar-week text-primary me-2"></i>
                    Lịch làm việc - Bác sĩ: <?php echo e($bacSi->ho_ten); ?>

                </h2>
                <small class="text-muted"><?php echo e($bacSi->chuyen_khoa); ?></small>
            </div>

            <div>
                <div class="btn-group me-2" role="group">
                    <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-download"></i> Xuất báo cáo
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?php echo e(route('admin.lichlamviec.export', ['bacSi' => $bacSi->id, 'format' => 'pdf'])); ?>" target="_blank">
                            <i class="bi bi-file-pdf"></i> Xuất PDF
                        </a></li>
                        <li><a class="dropdown-item" href="<?php echo e(route('admin.lichlamviec.export', ['bacSi' => $bacSi->id, 'format' => 'csv'])); ?>">
                            <i class="bi bi-file-earmark-spreadsheet"></i> Xuất CSV
                        </a></li>
                    </ul>
                </div>
                <a href="<?php echo e(route('admin.bac-si.index')); ?>" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>

        
        <?php if(session('success')): ?>
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill me-1"></i>
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <strong>Lỗi:</strong>
                <ul class="mb-0">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>• <?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        
        <div class="card card-custom mb-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0"><i class="bi bi-plus-circle text-success me-2"></i>Thêm lịch làm việc</h5>
            </div>

            <div class="card-body">
                <form action="<?php echo e(route('admin.lichlamviec.store', $bacSi)); ?>" method="POST">
                    <?php echo csrf_field(); ?>

                    <div class="row g-3">

                        
                        <div class="col-md-3">
                            <label class="form-label">Ngày trong tuần <span class="text-danger">*</span></label>
                            <select name="ngay_trong_tuan" class="form-select" required>
                                <option value="">-- Chọn --</option>
                                <option value="0">Chủ nhật</option>
                                <option value="1">Thứ hai</option>
                                <option value="2">Thứ ba</option>
                                <option value="3">Thứ tư</option>
                                <option value="4">Thứ năm</option>
                                <option value="5">Thứ sáu</option>
                                <option value="6">Thứ bảy</option>
                            </select>
                        </div>

                        
                        <div class="col-md-3">
                            <label class="form-label">Tháng áp dụng</label>
                            <select name="thangs[]" class="form-select" multiple size="4" style="height: auto;">
                                <option value="1">Tháng 1</option>
                                <option value="2">Tháng 2</option>
                                <option value="3">Tháng 3</option>
                                <option value="4">Tháng 4</option>
                                <option value="5">Tháng 5</option>
                                <option value="6">Tháng 6</option>
                                <option value="7">Tháng 7</option>
                                <option value="8">Tháng 8</option>
                                <option value="9">Tháng 9</option>
                                <option value="10">Tháng 10</option>
                                <option value="11">Tháng 11</option>
                                <option value="12">Tháng 12</option>
                            </select>
                            <small class="text-muted">Giữ Ctrl/Cmd để chọn nhiều. Để trống = tất cả tháng</small>
                        </div>

                        
                        <div class="col-md-2">
                            <label class="form-label">Giờ bắt đầu <span class="text-danger">*</span></label>
                            <input type="time" name="thoi_gian_bat_dau" class="form-control" required value="08:00">
                        </div>

                        
                        <div class="col-md-2">
                            <label class="form-label">Giờ kết thúc <span class="text-danger">*</span></label>
                            <input type="time" name="thoi_gian_ket_thuc" class="form-control" required value="17:00">
                        </div>

                        
                        <div class="col-md-2">
                            <label class="form-label">Phòng</label>
                            <select name="phong_id" class="form-select">
                                <option value="">-- Không chỉ định --</option>
                                <?php $__currentLoopData = $phongs ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($p->id); ?>"><?php echo e($p->ten); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        
                        <div class="col-md-1">
                            <label class="d-block">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        
        <div class="card card-custom">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0"><i class="bi bi-list-check text-primary me-2"></i>Danh sách lịch làm việc</h5>
            </div>

            <div class="card-body p-0">

                <div class="table-responsive">
                    <table id="lichlamviecTable" class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Ngày</th>
                                <th>Tháng áp dụng</th>
                                <th>Giờ bắt đầu</th>
                                <th>Giờ kết thúc</th>
                                <th>Phòng</th>
                                <th style="width:120px">Hành động</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                                $daysMap = [
                                    0 => 'Chủ nhật',
                                    1 => 'Thứ hai',
                                    2 => 'Thứ ba',
                                    3 => 'Thứ tư',
                                    4 => 'Thứ năm',
                                    5 => 'Thứ sáu',
                                    6 => 'Thứ bảy',
                                ];
                            ?>

                            <?php $__empty_1 = true; $__currentLoopData = $bacSi->lichLamViecs()->with('phong')->orderBy('ngay_trong_tuan')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lich): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="fw-semibold">
                                        <i class="bi bi-calendar-event me-1"></i>
                                        <?php echo e($daysMap[$lich->ngay_trong_tuan]); ?>

                                    </td>

                                    <td>
                                        <?php if(empty($lich->thangs)): ?>
                                            <span class="badge bg-secondary">Tất cả tháng</span>
                                        <?php else: ?>
                                            <?php
                                                $monthsList = explode(',', $lich->thangs);
                                            ?>
                                            <?php $__currentLoopData = $monthsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="badge bg-info me-1">T<?php echo e(trim($m)); ?></span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </td>

                                    <td><?php echo e($lich->thoi_gian_bat_dau); ?></td>
                                    <td><?php echo e($lich->thoi_gian_ket_thuc); ?></td>
                                    <td><?php echo e($lich->phong->ten ?? '-'); ?></td>

                                    <td>
                                        <form action="<?php echo e(route('admin.lichlamviec.destroy', [$bacSi, $lich])); ?>"
                                            method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa lịch này?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>

                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i> Xóa
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                                
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-calendar-x fs-1 d-block mb-3"></i>
                                        <p class="mb-0">Chưa có lịch làm việc nào.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                    </table>
                </div>

            </div>
        </div>

    </div>

<?php $__env->stopSection(); ?>


<?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.datatable-script','data' => ['tableId' => 'lichlamviecTable']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('datatable-script'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tableId' => 'lichlamviecTable']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\WORKING\DACN\DACN\resources\views/admin/lichlamviec/index.blade.php ENDPATH**/ ?>