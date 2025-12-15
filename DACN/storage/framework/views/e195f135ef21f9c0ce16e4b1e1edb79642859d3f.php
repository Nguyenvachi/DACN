

<?php $__env->startSection('title', 'Nhập kết quả siêu âm'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: #1f2937;">
                <i class="fas fa-baby me-2" style="color: #ec4899;"></i>
                Nhập kết quả siêu âm thai
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('doctor.dashboard')); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('doctor.benhan.edit', $sieuAm->benh_an_id)); ?>">Bệnh án #<?php echo e($sieuAm->benh_an_id); ?></a></li>
                    <li class="breadcrumb-item active">Kết quả siêu âm</li>
                </ol>
            </nav>
        </div>
        <a href="<?php echo e(route('doctor.benhan.edit', $sieuAm->benh_an_id)); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            
            <div class="card vc-card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-info-circle me-2" style="color: #3b82f6;"></i>
                        Thông tin chỉ định
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Bệnh nhân:</strong> <?php echo e($sieuAm->benhAn->user->name); ?></p>
                            <p class="mb-2"><strong>Loại siêu âm:</strong> <?php echo e($sieuAm->loai_sieu_am); ?></p>
                            <p class="mb-2"><strong>Ngày chỉ định:</strong> <?php echo e(\Carbon\Carbon::parse($sieuAm->ngay_chi_dinh)->format('d/m/Y H:i')); ?></p>
                        </div>
                        <div class="col-md-6">
                            <?php if($sieuAm->ly_do_chi_dinh): ?>
                            <p class="mb-2"><strong>Lý do chỉ định:</strong><br><?php echo e($sieuAm->ly_do_chi_dinh); ?></p>
                            <?php endif; ?>
                            <?php if($sieuAm->ghi_chu): ?>
                            <p class="mb-2"><strong>Ghi chú:</strong><br><?php echo e($sieuAm->ghi_chu); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            
            <form action="<?php echo e(route('doctor.sieu-am.update', $sieuAm->id)); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                
                <div class="card vc-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-alt me-2" style="color: #10b981;"></i>
                            Thông tin thai kỳ
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tuổi thai (tuần) <span class="text-danger">*</span></label>
                                <input type="number" name="tuoi_thai_tuan" class="form-control" 
                                       value="<?php echo e(old('tuoi_thai_tuan', $sieuAm->tuoi_thai_tuan)); ?>" 
                                       min="0" max="42" step="1" required>
                                <?php $__errorArgs = ['tuoi_thai_tuan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tuổi thai (ngày)</label>
                                <input type="number" name="tuoi_thai_ngay" class="form-control" 
                                       value="<?php echo e(old('tuoi_thai_ngay', $sieuAm->tuoi_thai_ngay)); ?>" 
                                       min="0" max="6" step="1">
                                <small class="text-muted">VD: 12 tuần 3 ngày</small>
                                <?php $__errorArgs = ['tuoi_thai_ngay'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="card vc-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-ruler me-2" style="color: #8b5cf6;"></i>
                            Các chỉ số thai nhi
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">CRL - Chiều dài đầu mông (mm)</label>
                                <input type="number" name="chieu_dai_dau_mong" class="form-control" 
                                       value="<?php echo e(old('chieu_dai_dau_mong', $sieuAm->chieu_dai_dau_mong)); ?>" 
                                       step="0.1" placeholder="VD: 65.3">
                                <small class="text-muted">Crown-Rump Length</small>
                                <?php $__errorArgs = ['chieu_dai_dau_mong'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">BPD - Đường kính hai đỉnh (mm)</label>
                                <input type="number" name="duong_kinh_hai_dinh" class="form-control" 
                                       value="<?php echo e(old('duong_kinh_hai_dinh', $sieuAm->duong_kinh_hai_dinh)); ?>" 
                                       step="0.1" placeholder="VD: 85.2">
                                <small class="text-muted">Biparietal Diameter</small>
                                <?php $__errorArgs = ['duong_kinh_hai_dinh'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">AC - Chu vi bụng (mm)</label>
                                <input type="number" name="chu_vi_bung" class="form-control" 
                                       value="<?php echo e(old('chu_vi_bung', $sieuAm->chu_vi_bung)); ?>" 
                                       step="0.1" placeholder="VD: 285.5">
                                <small class="text-muted">Abdominal Circumference</small>
                                <?php $__errorArgs = ['chu_vi_bung'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">FL - Chiều dài xương đùi (mm)</label>
                                <input type="number" name="chieu_dai_xuong_dui" class="form-control" 
                                       value="<?php echo e(old('chieu_dai_xuong_dui', $sieuAm->chieu_dai_xuong_dui)); ?>" 
                                       step="0.1" placeholder="VD: 65.8">
                                <small class="text-muted">Femur Length</small>
                                <?php $__errorArgs = ['chieu_dai_xuong_dui'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">HC - Chu vi đầu (mm)</label>
                                <input type="number" name="chu_vi_dau" class="form-control" 
                                       value="<?php echo e(old('chu_vi_dau', $sieuAm->chu_vi_dau)); ?>" 
                                       step="0.1" placeholder="VD: 310.2">
                                <small class="text-muted">Head Circumference</small>
                                <?php $__errorArgs = ['chu_vi_dau'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Cân nặng ước tính (gram)</label>
                                <input type="number" name="can_nang_uoc_tinh" class="form-control" 
                                       value="<?php echo e(old('can_nang_uoc_tinh', $sieuAm->can_nang_uoc_tinh)); ?>" 
                                       step="1" placeholder="VD: 2850">
                                <small class="text-muted">Estimated Fetal Weight</small>
                                <?php $__errorArgs = ['can_nang_uoc_tinh'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="card vc-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-notes-medical me-2" style="color: #f59e0b;"></i>
                            Thông tin khác
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nhịp tim thai (bpm)</label>
                                <input type="number" name="nhip_tim_thai" class="form-control" 
                                       value="<?php echo e(old('nhip_tim_thai', $sieuAm->nhip_tim_thai)); ?>" 
                                       step="1" placeholder="VD: 145">
                                <small class="text-muted">Fetal Heart Rate</small>
                                <?php $__errorArgs = ['nhip_tim_thai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">AFI - Lượng nước ối (cm)</label>
                                <input type="number" name="luong_nuoc_oi" class="form-control" 
                                       value="<?php echo e(old('luong_nuoc_oi', $sieuAm->luong_nuoc_oi)); ?>" 
                                       step="0.1" placeholder="VD: 12.5">
                                <small class="text-muted">Amniotic Fluid Index</small>
                                <?php $__errorArgs = ['luong_nuoc_oi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Vị trí thai</label>
                                <select name="vi_tri_thai" class="form-select">
                                    <option value="">-- Chọn vị trí --</option>
                                    <option value="Ngôi đầu" <?php echo e(old('vi_tri_thai', $sieuAm->vi_tri_thai) === 'Ngôi đầu' ? 'selected' : ''); ?>>Ngôi đầu</option>
                                    <option value="Ngôi mông" <?php echo e(old('vi_tri_thai', $sieuAm->vi_tri_thai) === 'Ngôi mông' ? 'selected' : ''); ?>>Ngôi mông</option>
                                    <option value="Ngôi ngang" <?php echo e(old('vi_tri_thai', $sieuAm->vi_tri_thai) === 'Ngôi ngang' ? 'selected' : ''); ?>>Ngôi ngang</option>
                                </select>
                                <?php $__errorArgs = ['vi_tri_thai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Giới tính</label>
                                <select name="gioi_tinh" class="form-select">
                                    <option value="">-- Chưa xác định --</option>
                                    <option value="Nam" <?php echo e(old('gioi_tinh', $sieuAm->gioi_tinh) === 'Nam' ? 'selected' : ''); ?>>Nam</option>
                                    <option value="Nữ" <?php echo e(old('gioi_tinh', $sieuAm->gioi_tinh) === 'Nữ' ? 'selected' : ''); ?>>Nữ</option>
                                </select>
                                <?php $__errorArgs = ['gioi_tinh'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Vị trí nhau thai</label>
                                <input type="text" name="vi_tri_nhau_thai" class="form-control" 
                                       value="<?php echo e(old('vi_tri_nhau_thai', $sieuAm->vi_tri_nhau_thai)); ?>" 
                                       placeholder="VD: Thành trước, thành sau, đáy...">
                                <?php $__errorArgs = ['vi_tri_nhau_thai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="card vc-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-file-medical-alt me-2" style="color: #ef4444;"></i>
                            Kết luận và đánh giá
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Kết luận <span class="text-danger">*</span></label>
                            <textarea name="ket_luan" class="form-control" rows="4" required
                                      placeholder="VD: Thai phát triển tốt, tương xứng tuổi thai. Không thấy dấu hiệu bất thường..."><?php echo e(old('ket_luan', $sieuAm->ket_luan)); ?></textarea>
                            <?php $__errorArgs = ['ket_luan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Đánh giá chung</label>
                            <textarea name="danh_gia" class="form-control" rows="3"
                                      placeholder="Đánh giá tổng quan về tình trạng thai nhi..."><?php echo e(old('danh_gia', $sieuAm->danh_gia)); ?></textarea>
                            <?php $__errorArgs = ['danh_gia'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Hình ảnh siêu âm</label>
                            <input type="file" name="hinh_anh[]" class="form-control" multiple accept="image/*">
                            <small class="text-muted">Có thể chọn nhiều ảnh. Định dạng: JPG, PNG</small>
                            <?php $__errorArgs = ['hinh_anh'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            
                            <?php if($sieuAm->hinh_anh && is_array($sieuAm->hinh_anh)): ?>
                            <div class="mt-2">
                                <small class="text-muted">Hình ảnh hiện có:</small>
                                <div class="d-flex flex-wrap gap-2 mt-1">
                                    <?php $__currentLoopData = $sieuAm->hinh_anh; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <img src="<?php echo e(asset('storage/' . $img)); ?>" alt="Siêu âm" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                
                <input type="hidden" name="trang_thai" value="Hoàn thành">
                <input type="hidden" name="ngay_thuc_hien" value="<?php echo e(now()->format('Y-m-d')); ?>">

                
                <div class="d-flex gap-2 justify-content-end mb-4">
                    <a href="<?php echo e(route('doctor.benhan.edit', $sieuAm->benh_an_id)); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Hủy
                    </a>
                    <button type="submit" class="btn vc-btn-primary">
                        <i class="fas fa-save me-2"></i>Lưu kết quả
                    </button>
                </div>
            </form>
        </div>

        <div class="col-lg-4">
            
            <div class="card vc-card">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-info-circle me-2" style="color: #10b981;"></i>
                        Hướng dẫn nhập kết quả
                    </h6>
                    <ul class="mb-0 small">
                        <li><strong>CRL:</strong> Đo từ đầu đến mông, dùng cho thai 7-14 tuần</li>
                        <li><strong>BPD:</strong> Đường kính hai đỉnh xương sọ</li>
                        <li><strong>AC:</strong> Chu vi vòng bụng thai nhi</li>
                        <li><strong>FL:</strong> Chiều dài xương đùi</li>
                        <li><strong>HC:</strong> Chu vi vòng đầu</li>
                        <li><strong>AFI:</strong> Chỉ số nước ối (bình thường: 5-25cm)</li>
                        <li><strong>Nhịp tim:</strong> Bình thường 120-160 bpm</li>
                    </ul>
                </div>
            </div>

            
            <div class="card vc-card mt-3">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-chart-line me-2" style="color: #f59e0b;"></i>
                        Giá trị tham khảo
                    </h6>
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Tuần thai</th>
                                <th>BPD (mm)</th>
                                <th>FL (mm)</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                            <tr><td>12</td><td>20-24</td><td>7-10</td></tr>
                            <tr><td>16</td><td>33-37</td><td>18-22</td></tr>
                            <tr><td>20</td><td>46-50</td><td>29-33</td></tr>
                            <tr><td>24</td><td>58-62</td><td>41-45</td></tr>
                            <tr><td>28</td><td>68-72</td><td>51-55</td></tr>
                            <tr><td>32</td><td>78-82</td><td>60-64</td></tr>
                            <tr><td>36</td><td>86-90</td><td>67-71</td></tr>
                            <tr><td>40</td><td>92-96</td><td>73-77</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.doctor', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\WORKING\DACN\DACN\resources\views/doctor/sieu-am/edit.blade.php ENDPATH**/ ?>