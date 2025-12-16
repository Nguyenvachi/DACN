

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-receipt"></i>
            Tạo hóa đơn từ bệnh án HS-<?php echo e(str_pad($benhAn->id, 4, '0', STR_PAD_LEFT)); ?>

        </h2>
        <a href="<?php echo e(route('admin.benhan.show', $benhAn)); ?>" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <?php if(session('info')): ?>
        <div class="alert alert-info alert-dismissible fade show">
            <i class="bi bi-info-circle me-2"></i>
            <?php echo e(session('info')); ?>

            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-circle me-2"></i>
            <?php echo e(session('error')); ?>

            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-person-badge"></i> Thông tin bệnh nhân</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Họ tên:</strong> <?php echo e($benhAn->benhNhan->name ?? 'N/A'); ?></p>
                    <p><strong>Email:</strong> <?php echo e($benhAn->benhNhan->email ?? 'N/A'); ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Bác sĩ:</strong> <?php echo e($benhAn->bacSi->ho_ten ?? 'N/A'); ?></p>
                    <p><strong>Ngày khám:</strong> <?php echo e($benhAn->ngay_kham); ?></p>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="<?php echo e(route('staff.hoadon.store-from-benh-an', $benhAn)); ?>" id="hoaDonForm">
        <?php echo csrf_field(); ?>
        
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-list-check"></i> Danh sách dịch vụ đã thực hiện</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th width="5%"><input type="checkbox" id="selectAll" checked></th>
                                <th width="10%">Loại</th>
                                <th width="35%">Tên dịch vụ / Thuốc</th>
                                <th width="10%">Số lượng</th>
                                <th width="20%">Đơn giá (VNĐ)</th>
                                <th width="20%">Thành tiền (VNĐ)</th>
                            </tr>
                        </thead>
                        <tbody id="dichVuTable">
                            
                            <?php if($benhAn->lichHen): ?>
                            <?php
                                $dichVuKham = $benhAn->lichHen->dichVu;
                                // Lấy giá từ dịch vụ (field đúng là gia_tien), nếu 0 hoặc null thì dùng giá mặc định 200000
                                $giaDichVu = ($dichVuKham && $dichVuKham->gia_tien > 0) ? $dichVuKham->gia_tien : 200000;
                                $tenDichVu = $dichVuKham ? $dichVuKham->ten_dich_vu : 'Khám bệnh tổng quát';
                            ?>
                            <tr>
                                <td>
                                    <input type="checkbox" class="dv-checkbox" checked
                                        data-loai="dich_vu_kham"
                                        data-id="<?php echo e($benhAn->lichHen->dich_vu_id ?? 0); ?>"
                                        data-ten="Khám bệnh - <?php echo e($tenDichVu); ?>"
                                        data-soluong="1"
                                        data-dongia="<?php echo e($giaDichVu); ?>">
                                </td>
                                <td><span class="badge bg-success">Khám bệnh</span></td>
                                <td><strong><?php echo e($tenDichVu); ?></strong></td>
                                <td><input type="number" class="form-control form-control-sm sl-input" value="1" min="1" style="width: 80px;" readonly></td>
                                <td><input type="number" class="form-control form-control-sm dg-input" value="<?php echo e($giaDichVu); ?>" min="0" step="1000" style="width: 150px;"></td>
                                <td class="tt-cell fw-bold text-end"><?php echo e(number_format($giaDichVu, 0, ',', ',')); ?></td>
                            </tr>
                            <?php endif; ?>

                            
                            <?php $__currentLoopData = $benhAn->noiSois; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ns): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <input type="checkbox" class="dv-checkbox" checked
                                        data-loai="noi_soi"
                                        data-id="<?php echo e($ns->id); ?>"
                                        data-ten="Nội soi - <?php echo e($ns->loai_noi_soi); ?>"
                                        data-soluong="1"
                                        data-dongia="500000">
                                </td>
                                <td><span class="badge bg-info">Nội soi</span></td>
                                <td><strong><?php echo e($ns->loai_noi_soi); ?></strong></td>
                                <td><input type="number" class="form-control form-control-sm sl-input" value="1" min="1" style="width: 80px;"></td>
                                <td><input type="number" class="form-control form-control-sm dg-input" value="500000" min="0" step="1000" style="width: 150px;"></td>
                                <td class="tt-cell fw-bold text-end">500,000</td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            
                            <?php $__currentLoopData = $benhAn->xQuangs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $xq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <input type="checkbox" class="dv-checkbox" checked
                                        data-loai="x_quang"
                                        data-id="<?php echo e($xq->id); ?>"
                                        data-ten="X-quang - <?php echo e($xq->loai_x_quang); ?>"
                                        data-soluong="1"
                                        data-dongia="300000">
                                </td>
                                <td><span class="badge bg-warning text-dark">X-quang</span></td>
                                <td><strong><?php echo e($xq->loai_x_quang); ?></strong></td>
                                <td><input type="number" class="form-control form-control-sm sl-input" value="1" min="1" style="width: 80px;"></td>
                                <td><input type="number" class="form-control form-control-sm dg-input" value="300000" min="0" step="1000" style="width: 150px;"></td>
                                <td class="tt-cell fw-bold text-end">300,000</td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            
                            <?php $__currentLoopData = $benhAn->xetNghiems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $xn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <input type="checkbox" class="dv-checkbox" checked
                                        data-loai="xet_nghiem"
                                        data-id="<?php echo e($xn->id); ?>"
                                        data-ten="Xét nghiệm - <?php echo e($xn->ten_xet_nghiem ?? $xn->loai_xet_nghiem); ?>"
                                        data-soluong="1"
                                        data-dongia="200000">
                                </td>
                                <td><span class="badge bg-primary">Xét nghiệm</span></td>
                                <td><strong><?php echo e($xn->ten_xet_nghiem ?? $xn->loai_xet_nghiem ?? 'Xét nghiệm'); ?></strong></td>
                                <td><input type="number" class="form-control form-control-sm sl-input" value="1" min="1" style="width: 80px;"></td>
                                <td><input type="number" class="form-control form-control-sm dg-input" value="200000" min="0" step="1000" style="width: 150px;"></td>
                                <td class="tt-cell fw-bold text-end">200,000</td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            
                            <?php $__currentLoopData = $benhAn->thuThuats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $giaThuThuat = (float) ($tt->gia_tien ?? 0);
                                if ($giaThuThuat <= 0 && isset($thuThuatPriceMap)) {
                                    $giaThuThuat = (float) ($thuThuatPriceMap[$tt->ten_thu_thuat] ?? 0);
                                }
                                if ($giaThuThuat <= 0) {
                                    $giaThuThuat = 1000000;
                                }
                            ?>
                            <tr>
                                <td>
                                    <input type="checkbox" class="dv-checkbox" checked
                                        data-loai="thu_thuat"
                                        data-id="<?php echo e($tt->id); ?>"
                                        data-ten="Thủ thuật - <?php echo e($tt->ten_thu_thuat); ?>"
                                        data-soluong="1"
                                        data-dongia="<?php echo e($giaThuThuat); ?>">
                                </td>
                                <td><span class="badge bg-danger">Thủ thuật</span></td>
                                <td><strong><?php echo e($tt->ten_thu_thuat); ?></strong></td>
                                <td><input type="number" class="form-control form-control-sm sl-input" value="1" min="1" style="width: 80px;"></td>
                                <td><input type="number" class="form-control form-control-sm dg-input" value="<?php echo e($giaThuThuat); ?>" min="0" step="1000" style="width: 150px;"></td>
                                <td class="tt-cell fw-bold text-end"><?php echo e(number_format($giaThuThuat, 0, ',', ',')); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            
                            <?php $__currentLoopData = $benhAn->sieuAms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <input type="checkbox" class="dv-checkbox" checked
                                        data-loai="sieu_am"
                                        data-id="<?php echo e($sa->id); ?>"
                                        data-ten="Siêu âm - <?php echo e($sa->loai_sieu_am); ?>"
                                        data-soluong="1"
                                        data-dongia="400000">
                                </td>
                                <td><span class="badge bg-purple" style="background-color: #6f42c1 !important;">Siêu âm</span></td>
                                <td><strong><?php echo e($sa->loai_sieu_am); ?></strong></td>
                                <td><input type="number" class="form-control form-control-sm sl-input" value="1" min="1" style="width: 80px;"></td>
                                <td><input type="number" class="form-control form-control-sm dg-input" value="400000" min="0" step="1000" style="width: 150px;"></td>
                                <td class="tt-cell fw-bold text-end">400,000</td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            
                            <?php $__currentLoopData = $benhAn->donThuocs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $don): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $__currentLoopData = $don->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $giaThuoc = $item->thuoc->gia_tham_khao ?? 50000;
                                    $soLuong = $item->so_luong;
                                    $thanhTien = $giaThuoc * $soLuong;
                                ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" class="dv-checkbox" checked
                                            data-loai="thuoc"
                                            data-id="<?php echo e($item->thuoc_id); ?>"
                                            data-ten="Thuốc - <?php echo e($item->thuoc->ten ?? 'N/A'); ?>"
                                            data-soluong="<?php echo e($soLuong); ?>"
                                            data-dongia="<?php echo e($giaThuoc); ?>">
                                    </td>
                                    <td><span class="badge bg-secondary">Thuốc</span></td>
                                    <td>
                                        <strong><?php echo e($item->thuoc->ten ?? 'N/A'); ?></strong>
                                        <br><small class="text-muted"><?php echo e($item->thuoc->ham_luong ?? ''); ?> - <?php echo e($item->lieu_dung); ?></small>
                                    </td>
                                    <td><input type="number" class="form-control form-control-sm sl-input" value="<?php echo e($soLuong); ?>" min="1" style="width: 80px;"></td>
                                    <td><input type="number" class="form-control form-control-sm dg-input" value="<?php echo e($giaThuoc); ?>" min="0" step="100" style="width: 150px;"></td>
                                    <td class="tt-cell fw-bold text-end"><?php echo e(number_format($thanhTien, 0, ',', ',')); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <?php if($benhAn->noiSois->isEmpty() && $benhAn->xQuangs->isEmpty() && $benhAn->xetNghiems->isEmpty() && $benhAn->thuThuats->isEmpty() && $benhAn->sieuAms->isEmpty() && $benhAn->donThuocs->isEmpty() && !$benhAn->lichHen): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    Không có dịch vụ hoặc thuốc nào
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="5" class="text-end fs-5">TỔNG TIỀN PHẢI TRẢ:</th>
                                <th id="tongTien" class="text-danger fs-4 text-end">0 VNĐ</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="mb-3 mt-3">
                    <label class="form-label">Ghi chú</label>
                    <textarea name="ghi_chu" class="form-control" rows="2" placeholder="Ghi chú thêm (nếu có)"></textarea>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="<?php echo e(route('admin.benhan.show', $benhAn)); ?>" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Hủy
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Tạo hóa đơn
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.dv-checkbox');
    const form = document.getElementById('hoaDonForm');

    // Tính tổng tiền
    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('tbody tr').forEach(row => {
            const checkbox = row.querySelector('.dv-checkbox');
            if (checkbox && checkbox.checked) {
                const soLuong = parseInt(row.querySelector('.sl-input').value) || 0;
                const donGia = parseInt(row.querySelector('.dg-input').value) || 0;
                const thanhTien = soLuong * donGia;
                row.querySelector('.tt-cell').textContent = thanhTien.toLocaleString('vi-VN');
                total += thanhTien;
            }
        });
        document.getElementById('tongTien').textContent = total.toLocaleString('vi-VN') + ' VNĐ';
    }

    // Select/Deselect all
    selectAllCheckbox.addEventListener('change', function() {
        checkboxes.forEach(cb => cb.checked = this.checked);
        calculateTotal();
    });

    // Checkbox change
    checkboxes.forEach(cb => {
        cb.addEventListener('change', calculateTotal);
    });

    // Input change
    document.querySelectorAll('.sl-input, .dg-input').forEach(input => {
        input.addEventListener('input', calculateTotal);
    });

    // Submit form
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const dichVu = [];
        document.querySelectorAll('tbody tr').forEach(row => {
            const checkbox = row.querySelector('.dv-checkbox');
            if (checkbox && checkbox.checked) {
                const soLuong = parseInt(row.querySelector('.sl-input').value);
                const donGia = parseInt(row.querySelector('.dg-input').value);
                
                dichVu.push({
                    loai: checkbox.dataset.loai,
                    id: checkbox.dataset.id,
                    ten: checkbox.dataset.ten,
                    so_luong: soLuong,
                    don_gia: donGia
                });
            }
        });

        if (dichVu.length === 0) {
            alert('Vui lòng chọn ít nhất một dịch vụ!');
            return;
        }

        // Add hidden inputs
        dichVu.forEach((dv, index) => {
            Object.keys(dv).forEach(key => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `dich_vu[${index}][${key}]`;
                input.value = dv[key];
                form.appendChild(input);
            });
        });

        form.submit();
    });

    // Initial calculation
    calculateTotal();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.staff', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\WORKING\DACN\DACN\resources\views/staff/hoadon/create-from-benh-an.blade.php ENDPATH**/ ?>