

<?php $__env->startSection('content'); ?>
    <div class="container-fluid py-4">

        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="fas fa-file-invoice-dollar me-2"></i>Quản lý Hóa đơn
            </h2>
            <a href="<?php echo e(route('admin.hoadon.all-refunds')); ?>" class="btn btn-warning">
                <i class="fas fa-undo me-1"></i> Quản lý hoàn tiền
            </a>
        </div>

        
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo e(session('success')); ?>

                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>


        
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0">
                <h5 class="fw-semibold mb-0">
                    <i class="fas fa-list me-1"></i>Danh sách hóa đơn
                </h5>
            </div>

            <div class="card-body">

                <div class="table-responsive">
                    <table id="hoadonTable" class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="10%">Lịch hẹn</th>
                                <th width="20%">Bệnh nhân</th>
                                <th width="15%">Tổng tiền</th>
                                <th width="15%">Trạng thái</th>
                                <th width="15%">Thanh toán</th>
                                <th width="10%">Hành động</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($it->id); ?></td>

                                    <td>
                                        <span class="badge bg-primary">#<?php echo e($it->lich_hen_id); ?></span>
                                    </td>

                                    <td>
                                        <?php echo e(optional($it->user)->name ?? '#' . $it->user_id); ?>

                                    </td>

                                    <td class="fw-bold text-success">
                                        <?php echo e(number_format($it->tong_tien, 0, ',', '.')); ?> đ
                                    </td>

                                    <td>
                                        <?php
                                            $statusColor = match ($it->trang_thai) {
                                                'Đã thanh toán' => 'success',
                                                'Chưa thanh toán' => 'warning',
                                                'Hủy' => 'danger',
                                                default => 'secondary',
                                            };
                                        ?>

                                        <span class="badge bg-<?php echo e($statusColor); ?>">
                                            <?php echo e($it->trang_thai); ?>

                                        </span>
                                    </td>

                                    <td>
                                        <?php if($it->phuong_thuc): ?>
                                            <span class="badge bg-info">
                                                <?php echo e(strtoupper($it->phuong_thuc)); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">—</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <a href="<?php echo e(route('admin.hoadon.show', $it)); ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Xem
                                        </a>
                                    </td>
                                </tr>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                        <p class="mb-0 text-muted">Chưa có hóa đơn nào</p>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.datatable-script','data' => ['tableId' => 'hoadonTable']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('datatable-script'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tableId' => 'hoadonTable']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\WORKING\DACN\DACN\resources\views/admin/hoadon/index.blade.php ENDPATH**/ ?>