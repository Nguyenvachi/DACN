<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Bác sĩ - <?php echo e(config('app.name')); ?></title>

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    
    <link rel="stylesheet" href="<?php echo e(asset('css/vietcare.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/doctor-layout.css')); ?>">

    <?php echo $__env->yieldPushContent('meta'); ?>
    <?php echo $__env->yieldPushContent('styles'); ?>

    <!-- ADDED: Unified design system -->
    <link rel="stylesheet" href="<?php echo e(asset('css/design-system-unified.css')); ?>">
</head>

<body>

    
    <nav class="doctor-sidebar">
        
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <i class="fas fa-heartbeat"></i>
            </div>
            <div>
                <div class="sidebar-brand">VietCare</div>
                <small style="opacity: 0.9; font-size: 0.8rem;">Bác Sĩ</small>
            </div>
        </div>

        <ul>
            
            <li>
                <a href="<?php echo e(route('doctor.dashboard')); ?>">
                    <i class="fas fa-chart-line"></i>
                    <span>Tổng quan</span>
                </a>
            </li>

            
            <li>
                <a href="<?php echo e(route('doctor.lichhen.pending')); ?>">
                    <i class="fas fa-clock"></i>
                    <span>Lịch chờ xác nhận</span>
                    <?php
                        $pendingCount = auth()->user()->bacSi
                            ? \App\Models\LichHen::where('bac_si_id', auth()->user()->bacSi->id)
                                ->where('trang_thai', \App\Models\LichHen::STATUS_PENDING_VN)
                                ->count()
                            : 0;
                    ?>
                    <?php if($pendingCount > 0): ?>
                        <span class="sidebar-badge sidebar-badge-pulse"><?php echo e($pendingCount); ?></span>
                    <?php endif; ?>
                </a>
            </li>

            
            <li>
                <a href="<?php echo e(route('doctor.queue.index')); ?>">
                    <i class="fas fa-users"></i>
                    <span>Hàng đợi khám</span>
                    <?php
                        $queueCount = auth()->user()->bacSi
                            ? \App\Models\LichHen::where('bac_si_id', auth()->user()->bacSi->id)
                                ->whereIn('trang_thai', [\App\Models\LichHen::STATUS_CHECKED_IN_VN, \App\Models\LichHen::STATUS_IN_PROGRESS_VN])
                                ->whereDate('ngay_hen', today())
                                ->count()
                            : 0;
                        
                        $inProgressCount = auth()->user()->bacSi
                            ? \App\Models\LichHen::where('bac_si_id', auth()->user()->bacSi->id)
                                ->where('trang_thai', \App\Models\LichHen::STATUS_IN_PROGRESS_VN)
                                ->whereDate('ngay_hen', today())
                                ->count()
                            : 0;
                    ?>
                    <?php if($inProgressCount > 0): ?>
                        <span class="sidebar-badge" style="background: #10b981; animation: pulse 2s infinite;"><?php echo e($inProgressCount); ?></span>
                    <?php elseif($queueCount > 0): ?>
                        <span class="sidebar-badge"><?php echo e($queueCount); ?></span>
                    <?php endif; ?>
                </a>
            </li>

            
            <li>
                <a href="<?php echo e(route('doctor.benhan.index')); ?>">
                    <i class="fas fa-folder-open"></i>
                    <span>Bệnh án</span>
                    <?php
                        $todayRecordsCount = auth()->user()->bacSi
                            ? \App\Models\BenhAn::where('bac_si_id', auth()->user()->bacSi->id)
                                ->whereDate('created_at', today())
                                ->count()
                            : 0;
                    ?>
                    <?php if($todayRecordsCount > 0): ?>
                        <span class="sidebar-badge"><?php echo e($todayRecordsCount); ?></span>
                    <?php endif; ?>
                </a>
            </li>

            
            

            
            <li>
                <a href="<?php echo e(route('doctor.theo-doi-thai-ky.index')); ?>">
                    <i class="fas fa-heart"></i>
                    <span>Theo dõi thai kỳ</span>
                </a>
            </li>

            
            <li>
                <a href="<?php echo e(route('doctor.lich-tai-kham.index')); ?>">
                    <i class="fas fa-calendar-check"></i>
                    <span>Lịch tái khám</span>
                </a>
            </li>

            
            <li>
                <a href="<?php echo e(route('doctor.calendar.index')); ?>">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Lịch làm việc</span>
                </a>
            </li>

            
            <li>
                <a href="<?php echo e(route('doctor.chat.index')); ?>">
                    <i class="fas fa-comments"></i>
                    <span>Tin nhắn</span>
                    <?php
                        $unreadMessages = 0; // TODO: implement unread count
                    ?>
                    <?php if($unreadMessages > 0): ?>
                        <span class="sidebar-badge"><?php echo e($unreadMessages); ?></span>
                    <?php endif; ?>
                </a>
            </li>

            <hr style="opacity: 0.1; margin: 1rem 0;">

            
            <li>
                <a href="<?php echo e(route('profile.edit')); ?>">
                    <i class="fas fa-user-cog"></i>
                    <span>Hồ sơ</span>
                </a>
            </li>

            
            <li>
                <a href="<?php echo e(route('home')); ?>">
                    <i class="fas fa-home"></i>
                    <span>Trang chủ</span>
                </a>
            </li>

            
            <li>
                <form method="POST" action="<?php echo e(route('logout')); ?>" class="m-0">
                    <?php echo csrf_field(); ?>
                    <button type="submit">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Đăng xuất</span>
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    
    <?php if(View::hasSection('header')): ?>
        <header class="bg-white shadow-sm mb-3" style="margin-left: 260px;">
            <div class="container-fluid py-3 px-4">
                <?php echo $__env->yieldContent('header'); ?>
            </div>
        </header>
    <?php endif; ?>

    
    <main class="doctor-main">
        <div class="container-fluid">
            
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                    <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </main>

    
    <?php
        $hasInProgress = auth()->user()->bacSi
            ? \App\Models\LichHen::where('bac_si_id', auth()->user()->bacSi->id)
                ->where('trang_thai', \App\Models\LichHen::STATUS_IN_PROGRESS_VN)
                ->whereDate('ngay_hen', today())
                ->with(['user', 'benhAn'])
                ->first()
            : null;
    ?>

    <?php if($hasInProgress && !request()->is('doctor/queue*') && !request()->is('doctor/benhan/*/edit')): ?>
    <div class="floating-quick-access">
        <button class="btn btn-success btn-lg rounded-circle shadow-lg" 
                style="width: 65px; height: 65px; position: relative;"
                data-bs-toggle="tooltip" 
                data-bs-placement="left"
                title="Bạn đang khám <?php echo e($hasInProgress->user->name); ?>"
                onclick="window.location.href='<?php echo e($hasInProgress->benhAn ? route('doctor.benhan.edit', $hasInProgress->benhAn->id) : route('doctor.queue.index')); ?>'">
            <i class="fas fa-stethoscope fa-lg"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" 
                  style="font-size: 0.65rem; animation: pulse 2s infinite;">
                1
            </span>
        </button>
        <small class="d-block text-center mt-2 fw-semibold" style="color: #065f46;">
            Đang khám
        </small>
    </div>

    <style>
        .floating-quick-access {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            text-align: center;
        }

        .floating-quick-access button {
            background: linear-gradient(135deg, #10b981, #059669) !important;
            border: 3px solid white;
            box-shadow: 0 4px 20px rgba(16, 185, 129, 0.4) !important;
            transition: all 0.3s ease;
        }

        .floating-quick-access button:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 30px rgba(16, 185, 129, 0.6) !important;
        }
    </style>
    <?php endif; ?>

    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <?php echo $__env->yieldPushContent('scripts'); ?>

    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.doctor-sidebar');
            if (!sidebar) return;

            const links = sidebar.querySelectorAll('a[href]');
            const normalize = href => {
                const a = document.createElement('a');
                a.href = href;
                return a.pathname.replace(/\/$/, '');
            };
            const current = normalize(window.location.href);

            links.forEach(link => {
                const path = normalize(link.href);
                if (current === path || (current.startsWith(path + '/') && path !== '/')) {
                    link.classList.add('active');
                }
            });

            // Mobile sidebar toggle (optional)
            const toggleBtn = document.getElementById('sidebarToggle');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }
        });

        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>

    

</body>

</html>
<?php /**PATH F:\WORKING\DACN\DACN\resources\views/layouts/doctor.blade.php ENDPATH**/ ?>