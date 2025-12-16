<?php
    $isPatient = auth()->check() && auth()->user()->role === 'patient';
    $layout = $isPatient ? 'layouts.patient-modern' : 'layouts.app';
?>



<?php $__env->startSection('content'); ?>
    <style>
        /* ===== Color System ===== */
        :root {
            --primary: #2563eb;      /* Blue - primary actions */
            --primary-hover: #1d4ed8;
            --primary-light: #eff6ff;
            
            --success: #10b981;      /* Green - success states */
            --success-light: #ecfdf5;
            
            --warning: #f59e0b;      /* Orange - warnings */
            --warning-light: #fffbeb;
            
            --danger: #ef4444;       /* Red - errors/full */
            --danger-light: #fef2f2;
            
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-900: #111827;
            
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            
            --transition: all 0.2s ease;
        }

        body {
            background: var(--gray-50);
            color: var(--gray-900);
        }

        /* ===== Header ===== */
        .schedule-header {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow);
            border-left: 4px solid var(--primary);
        }

        .schedule-header h2 {
            color: var(--gray-900);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .schedule-header p {
            color: var(--gray-600);
            margin-bottom: 0;
        }

        /* ===== Navigation ===== */
        .week-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            background: white;
            padding: 1.25rem;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
        }

        .week-navigation h4 {
            color: var(--gray-900);
            font-weight: 600;
            font-size: 1.125rem;
        }

        .week-navigation .btn {
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: var(--transition);
        }

        .week-navigation .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
        }

        .week-navigation .btn-outline-primary:hover {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
            transform: translateX(0);
        }

        /* ===== Filter Tabs ===== */
        .filter-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            background: white;
            padding: 1rem;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
        }

        .filter-tab {
            padding: 0.625rem 1rem;
            border-radius: 8px;
            border: 1.5px solid var(--gray-200);
            background: white;
            color: var(--gray-700);
            font-weight: 500;
            font-size: 0.875rem;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .filter-tab:hover {
            border-color: var(--primary);
            background: var(--primary-light);
            color: var(--primary);
        }

        .filter-tab.active {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
        }

        .filter-tab i {
            font-size: 14px;
        }

        /* ===== Day Selector ===== */
        .day-selector {
            display: flex;
            gap: 12px;
            margin-bottom: 1.5rem;
            overflow-x: auto;
            padding: 1rem;
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            scrollbar-width: thin;
        }

        .day-selector::-webkit-scrollbar {
            height: 6px;
        }

        .day-selector::-webkit-scrollbar-track {
            background: var(--gray-100);
            border-radius: 3px;
        }

        .day-selector::-webkit-scrollbar-thumb {
            background: var(--gray-300);
            border-radius: 3px;
        }

        .day-card {
            min-width: 100px;
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
            border: 1.5px solid var(--gray-200);
            background: white;
        }

        .day-card:hover {
            border-color: var(--primary);
            background: var(--primary-light);
        }

        .day-card.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .day-card .day-name {
            font-weight: 600;
            font-size: 0.875rem;
            margin-bottom: 4px;
            color: inherit;
        }

        .day-card .day-date {
            font-size: 1.5rem;
            font-weight: 700;
            color: inherit;
        }

        /* ===== Slots Container ===== */
        .slots-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .slot-card {
            background: white;
            border-radius: 8px;
            padding: 1.25rem;
            cursor: pointer;
            transition: var(--transition);
            border: 1.5px solid var(--gray-200);
            position: relative;
        }

        /* Available slots */
        .slot-card.slot-available:hover {
            border-color: var(--primary);
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        /* Partial slots */
        .slot-card.slot-partial {
            background: var(--warning-light);
            border-color: var(--warning);
        }

        .slot-card.slot-partial:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        /* My bookings */
        .slot-card.slot-my-booking {
            background: var(--success-light);
            border-color: var(--success);
            cursor: default;
        }

        /* Full slots */
        .slot-card.slot-full {
            background: var(--gray-100);
            border-color: var(--gray-300);
            opacity: 0.7;
            cursor: not-allowed;
        }

        .slot-time {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .slot-time i {
            font-size: 1rem;
            color: var(--gray-600);
        }

        .slot-status {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .slot-status.status-empty {
            background: var(--primary-light);
            color: var(--primary);
        }

        .slot-status.status-partial {
            background: var(--warning-light);
            color: var(--warning);
        }

        .slot-status.status-my {
            background: var(--success-light);
            color: var(--success);
        }

        .slot-status.status-full {
            background: var(--gray-200);
            color: var(--gray-600);
        }

        .no-slots-message {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
        }

        .no-slots-message i {
            font-size: 3rem;
            color: var(--gray-300);
            margin-bottom: 1rem;
        }

        .no-slots-message h4 {
            color: var(--gray-700);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .no-slots-message p {
            color: var(--gray-600);
        }

        /* ===== Responsive ===== */
        @media (max-width: 768px) {
            .slots-container {
                grid-template-columns: 1fr;
            }
            
            .week-navigation {
                flex-direction: column;
                gap: 1rem;
            }

            .schedule-header {
                padding: 1.5rem;
            }
        }
    </style>

    <div class="container py-4">
        <!-- Header -->
        <div class="schedule-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2>
                        <i class="fas fa-calendar-week"></i>
                        Lịch khám - BS. <?php echo e($bacSi->ho_ten); ?>

                    </h2>
                    <p>
                        <i class="fas fa-stethoscope"></i>
                        <?php echo e($bacSi->chuyenKhoa->ten ?? 'Sản phụ khoa'); ?>

                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="<?php echo e(route('public.bacsi.index')); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>
        </div>

        <!-- Week Navigation -->
        <div class="week-navigation">
            <?php
                $currentWeekStart = now()->startOfWeek();
                $isCurrentWeek = $weekStart->format('Y-m-d') === $currentWeekStart->format('Y-m-d');
            ?>
            
            <?php if(!$isCurrentWeek): ?>
                <a href="<?php echo e(route('public.bacsi.schedule', ['bacSi' => $bacSi->id, 'week_start' => $weekStart->copy()->subWeek()->format('Y-m-d')])); ?>"
                    class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-chevron-left"></i> Tuần trước
                </a>
            <?php else: ?>
                <div></div>
            <?php endif; ?>
            
            <h4 class="mb-0 text-center">
                <i class="fas fa-calendar-week text-primary"></i>
                <?php echo e($weekStart->format('d/m/Y')); ?> - <?php echo e($weekEnd->format('d/m/Y')); ?>

                <?php if($isCurrentWeek): ?>
                    <span class="badge bg-success ms-2">Tuần này</span>
                <?php endif; ?>
            </h4>
            <a href="<?php echo e(route('public.bacsi.schedule', ['bacSi' => $bacSi->id, 'week_start' => $weekStart->copy()->addWeek()->format('Y-m-d')])); ?>"
                class="btn btn-outline-primary btn-lg">
                Tuần sau <i class="fas fa-chevron-right"></i>
            </a>
        </div>

        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <button class="filter-tab" data-filter="empty" onclick="filterSlots('empty')">
                <i class="far fa-clock"></i>
                <span>Còn trống <span id="count-empty">(0)</span></span>
            </button>
            <button class="filter-tab" data-filter="partial" onclick="filterSlots('partial')">
                <i class="fas fa-user-plus"></i>
                <span>Đã có người <span id="count-partial">(0)</span></span>
            </button>
            <button class="filter-tab" data-filter="my" onclick="filterSlots('my')">
                <i class="fas fa-check-circle"></i>
                <span>Đã đặt <span id="count-my">(0)</span></span>
            </button>
            <button class="filter-tab" data-filter="full" onclick="filterSlots('full')">
                <i class="fas fa-times-circle"></i>
                <span>Đã đầy <span id="count-full">(0)</span></span>
            </button>
            <button class="filter-tab active" data-filter="all" onclick="filterSlots('all')">
                <i class="fas fa-list"></i>
                <span>Tất cả</span>
            </button>
        </div>

        <!-- Day Selector -->
        <div class="day-selector">
            <?php
                $daysOfWeek = ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'];
                $currentDay = $weekStart->copy();
                $today = now()->startOfDay();
            ?>

            <?php for($i = 0; $i < 7; $i++): ?>
                <?php
                    $dateStr = $currentDay->format('Y-m-d');
                    $isPastDay = $currentDay->lt($today);
                ?>

                <?php if(!$isPastDay): ?>
                    <div class="day-card <?php echo e($currentDay->isToday() ? 'active' : ''); ?>" 
                         data-date="<?php echo e($dateStr); ?>"
                         onclick="selectDay('<?php echo e($dateStr); ?>')">
                        <div class="day-name"><?php echo e($daysOfWeek[$i]); ?></div>
                        <div class="day-date"><?php echo e($currentDay->format('d/m')); ?></div>
                        <?php if($currentDay->isToday()): ?>
                            <small class="badge bg-success mt-1" style="font-size: 0.7rem;">Hôm nay</small>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php
                    $currentDay->addDay();
                ?>
            <?php endfor; ?>
        </div>

        <!-- Slots Container -->
        <div id="slotsContainer">
            <?php
                $currentDay = $weekStart->copy();
                $today = now()->startOfDay();
                $hasSlots = false;
            ?>

            <?php for($i = 0; $i < 7; $i++): ?>
                <?php
                    $dateStr = $currentDay->format('Y-m-d');
                    $daySlots = $slotsByDate->get($dateStr, collect());
                    $isPastDay = $currentDay->lt($today);
                ?>

                <?php if(!$isPastDay && $daySlots->isNotEmpty()): ?>
                    <div class="day-slots" data-date="<?php echo e($dateStr); ?>" style="<?php echo e($currentDay->isToday() ? '' : 'display: none;'); ?>">
                        <div class="slots-container">
                            <?php $__currentLoopData = $daySlots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $userId = auth()->id();
                                    $userPhone = auth()->check() && auth()->user()->benh_nhan 
                                        ? auth()->user()->benh_nhan->so_dien_thoai 
                                        : null;
                                    
                                    $hasBooked = false;
                                    $slotTime = \Carbon\Carbon::createFromFormat('H:i', $slot['start'])->format('H:i:s');
                                    
                                    if ($userId || $userPhone) {
                                        $query = \App\Models\LichHen::where('bac_si_id', $bacSi->id)
                                            ->where('ngay_hen', $dateStr)
                                            ->where('thoi_gian_hen', $slotTime)
                                            ->whereNotIn('trang_thai', [\App\Models\LichHen::STATUS_CANCELLED_VN]);
                                        
                                        if ($userId) {
                                            $query->where('user_id', $userId);
                                        } elseif ($userPhone) {
                                            $query->where('so_dien_thoai_benh_nhan', $userPhone);
                                        }
                                        
                                        $hasBooked = $query->exists();
                                    }
                                    
                                    $bookedCount = $slot['booked_count'] ?? 0;
                                    $isFull = $slot['is_full'] ?? false;
                                    
                                    if($hasBooked) {
                                        $slotClass = 'slot-my-booking';
                                        $statusClass = 'status-my';
                                        $statusIcon = 'fa-check-circle';
                                        $statusText = 'Đã đặt';
                                        $dataFilter = 'my';
                                    } elseif($isFull) {
                                        $slotClass = 'slot-full';
                                        $statusClass = 'status-full';
                                        $statusIcon = 'fa-user-clock';
                                        $statusText = "{$bookedCount}/2";
                                        $dataFilter = 'full';
                                    } elseif($bookedCount > 0) {
                                        $slotClass = 'slot-partial';
                                        $statusClass = 'status-partial';
                                        $statusIcon = 'fa-user-plus';
                                        $statusText = "{$bookedCount}/2";
                                        $dataFilter = 'partial';
                                    } else {
                                        $slotClass = 'slot-available';
                                        $statusClass = 'status-empty';
                                        $statusIcon = 'far fa-clock';
                                        $statusText = 'Còn trống';
                                        $dataFilter = 'empty';
                                    }
                                    $hasSlots = true;
                                ?>
                                
                                <div class="slot-card <?php echo e($slotClass); ?>" 
                                     data-filter="<?php echo e($dataFilter); ?>"
                                     <?php if(!$hasBooked && !$isFull): ?> onclick="bookSlot('<?php echo e($dateStr); ?>', '<?php echo e($slot['start']); ?>')" <?php endif; ?>>
                                    <div class="slot-time">
                                        <i class="fas fa-clock"></i>
                                        <?php echo e($slot['start']); ?> - <?php echo e($slot['end']); ?>

                                    </div>
                                    <span class="slot-status <?php echo e($statusClass); ?>">
                                        <i class="fas <?php echo e($statusIcon); ?>"></i>
                                        <?php echo e($statusText); ?>

                                    </span>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php
                    $currentDay->addDay();
                ?>
            <?php endfor; ?>

            <?php if(!$hasSlots): ?>
                <div class="no-slots-message">
                    <i class="fas fa-calendar-times"></i>
                    <h4>Không có lịch khả dụng</h4>
                    <p class="text-muted">Bác sĩ chưa mở lịch cho tuần này</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Booking Modal -->
        <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                        <h5 class="modal-title" id="bookingModalLabel">
                            <i class="fas fa-calendar-check"></i> Đặt Lịch Khám
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="bookingForm" action="<?php echo e(route('lichhen.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="bac_si_id" value="<?php echo e($bacSi->id); ?>">
                        <input type="hidden" name="ngay_hen" id="modal_ngay_hen">
                        <input type="hidden" name="thoi_gian_hen" id="modal_thoi_gian_hen">
                        <input type="hidden" name="payment_gateway" id="payment_gateway" value="">

                        <div class="modal-body">
                            <!-- Thông tin bác sĩ -->
                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <h6 class="card-title mb-2"><i class="fas fa-user-md text-primary"></i> Thông tin bác sĩ</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Bác sĩ:</strong> <?php echo e($bacSi->ho_ten); ?>

                                        </div>
                                        <div class="col-md-6">
                                            <strong>Chuyên khoa:</strong> <?php echo e($bacSi->chuyen_khoa); ?>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Thời gian đã chọn -->
                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <h6 class="card-title mb-2"><i class="fas fa-clock text-success"></i> Thời gian khám</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Ngày:</strong> <span id="display_ngay_hen"></span>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Giờ:</strong> <span id="display_thoi_gian_hen"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Chọn dịch vụ -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-stethoscope text-primary"></i> Chọn dịch vụ <span class="text-danger">*</span>
                                </label>
                                <select name="dich_vu_id" id="dich_vu_select" class="form-select" required>
                                    <option value="">-- Chọn dịch vụ --</option>
                                    <?php
                                        $dichVus = \App\Models\DichVu::where('loai', 'Cơ bản')
                                            ->where('hoat_dong', true)
                                            ->orderBy('ten_dich_vu')
                                            ->get();
                                    ?>
                                    <?php $__currentLoopData = $dichVus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($dv->id); ?>" 
                                                data-price="<?php echo e($dv->gia_tien); ?>" 
                                                data-duration="<?php echo e($dv->thoi_gian); ?>">
                                            <?php echo e($dv->ten_dich_vu); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <!-- Hiển thị giá tiền -->
                            <div id="service_info" class="card border-primary mb-3" style="display: none;">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-money-bill-wave text-success me-2 fs-4"></i>
                                                <div>
                                                    <small class="text-muted">Chi phí dự kiến</small>
                                                    <div class="fw-bold text-success fs-5" id="service_price">0 ₫</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-clock text-info me-2 fs-4"></i>
                                                <div>
                                                    <small class="text-muted">Thời gian khám</small>
                                                    <div class="fw-bold text-info fs-5" id="service_duration">0 phút</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php if(auth()->guard()->check()): ?>
                                <!-- Thông tin bệnh nhân (đã đăng nhập) -->
                                <div class="card bg-light mb-3">
                                    <div class="card-body">
                                        <h6 class="card-title mb-2"><i class="fas fa-user text-primary"></i> Thông tin bệnh nhân</h6>
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <strong>Họ tên:</strong> <?php echo e(auth()->user()->name); ?>

                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <strong>Email:</strong> <?php echo e(auth()->user()->email); ?>

                                            </div>
                                            <?php if(auth()->user()->benh_nhan): ?>
                                                <div class="col-md-6">
                                                    <strong>SĐT:</strong> <?php echo e(auth()->user()->benh_nhan->so_dien_thoai ?? 'Chưa cập nhật'); ?>

                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Ngày sinh:</strong> <?php echo e(auth()->user()->benh_nhan->ngay_sinh ? \Carbon\Carbon::parse(auth()->user()->benh_nhan->ngay_sinh)->format('d/m/Y') : 'Chưa cập nhật'); ?>

                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <!-- Hidden inputs cho user đã đăng nhập -->
                                <input type="hidden" name="ho_ten" value="<?php echo e(auth()->user()->name); ?>">
                                <input type="hidden" name="so_dien_thoai" value="<?php echo e(auth()->user()->benh_nhan->so_dien_thoai ?? ''); ?>">
                            <?php else: ?>
                                <!-- Form thông tin bệnh nhân (chưa đăng nhập) -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Họ tên <span class="text-danger">*</span></label>
                                        <input type="text" name="ho_ten" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                                        <input type="text" name="so_dien_thoai" class="form-control" required>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Ghi chú -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Ghi chú (tùy chọn)</label>
                                <textarea name="ghi_chu" class="form-control" rows="3" placeholder="Mô tả triệu chứng hoặc lý do khám..."></textarea>
                            </div>

                            <!-- Hình thức thanh toán -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-credit-card text-warning"></i> Hình thức thanh toán <span class="text-danger">*</span>
                                </label>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <div class="form-check card p-3 h-100">
                                            <input class="form-check-input" type="radio" name="payment_method" id="payment_tien_mat" value="tien_mat" checked>
                                            <label class="form-check-label w-100" for="payment_tien_mat">
                                                <i class="fas fa-money-bill-wave text-success"></i> Tiền mặt
                                                <small class="d-block text-muted">Thanh toán tại phòng khám</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check card p-3 h-100">
                                            <input class="form-check-input" type="radio" name="payment_method" id="payment_chuyen_khoan" value="chuyen_khoan">
                                            <label class="form-check-label w-100" for="payment_chuyen_khoan">
                                                <i class="fas fa-university text-primary"></i> Chuyển khoản
                                                <small class="d-block text-muted">Thanh toán qua VNPay/Momo</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times"></i> Hủy
                            </button>
                            <button type="button" class="btn btn-primary" id="submit_booking">
                                <i class="fas fa-check"></i> Xác nhận đặt lịch
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Payment Gateway Modal -->
        <div class="modal fade" id="paymentGatewayModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-credit-card"></i> Chọn phương thức thanh toán
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="text-center mb-3">Vui lòng chọn cổng thanh toán:</p>
                        <div class="row g-3">
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-primary w-100 h-100 p-4 payment-gateway-btn" data-gateway="vnpay">
                                    <img src="https://vnpay.vn/s1/statics.vnpay.vn/2023/9/06ncktiwd6dc1694418196384.png" 
                                         alt="VNPay" style="max-height: 40px;" class="mb-2">
                                    <div class="fw-bold">VNPay</div>
                                    <small class="text-muted">Thanh toán qua VNPay</small>
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-danger w-100 h-100 p-4 payment-gateway-btn" data-gateway="momo">
                                    <img src="https://developers.momo.vn/v3/img/logo.png" 
                                         alt="Momo" style="max-height: 40px;" class="mb-2">
                                    <div class="fw-bold">Momo</div>
                                    <small class="text-muted">Thanh toán qua Momo</small>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <?php
            $avgRating = \App\Models\DanhGia::getAverageRating($bacSi->id);
            $totalReviews = \App\Models\DanhGia::getTotalReviews($bacSi->id);
            $reviews = \App\Models\DanhGia::where('bac_si_id', $bacSi->id)
                ->approved()
                ->with('user')
                ->latest()
                ->take(5)
                ->get();
        ?>

        <?php if($totalReviews > 0): ?>
            <div class="card mt-4 shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-star-fill text-warning"></i>
                        Đánh giá từ bệnh nhân
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3 text-center border-end">
                            <div class="display-4 fw-bold text-warning"><?php echo e(number_format($avgRating, 1)); ?></div>
                            <div class="mb-2">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <?php if($i <= round($avgRating)): ?>
                                        <i class="bi bi-star-fill text-warning"></i>
                                    <?php else: ?>
                                        <i class="bi bi-star text-muted"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                            <div class="text-muted"><?php echo e($totalReviews); ?> đánh giá</div>
                        </div>
                        <div class="col-md-9">
                            <?php
                                $distribution = \App\Models\DanhGia::getRatingDistribution($bacSi->id);
                            ?>
                            <?php for($i = 5; $i >= 1; $i--): ?>
                                <?php
                                    $count = $distribution[$i] ?? 0;
                                    $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                                ?>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="me-2" style="width: 60px;"><?php echo e($i); ?> sao</span>
                                    <div class="progress flex-grow-1" style="height: 20px;">
                                        <div class="progress-bar bg-warning" role="progressbar"
                                             style="width: <?php echo e($percentage); ?>%"
                                             aria-valuenow="<?php echo e($percentage); ?>" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                    <span class="ms-2 text-muted" style="width: 50px;"><?php echo e($count); ?></span>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <hr>

                    <h6 class="fw-bold mb-3">Đánh giá gần đây</h6>
                    <?php $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <strong><?php echo e($review->user->name); ?></strong>
                                    <div class="text-warning">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <?php if($i <= $review->rating): ?>
                                                <i class="bi bi-star-fill"></i>
                                            <?php else: ?>
                                                <i class="bi bi-star"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <small class="text-muted"><?php echo e($review->created_at->diffForHumans()); ?></small>
                            </div>
                            <p class="mb-0"><?php echo e($review->noi_dung); ?></p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
        let currentFilter = 'all';

        // Update slot counts
        function updateCounts() {
            const counts = {
                empty: 0,
                partial: 0,
                my: 0,
                full: 0
            };

            document.querySelectorAll('.day-slots:not([style*="display: none"]) .slot-card').forEach(slot => {
                const filter = slot.getAttribute('data-filter');
                if (counts.hasOwnProperty(filter)) {
                    counts[filter]++;
                }
            });

            document.getElementById('count-empty').textContent = `(${counts.empty})`;
            document.getElementById('count-partial').textContent = `(${counts.partial})`;
            document.getElementById('count-my').textContent = `(${counts.my})`;
            document.getElementById('count-full').textContent = `(${counts.full})`;
        }

        // Filter slots by status
        function filterSlots(filter) {
            currentFilter = filter;
            const slots = document.querySelectorAll('.day-slots:not([style*="display: none"]) .slot-card');
            
            // Update slots visibility
            slots.forEach(slot => {
                const slotFilter = slot.getAttribute('data-filter');
                if (filter === 'all' || slotFilter === filter) {
                    slot.style.display = 'block';
                } else {
                    slot.style.display = 'none';
                }
            });

            // Update active filter tab
            document.querySelectorAll('.filter-tab').forEach(tab => {
                if (tab.getAttribute('data-filter') === filter) {
                    tab.classList.add('active');
                } else {
                    tab.classList.remove('active');
                }
            });
        }

        // Select day
        function selectDay(dateStr) {
            // Update day cards
            document.querySelectorAll('.day-card').forEach(card => {
                card.classList.remove('active');
            });
            event.target.closest('.day-card').classList.add('active');

            // Show selected day slots
            document.querySelectorAll('.day-slots').forEach(daySlot => {
                if (daySlot.getAttribute('data-date') === dateStr) {
                    daySlot.style.display = 'block';
                } else {
                    daySlot.style.display = 'none';
                }
            });

            // Reapply current filter
            if (currentFilter !== 'all') {
                filterSlots(currentFilter);
            }

            // Update counts
            updateCounts();
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCounts();
        });

        function bookSlot(date, time) {
            // Điền thông tin vào modal
            document.getElementById('modal_ngay_hen').value = date;
            document.getElementById('modal_thoi_gian_hen').value = time;
            
            // Format hiển thị
            const dateObj = new Date(date);
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('display_ngay_hen').textContent = dateObj.toLocaleDateString('vi-VN', options);
            document.getElementById('display_thoi_gian_hen').textContent = time;
            
            // Reset form
            document.getElementById('bookingForm').reset();
            document.getElementById('modal_ngay_hen').value = date;
            document.getElementById('modal_thoi_gian_hen').value = time;
            document.getElementById('service_info').style.display = 'none';
            document.getElementById('payment_gateway').value = '';
            
            // Hiển thị modal
            const modal = new bootstrap.Modal(document.getElementById('bookingModal'));
            modal.show();
        }

        // Xử lý khi chọn dịch vụ
        document.addEventListener('DOMContentLoaded', function() {
            const dichVuSelect = document.getElementById('dich_vu_select');
            const serviceInfo = document.getElementById('service_info');
            const servicePrice = document.getElementById('service_price');
            const serviceDuration = document.getElementById('service_duration');
            const bookingForm = document.getElementById('bookingForm');
            const submitBtn = document.getElementById('submit_booking');
            let paymentGatewayModal = null;

            // Khởi tạo modal sau khi DOM load xong
            const paymentGatewayModalEl = document.getElementById('paymentGatewayModal');
            if (paymentGatewayModalEl) {
                paymentGatewayModal = new bootstrap.Modal(paymentGatewayModalEl);
            }

            // Hiển thị thông tin dịch vụ
            dichVuSelect.addEventListener('change', function() {
                const selected = this.options[this.selectedIndex];
                if (this.value) {
                    const price = selected.getAttribute('data-price');
                    const duration = selected.getAttribute('data-duration');
                    
                    servicePrice.textContent = new Intl.NumberFormat('vi-VN', {
                        style: 'currency',
                        currency: 'VND'
                    }).format(price);
                    serviceDuration.textContent = duration + ' phút';
                    serviceInfo.style.display = 'block';
                } else {
                    serviceInfo.style.display = 'none';
                }
            });

            // Xử lý nút xác nhận
            submitBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                const dichVuId = dichVuSelect.value;
                if (!dichVuId) {
                    alert('Vui lòng chọn dịch vụ!');
                    return;
                }

                const paymentMethodEl = document.querySelector('input[name="payment_method"]:checked');
                if (!paymentMethodEl) {
                    alert('Vui lòng chọn phương thức thanh toán!');
                    return;
                }
                
                const paymentMethod = paymentMethodEl.value;
                
                // Nếu chọn chuyển khoản, hiển thị popup chọn cổng
                if (paymentMethod === 'chuyen_khoan') {
                    if (paymentGatewayModal) {
                        paymentGatewayModal.show();
                    } else {
                        alert('Không thể hiển thị popup thanh toán. Vui lòng thử lại!');
                    }
                } else {
                    // Thanh toán tiền mặt, submit trực tiếp
                    submitForm();
                }
            });

            // Xử lý chọn cổng thanh toán
            document.querySelectorAll('.payment-gateway-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const gateway = this.getAttribute('data-gateway');
                    const gatewayInput = document.getElementById('payment_gateway');
                    
                    if (gatewayInput) {
                        gatewayInput.value = gateway;
                        
                        if (paymentGatewayModal) {
                            paymentGatewayModal.hide();
                        }
                        
                        // Submit form sau khi chọn cổng
                        setTimeout(() => submitForm(), 300); // Đợi modal đóng xong
                    } else {
                        alert('Lỗi: Không tìm thấy trường payment_gateway!');
                    }
                });
            });

            function submitForm() {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
                
                const formData = new FormData(bookingForm);
                
                // Debug: log form data
                console.log('Submitting form with data:');
                for (let [key, value] of formData.entries()) {
                    console.log(key + ': ' + value);
                }
                
                fetch(bookingForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    
                    if (data.success) {
                        // Nếu có payment_gateway (vnpay/momo), tạo form POST đến gateway
                        if (data.payment_gateway && data.hoa_don_id) {
                            console.log('Creating payment form for gateway:', data.payment_gateway);
                            
                            const gatewayRoute = data.payment_gateway === 'vnpay' 
                                ? '<?php echo e(route("vnpay.create")); ?>' 
                                : '<?php echo e(route("momo.create")); ?>';
                            
                            // Tạo form POST động
                            const paymentForm = document.createElement('form');
                            paymentForm.method = 'POST';
                            paymentForm.action = gatewayRoute;
                            
                            // CSRF token
                            const csrfInput = document.createElement('input');
                            csrfInput.type = 'hidden';
                            csrfInput.name = '_token';
                            csrfInput.value = '<?php echo e(csrf_token()); ?>';
                            paymentForm.appendChild(csrfInput);
                            
                            // hoa_don_id
                            const hoaDonInput = document.createElement('input');
                            hoaDonInput.type = 'hidden';
                            hoaDonInput.name = 'hoa_don_id';
                            hoaDonInput.value = data.hoa_don_id;
                            paymentForm.appendChild(hoaDonInput);
                            
                            // amount
                            const amountInput = document.createElement('input');
                            amountInput.type = 'hidden';
                            amountInput.name = 'amount';
                            amountInput.value = data.amount;
                            paymentForm.appendChild(amountInput);
                            
                            // Submit form
                            document.body.appendChild(paymentForm);
                            console.log('Submitting payment form to:', gatewayRoute);
                            paymentForm.submit();
                        } else if (data.payment_url) {
                            // Chưa chọn gateway, chuyển đến trang payment để chọn
                            console.log('Redirecting to payment selection page:', data.payment_url);
                            window.location.href = data.payment_url;
                        } else if (data.redirect_url) {
                            // Thanh toán tiền mặt, reload trang để hiển thị slot đã đặt với màu mới
                            console.log('Booking successful, reloading page');
                            
                            // Đóng modal trước
                            const bookingModalEl = document.getElementById('bookingModal');
                            const bookingModalInstance = bootstrap.Modal.getInstance(bookingModalEl);
                            if (bookingModalInstance) {
                                bookingModalInstance.hide();
                            }
                            
                            // Hiển thị thông báo thành công
                            setTimeout(() => {
                                alert(data.message || 'Đặt lịch thành công!');
                                window.location.reload();
                            }, 300);
                        } else {
                            // Thành công nhưng không có redirect
                            alert(data.message || 'Đặt lịch thành công!');
                            window.location.reload();
                        }
                    } else {
                        alert(data.message || 'Có lỗi xảy ra, vui lòng thử lại!');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-check"></i> Xác nhận đặt lịch';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra, vui lòng thử lại!');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-check"></i> Xác nhận đặt lịch';
                });
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\WORKING\DACN\DACN\resources\views/public/bacsi/weekly-schedule.blade.php ENDPATH**/ ?>