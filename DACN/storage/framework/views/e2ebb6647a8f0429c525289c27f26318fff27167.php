

<?php $__env->startSection('content'); ?>
    <div class="container-fluid py-4">
        <h1 class="mb-4">Danh sách Bác sĩ</h1>

        <?php if(session('status')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo e(session('status')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card">

            <!-- =========================
                             🔥 BỔ SUNG: Bộ lọc + tìm kiếm
                        ========================== -->
            <div class="p-3 border-bottom bg-light rounded">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="keyword" class="form-control"
                            placeholder="Tìm tên bác sĩ..." value="<?php echo e(request('keyword')); ?>">
                    </div>

                    <div class="col-md-3">
                        <select name="trang_thai" class="form-select">
                            <option value="">-- Trạng thái --</option>
                            <?php $__currentLoopData = ($trangThaiOptions ?? ['Đang hoạt động', 'Ngừng hoạt động']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($tt); ?>" <?php echo e(request('trang_thai') == $tt ? 'selected' : ''); ?>><?php echo e($tt); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-2 d-grid">
                        <button class="btn btn-dark btn-sm">
                            <i class="bi bi-funnel"></i> Lọc
                        </button>
                    </div>
                </form>
            </div>
            <!-- ========================= -->


            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Danh sách Bác sĩ</h5>
                <a href="<?php echo e(route('admin.bac-si.create')); ?>" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Thêm Bác sĩ mới
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="bacsiTable" class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">ID</th>
                                <th width="15%">Họ Tên</th>
                                <th width="12%">Số điện thoại</th>
                                <th width="15%">Email</th>
                                <th width="8%">Kinh nghiệm</th>
                                <th width="10%">Trạng thái</th>
                                <th width="35%">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $bacSis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bacSi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($bacSi->id); ?></td>
                                    <td><strong><?php echo e($bacSi->ho_ten); ?></strong></td>
                                    <td><?php echo e($bacSi->so_dien_thoai); ?></td>
                                    <td><?php echo e($bacSi->email ?? 'N/A'); ?></td>
                                    <td><?php echo e($bacSi->kinh_nghiem ?? 0); ?> năm</td>
                                    <td>
                                        <?php if($bacSi->trang_thai == 'Đang hoạt động'): ?>
                                            <span class="badge bg-success">Đang hoạt động</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Ngừng hoạt động</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>

                                        <!-- Xem chi tiết bác sĩ -->
                                        <a href="<?php echo e(route('admin.bac-si.show', $bacSi)); ?>"
                                           class="btn btn-sm btn-outline-info me-1 mb-1"
                                           title="Xem chi tiết">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="<?php echo e(route('admin.bac-si.edit', $bacSi)); ?>"
                                            class="btn btn-sm btn-outline-primary me-1 mb-1"
                                            title="Sửa">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <a href="<?php echo e(route('admin.lichlamviec.index', $bacSi)); ?>"
                                            class="btn btn-sm btn-outline-success me-1 mb-1"
                                            title="Lịch làm việc">
                                            <i class="bi bi-calendar-check"></i>
                                        </a>

                                        <a href="<?php echo e(route('admin.lichnghi.index', $bacSi)); ?>"
                                            class="btn btn-sm btn-outline-secondary me-1 mb-1"
                                            title="Lịch nghỉ">
                                            <i class="bi bi-calendar-x"></i>
                                        </a>

                                        <a href="<?php echo e(route('admin.cadieuchinh.index', $bacSi)); ?>"
                                            class="btn btn-sm btn-outline-purple me-1 mb-1"
                                            title="Ca điều chỉnh">
                                            <i class="bi bi-calendar2-event"></i>
                                        </a>

                                        <form action="<?php echo e(route('admin.bac-si.destroy', $bacSi)); ?>" method="POST"
                                            class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa bác sĩ này?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger mb-1" title="Xóa">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="bi bi-inbox fs-2 text-muted mb-3 d-block"></i>
                                        <p class="mb-0">Chưa có bác sĩ nào.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if($bacSis->hasPages()): ?>
                    <div class="mt-4">
                        <?php echo e($bacSis->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- =========================
                     🔥 BỔ SUNG: CSS làm đẹp UI
                ========================== -->
    <style>
        .table th {
            background: #f8f9fc !important;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 13px;
        }

        .table-striped>tbody>tr:nth-of-type(odd) {
            background-color: #fafafa;
        }

        .card {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .btn-outline-purple {
            color: #a855f7;
            border-color: #a855f7;
        }

        .btn-outline-purple:hover {
            background-color: #a855f7;
            color: white;
            border-color: #a855f7;
        }
    </style>
<?php $__env->stopSection(); ?>


<?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.datatable-script','data' => ['tableId' => 'bacsiTable']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('datatable-script'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tableId' => 'bacsiTable']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\WORKING\DACN\DACN\resources\views/admin/bacsi/index.blade.php ENDPATH**/ ?>