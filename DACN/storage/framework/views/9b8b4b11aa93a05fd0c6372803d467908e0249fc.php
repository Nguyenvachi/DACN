<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Quản trị Phòng khám Sản Phụ Khoa</title>

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

    <?php echo $__env->yieldPushContent('meta'); ?>
    <?php echo $__env->yieldPushContent('styles'); ?>

    <!-- ADDED: Unified design system (keeps and extends existing styles) -->
    <link rel="stylesheet" href="<?php echo e(asset('css/design-system-unified.css')); ?>">

    <style>
        html {
            scroll-behavior: auto;
        }

        body {
            background: #f5f6fa;
            font-family: "Segoe UI", sans-serif;
        }

        .admin-sidebar {
            width: 250px;
            height: 100vh;
            background: #ffffff;
            border-right: 1px solid #ddd;
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
        }

        /* Scroll đẹp */
        .admin-sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .admin-sidebar::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 5px;
        }

        .admin-sidebar::-webkit-scrollbar-thumb:hover {
            background: #999;
        }

        .admin-sidebar ul {
            list-style: none;
            padding: 0;
        }

        .admin-sidebar li {
            margin-bottom: 10px;
        }

        .admin-sidebar a {
            display: block;
            padding: 10px 12px;
            border-radius: 6px;
            text-decoration: none;
            color: #333;
            font-weight: 500;
        }

        .admin-sidebar a:hover {
            background: #e9ecef;
        }

        .admin-sidebar a.active {
            background: #007bff;
            color: white;
        }

        main {
            margin-left: 270px;
            padding: 20px;
        }
    </style>
</head>

