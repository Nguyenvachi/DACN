

<?php $__env->startSection('content'); ?>
    <style>
        .card-custom {
            border-radius: 12px;
            border: none;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        }
    </style>

    <div class="container-fluid py-4">

        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-door-closed-fill text-primary me-2"></i>
                Quản lý Phòng khám
            </h2>

            <a href="<?php echo e(route('admin.phong.create')); ?>" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Thêm phòng
            </a>
        </div>

        
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        
        <div class="card card-custom">
            <div class="card-body p-0">

                <div class="table-responsive">
                    <table id="phongTable" class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tên phòng</th>
                                <th>Loại</th>
                                <th>Số bác sĩ</th>
                                <th style="width: 150px" class="text-center">Hành động</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $phongs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="fw-semibold"><?php echo e($p->ten); ?></td>
                                    <td>
                                        <?php if($p->loaiPhong): ?>
                                            <span class="badge bg-primary"><?php echo e($p->loaiPhong->ten); ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Chưa gán</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($p->bac_sis_count); ?></td>

                                    <td class="text-center text-nowrap">

                                        <a href="<?php echo e(route('admin.phong.edit', $p)); ?>"
                                            class="btn btn-sm btn-outline-primary me-1"
                                            title="Sửa">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <form action="<?php echo e(route('admin.phong.destroy', $p)); ?>" method="POST" class="d-inline"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa phòng này?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>

                                            <button class="btn btn-sm btn-outline-danger" title="Xóa">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>

                                    </td>
                                </tr>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="bi bi-door-closed fs-2 d-block mb-2"></i>
                                        Chưa có phòng
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                    </table>
                </div>

            </div>

            
            <div class="card-footer d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    <?php if($phongs->total() > 0): ?>
                        Hiển thị <?php echo e($phongs->firstItem()); ?> - <?php echo e($phongs->lastItem()); ?> / <?php echo e($phongs->total()); ?> phòng
                    <?php else: ?>
                        Không có dữ liệu
                    <?php endif; ?>
                </div>

                <div>
                    <?php echo e($phongs->links()); ?>

                </div>
            </div>

        </div>

    </div>
<?php $__env->stopSection(); ?>


<?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.datatable-script','data' => ['tableId' => 'phongTable']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('datatable-script'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tableId' => 'phongTable']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\WORKING\DACN\DACN\resources\views/admin/phong/index.blade.php ENDPATH**/ ?>