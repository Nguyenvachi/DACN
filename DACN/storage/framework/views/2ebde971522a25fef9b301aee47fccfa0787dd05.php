

<?php $__env->startSection('title', 'Lịch Hẹn Khám'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid px-4 py-4">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeInDown">
            <div>
                <h2 class="fw-bold mb-1" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    <i class="fas fa-calendar-alt me-2" style="-webkit-text-fill-color: #667eea;"></i>Lịch Hẹn Khám
                </h2>
                <p class="text-muted mb-0">Quản lý và theo dõi các lịch hẹn của bạn</p>
            </div>
            <a href="<?php echo e(route('public.bacsi.index')); ?>" class="btn btn-primary px-4 py-2 shadow-sm" 
               style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                <i class="fas fa-plus me-2"></i>Đặt lịch mới
            </a>
        </div>

        <!-- Alerts -->
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0">
                <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0">
                <i class="fas fa-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if($danhSachLichHen->count() > 0): ?>

            <!-- Filter Tabs -->
            <div class="card shadow-sm border-0 mb-4" style="overflow: hidden;">
                <div class="card-body p-0" style="background: linear-gradient(90deg, #f8f9fa 0%, #ffffff 100%);">
                    <ul class="nav nav-pills p-3" role="tablist">
                        <li class="nav-item me-2">
                            <button class="nav-link active rounded-pill px-4 py-2" data-bs-toggle="tab" data-bs-target="#all">
                                <i class="fas fa-list me-2"></i>Tất cả 
                                <span class="badge bg-white text-primary ms-2 shadow-sm"><?php echo e($danhSachLichHen->count()); ?></span>
                            </button>
                        </li>
                        <li class="nav-item me-2">
                            <button class="nav-link rounded-pill px-4 py-2" data-bs-toggle="tab" data-bs-target="#pending">
                                <i class="fas fa-clock me-2"></i>Chờ xác nhận 
                                <span class="badge bg-warning text-white ms-2 shadow-sm"><?php echo e($danhSachLichHen->where('trang_thai', \App\Models\LichHen::STATUS_PENDING_VN)->count()); ?></span>
                            </button>
                        </li>
                        <li class="nav-item me-2">
                            <button class="nav-link rounded-pill px-4 py-2" data-bs-toggle="tab" data-bs-target="#confirmed">
                                <i class="fas fa-check-circle me-2"></i>Đã xác nhận 
                                <span class="badge bg-success text-white ms-2 shadow-sm"><?php echo e($danhSachLichHen->where('trang_thai', \App\Models\LichHen::STATUS_CONFIRMED_VN)->count()); ?></span>
                            </button>
                        </li>
                        <li class="nav-item me-2">
                            <button class="nav-link rounded-pill px-4 py-2" data-bs-toggle="tab" data-bs-target="#completed">
                                <i class="fas fa-check-double me-2"></i>Hoàn thành 
                                <span class="badge bg-info text-white ms-2 shadow-sm"><?php echo e($danhSachLichHen->where('trang_thai', \App\Models\LichHen::STATUS_COMPLETED_VN)->count()); ?></span>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Listing -->
            <div class="card shadow border-0" style="border-radius: 15px; overflow: hidden;">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                            <tr>
                                <th class="border-0 py-3 ps-4">Ngày & Giờ</th>
                                <th class="border-0 py-3">Bác sĩ</th>
                                <th class="border-0 py-3">Dịch vụ</th>
                                <th class="border-0 py-3">Trạng thái</th>
                                <th class="border-0 py-3">Thanh toán</th>
                                <th class="border-0 py-3 text-center pe-4">Hành động</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $__currentLoopData = $danhSachLichHen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lichHen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="border-bottom">

                                    <!-- NGÀY GIỜ -->
                                    <td class="py-3 ps-4">
                                        <div>
                                            <div class="fw-semibold" style="color: #2d3748;">
                                                <?php echo e(\Carbon\Carbon::parse($lichHen->ngay_hen)->format('d/m/Y')); ?>

                                            </div>
                                            <div class="text-muted small">
                                                <?php echo e($lichHen->thoi_gian_hen); ?>

                                            </div>
                                        </div>
                                    </td>

                                    <!-- BÁC SĨ -->
                                    <td class="py-3">
                                        <div>
                                            <div class="fw-semibold" style="color: #2d3748;"><?php echo e($lichHen->bacSi->ho_ten ?? 'N/A'); ?></div>
                                            <div class="text-muted small">
                                                <?php echo e($lichHen->bacSi->chuyen_khoa); ?>

                                            </div>
                                        </div>
                                    </td>

                                    <!-- DỊCH VỤ -->
                                    <td class="py-3">
                                        <span class="badge bg-light text-dark border">
                                            <?php echo e($lichHen->dichVu->ten_dich_vu); ?>

                                        </span>
                                    </td>

                                    <!-- TRẠNG THÁI -->
                                    <td class="py-3">
                                        <?php
                                            $statusClass = match ($lichHen->trang_thai) {
                                                \App\Models\LichHen::STATUS_PENDING_VN => 'warning',
                                                \App\Models\LichHen::STATUS_CONFIRMED_VN => 'success',
                                                \App\Models\LichHen::STATUS_CANCELLED_VN => 'danger',
                                                \App\Models\LichHen::STATUS_COMPLETED_VN => 'info',
                                                default => 'secondary',
                                            };
                                        ?>
                                        <span class="badge bg-<?php echo e($statusClass); ?>">
                                            <?php echo e($lichHen->trang_thai); ?>

                                        </span>
                                    </td>

                                    <!-- THANH TOÁN -->
                                    <td class="py-3">
                                        <?php if($lichHen->is_paid): ?>
                                            <span class="badge bg-success">
                                                Đã thanh toán
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">
                                                Chưa thanh toán
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- ACTIONS -->
                                    <td class="text-center py-3">
                                        <div class="btn-group" role="group">

                                            <!-- XEM CHI TIẾT -->
                                            <button type="button" class="btn btn-sm btn-outline-primary" title="Xem chi tiết"
                                                data-bs-toggle="modal" data-bs-target="#detailModal"
                                                data-ngay="<?php echo e(\Carbon\Carbon::parse($lichHen->ngay_hen)->format('d/m/Y')); ?>"
                                                data-gio="<?php echo e($lichHen->thoi_gian_hen); ?>"
                                                data-bacsi="<?php echo e($lichHen->bacSi->ho_ten); ?>"
                                                data-khoa="<?php echo e($lichHen->bacSi->chuyen_khoa); ?>"
                                                data-dichvu="<?php echo e($lichHen->dichVu->ten_dich_vu); ?>"
                                                data-tien="<?php echo e(number_format($lichHen->tong_tien)); ?>đ"
                                                data-trangthai="<?php echo e($lichHen->trang_thai); ?>"
                                                data-thanhtoan="<?php echo e($lichHen->is_paid ? 'Đã thanh toán' : 'Chưa thanh toán'); ?>"
                                                data-ghichu="<?php echo e($lichHen->ghi_chu); ?>">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            <!-- SỬA -->
                                            <?php if(in_array($lichHen->trang_thai, [\App\Models\LichHen::STATUS_PENDING_VN, \App\Models\LichHen::STATUS_CONFIRMED_VN])): ?>
                                                <button class="btn btn-sm btn-outline-success" title="Chỉnh sửa"
                                                    data-bs-toggle="modal" data-bs-target="#editModal<?php echo e($lichHen->id); ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            <?php endif; ?>

                                            <!-- HỦY -->
                                            <?php if(in_array($lichHen->trang_thai, [\App\Models\LichHen::STATUS_PENDING_VN, \App\Models\LichHen::STATUS_CONFIRMED_VN])): ?>
                                                <button class="btn btn-sm btn-outline-danger" title="Hủy lịch"
                                                    onclick="if(confirm('Xác nhận hủy lịch hẹn?')) document.getElementById('delete<?php echo e($lichHen->id); ?>').submit();">
                                                    <i class="fas fa-times"></i>
                                                </button>

                                                <form id="delete<?php echo e($lichHen->id); ?>" method="POST"
                                                    action="<?php echo e(route('patient.lichhen.destroy', $lichHen->id)); ?>"
                                                    style="display:none;">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                </form>
                                            <?php endif; ?>

                                        </div>
                                    </td>

                                </tr>

                                <!-- Modal SỬA GIỮ NGUYÊN NHƯ CŨ -->
                                <?php if(in_array($lichHen->trang_thai, [\App\Models\LichHen::STATUS_PENDING_VN, \App\Models\LichHen::STATUS_CONFIRMED_VN])): ?>
                                    <div class="modal fade" id="editModal<?php echo e($lichHen->id); ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="<?php echo e(route('lichhen.update', $lichHen->id)); ?>" method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PUT'); ?>
                                                    <div class="modal-header bg-light">
                                                        <h5 class="modal-title">
                                                            <i class="fas fa-edit text-success"></i> Chỉnh sửa lịch hẹn
                                                        </h5>
                                                        <button class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label>Ngày hẹn</label>
                                                            <input type="date" name="ngay_hen" class="form-control"
                                                                value="<?php echo e($lichHen->ngay_hen); ?>" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label>Giờ hẹn</label>
                                                            <input type="time" name="thoi_gian_hen"
                                                                class="form-control"
                                                                value="<?php echo e($lichHen->thoi_gian_hen); ?>" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label>Ghi chú</label>
                                                            <textarea name="ghi_chu" class="form-control" rows="3"><?php echo e($lichHen->ghi_chu); ?></textarea>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Hủy</button>
                                                        <button class="btn-hc-primary"><i class="fas fa-save"></i>
                                                            Lưu</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.patient.empty-state','data' => ['icon' => 'fa-calendar-times','title' => 'Chưa có lịch hẹn','description' => 'Bạn chưa có lịch hẹn nào. Hãy đặt lịch ngay!','actionRoute' => route('public.bacsi.index'),'actionText' => 'Đặt lịch khám','actionIcon' => 'fa-calendar-plus']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('patient.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'fa-calendar-times','title' => 'Chưa có lịch hẹn','description' => 'Bạn chưa có lịch hẹn nào. Hãy đặt lịch ngay!','action-route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('public.bacsi.index')),'action-text' => 'Đặt lịch khám','action-icon' => 'fa-calendar-plus']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>

        <?php endif; ?>
    </div>



    <!-- 🟢🟢🟢 MODAL XEM CHI TIẾT DUY NHẤT (KHÔNG LAG, KHÔNG DUPLICATE) -->
    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header bg-light">
                    <h5 class="modal-title">
                        <i class="fas fa-info-circle text-primary-hc"></i> Chi tiết lịch hẹn
                    </h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="small text-secondary-hc">Ngày hẹn</label>
                            <div id="ctNgay" class="fw-semibold"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="small text-secondary-hc">Giờ hẹn</label>
                            <div id="ctGio" class="fw-semibold"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="small text-secondary-hc">Bác sĩ</label>
                            <div id="ctBacSi" class="fw-semibold"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="small text-secondary-hc">Chuyên khoa</label>
                            <div id="ctKhoa" class="fw-semibold"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="small text-secondary-hc">Dịch vụ</label>
                            <div id="ctDichVu" class="fw-semibold"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="small text-secondary-hc">Tổng tiền</label>
                            <div id="ctTien" class="fw-semibold text-success"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="small text-secondary-hc">Trạng thái</label>
                            <div id="ctTrangThai"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="small text-secondary-hc">Thanh toán</label>
                            <div id="ctThanhToan"></div>
                        </div>

                        <div class="col-12">
                            <label class="small text-secondary-hc">Ghi chú</label>
                            <div id="ctGhiChu" class="p-3 bg-light rounded"></div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>

            </div>
        </div>
    </div>



    <?php $__env->startPush('scripts'); ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const detailModal = document.getElementById('detailModal');

                detailModal.addEventListener('show.bs.modal', function(event) {

                    const btn = event.relatedTarget;

                    document.getElementById("ctNgay").innerText = btn.dataset.ngay;
                    document.getElementById("ctGio").innerText = btn.dataset.gio;
                    document.getElementById("ctBacSi").innerText = btn.dataset.bacsi;
                    document.getElementById("ctKhoa").innerText = btn.dataset.khoa;
                    document.getElementById("ctDichVu").innerText = btn.dataset.dichvu;
                    document.getElementById("ctTien").innerText = btn.dataset.tien;

                    document.getElementById("ctTrangThai").innerHTML =
                        `<span class="badge bg-primary">${btn.dataset.trangthai}</span>`;

                    document.getElementById("ctThanhToan").innerHTML =
                        btn.dataset.thanhtoan === 'Đã thanh toán' ?
                        `<span class="badge bg-success">Đã thanh toán</span>` :
                        `<span class="badge bg-warning text-dark">Chưa thanh toán</span>`;

                    document.getElementById("ctGhiChu").innerText = btn.dataset.ghichu || "Không có ghi chú";
                });

                // Tab filtering functionality
                const tabButtons = document.querySelectorAll('.nav-pills .nav-link');
                const tableRows = document.querySelectorAll('table tbody tr');

                tabButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const target = this.getAttribute('data-bs-target');
                        let filterStatus = '';

                        // Determine which status to filter
                        if (target === '#pending') {
                            filterStatus = '<?php echo e(\App\Models\LichHen::STATUS_PENDING_VN); ?>';
                        } else if (target === '#confirmed') {
                            filterStatus = '<?php echo e(\App\Models\LichHen::STATUS_CONFIRMED_VN); ?>';
                        } else if (target === '#completed') {
                            filterStatus = '<?php echo e(\App\Models\LichHen::STATUS_COMPLETED_VN); ?>';
                        }

                        // Show/hide rows based on filter
                        tableRows.forEach(row => {
                            if (target === '#all') {
                                row.style.display = '';
                            } else {
                                const statusBadge = row.querySelector('td:nth-child(4) .badge');
                                if (statusBadge && statusBadge.textContent.trim() === filterStatus) {
                                    row.style.display = '';
                                } else {
                                    row.style.display = 'none';
                                }
                            }
                        });
                    });
                });

            });
        </script>

        <style>
            /* Smooth transitions */
            .table tbody tr {
                transition: all 0.3s ease;
            }

            .table tbody tr:hover {
                background: linear-gradient(90deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
                transform: translateX(5px);
                box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            }

            /* Button hover effects */
            .btn-group .btn {
                transition: all 0.2s ease;
            }

            .btn-group .btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            }

            /* Badge animations */
            .badge {
                transition: all 0.2s ease;
            }

            .badge:hover {
                transform: scale(1.05);
            }

            /* Nav pills active state */
            .nav-pills .nav-link.active {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
                transition: all 0.3s ease;
            }

            .nav-pills .nav-link {
                transition: all 0.3s ease;
            }

            .nav-pills .nav-link:not(.active):hover {
                background-color: #f8f9fa;
                transform: translateY(-2px);
            }

            /* Card hover effect */
            .card {
                transition: all 0.3s ease;
            }

            .card:hover {
                box-shadow: 0 8px 24px rgba(0,0,0,0.12) !important;
            }

            /* Avatar pulse effect */
            .rounded-circle.bg-gradient {
                animation: subtle-pulse 3s ease-in-out infinite;
            }

            @keyframes subtle-pulse {
                0%, 100% { box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.4); }
                50% { box-shadow: 0 0 0 8px rgba(102, 126, 234, 0); }
            }

            /* Icon date hover */
            .bg-light.rounded:hover {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
                color: white !important;
                transition: all 0.3s ease;
            }

            .bg-light.rounded:hover i {
                color: white !important;
            }
        </style>
    <?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.patient-modern', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\WORKING\DACN\DACN\resources\views/patient/lichhen/index.blade.php ENDPATH**/ ?>