<body>

    <nav class="admin-sidebar">
        <h5 class="fw-bold mb-3"><i class="fas fa-clinic-medical me-2"></i>Quản trị</h5>

        <ul>
            <?php if(auth()->user()->role === 'staff'): ?>
                <li><a href="<?php echo e(route('staff.dashboard')); ?>"><i class="fas fa-chart-line me-2"></i>Dashboard</a></li>
            <?php else: ?>
                <li><a href="<?php echo e(route('admin.dashboard')); ?>"><i class="fas fa-chart-line me-2"></i>Dashboard</a></li>
            <?php endif; ?>
            <li>
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button class="btn btn-link p-0" type="submit">🔒 Đăng xuất</button>
                </form>
            </li>
            <hr>

            <?php if(auth()->user()->role === 'admin'): ?>
            <li><strong>Quản lý cơ bản</strong></li>
            <li><a href="<?php echo e(route('admin.bac-si.index')); ?>">👨‍⚕️ Quản lý Bác sĩ</a></li>
            <li><a href="<?php echo e(route('admin.nhanvien.index')); ?>">👥 Nhân viên</a></li>
            <li><a href="<?php echo e(route('admin.dich-vu.index')); ?>">🏥 Dịch vụ</a></li>
            <li><a href="<?php echo e(route('admin.chuyenkhoa.index')); ?>">🔬 Chuyên khoa</a></li>
            <li><a href="<?php echo e(route('admin.loaiphong.index')); ?>">🏢 Loại phòng</a></li>
            <li><a href="<?php echo e(route('admin.phong.index')); ?>">🚪 Phòng khám</a></li>
            <li><a href="<?php echo e(route('admin.phong.diagram')); ?>">🗺️ Sơ đồ phòng</a></li>

            <hr>
            <?php endif; ?>

            <li><strong>Lịch & Hẹn</strong></li>
            <li><a href="<?php echo e(route('admin.lichhen.index')); ?>">📅 Lịch hẹn</a></li>
            <?php if(auth()->user()->role === 'admin'): ?>
            <li><a href="<?php echo e(route('admin.calendar.index')); ?>">📆 Calendar</a></li>
            <li><a href="<?php echo e(route('admin.danhgia.index')); ?>">⭐ Đánh giá</a></li>
            <li><a href="<?php echo e(route('admin.chat.index')); ?>">💬 Chat tư vấn</a></li>
            <?php endif; ?>

            <hr>

            <li><strong>Bệnh án & Hóa đơn</strong></li>
            <li><a href="<?php echo e(route('admin.benhan.index')); ?>">📋 Bệnh án</a></li>
            
            <?php if(auth()->user()->role === 'staff'): ?>
                <li><a href="<?php echo e(route('staff.hoadon.index')); ?>">💰 Hóa đơn</a></li>
            <?php else: ?>
                <li><a href="<?php echo e(route('admin.hoadon.index')); ?>">💰 Hóa đơn (Xem)</a></li>
            <?php endif; ?>

            <?php if(auth()->user()->role === 'admin'): ?>
            <hr>

            <li><strong>Quản lý kho</strong></li>
            <li><a href="<?php echo e(route('admin.thuoc.index')); ?>">💊 Thuốc</a></li>
            <li><a href="<?php echo e(route('admin.coupons.index')); ?>">🎫 Mã giảm giá</a></li>
            <li><a href="<?php echo e(route('admin.kho.index')); ?>">📦 Kho</a></li>
            <li><a href="<?php echo e(route('admin.kho.nhap.form')); ?>">📥 Nhập kho</a></li>
            <li><a href="<?php echo e(route('admin.kho.xuat.form')); ?>">📤 Xuất kho</a></li>

            <hr>

            <li><strong>Dịch vụ Y tế</strong></li>
            <li><a href="<?php echo e(route('admin.sieu-am.index')); ?>">🔊 Loại siêu âm</a></li>
            <li><a href="<?php echo e(route('admin.xet-nghiem.index')); ?>">🧪 Xét nghiệm</a></li>
            <li><a href="<?php echo e(route('admin.thu-thuat.index')); ?>">🔧 Loại thủ thuật</a></li>
            <li><a href="<?php echo e(route('admin.kho.bao_cao')); ?>">📊 Báo cáo</a></li>
            <li><a href="<?php echo e(route('admin.ncc.index')); ?>">🏢 Nhà cung cấp</a></li>

            <hr>

            <li><strong>CMS</strong></li>
            <li><a href="<?php echo e(route('admin.baiviet.index')); ?>">📝 Bài viết</a></li>
            <li><a href="<?php echo e(route('admin.danhmuc.index')); ?>">📂 Danh mục</a></li>
            <li><a href="<?php echo e(route('admin.tag.index')); ?>">🏷️ Thẻ</a></li>
            <li><a href="<?php echo e(route('admin.media.index')); ?>">🖼️ Media Library</a></li>

            <hr>

            <li><strong>Quản lý người dùng</strong></li>
            <li><a href="<?php echo e(route('admin.users.index')); ?>">👤 Users</a></li>
            
            
            

            <hr>

            <li><strong>Tools</strong></li>
            <li><a href="<?php echo e(route('admin.tools.test-mail')); ?>">✉️ Test gửi mail</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <main>

        
        <?php if (! empty(trim($__env->yieldContent('header')))): ?>
            <div class="mb-4">
                <?php echo $__env->yieldContent('header'); ?>
            </div>
        <?php endif; ?>

        <div class="container-fluid">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </main>

    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <?php echo $__env->yieldPushContent('scripts'); ?>

    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.admin-sidebar');
            if (!sidebar) return;

            // Khôi phục vị trí scroll từ sessionStorage
            const savedScrollPos = sessionStorage.getItem('adminSidebarScroll');
            if (savedScrollPos !== null) {
                sidebar.scrollTop = parseInt(savedScrollPos);
            }

            // Lưu vị trí scroll khi chuyển trang
            window.addEventListener('beforeunload', function() {
                sessionStorage.setItem('adminSidebarScroll', sidebar.scrollTop);
            });

            const links = sidebar.querySelectorAll('a');

            const normalize = href => {
                const a = document.createElement('a');
                a.href = href;
                return a.pathname.replace(/\/$/, '');
            };

            const current = normalize(window.location.href);

            links.forEach(link => {
                const path = normalize(link.href);
                if (current === path || current.startsWith(path + '/')) {
                    link.classList.add('active');
                }
            });
        });
    </script>

    

</body>

</html>
<?php /**PATH F:\WORKING\DACN\DACN\resources\views/layouts/admin.blade.php ENDPATH**/ ?>