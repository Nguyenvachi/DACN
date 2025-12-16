<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Lịch hẹn đã được xác nhận</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f6f6f6;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .email-container {
            max-width: 600px;
            background: #ffffff;
            margin: 20px auto;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: #16A34A;
            color: white;
            padding: 20px 24px;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
        }

        .content {
            padding: 24px;
            background: #ffffff;
            line-height: 1.6;
            font-size: 15px;
        }

        .info-box {
            margin-top: 15px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 16px;
        }

        .info-row {
            margin-bottom: 8px;
            font-size: 14px;
        }

        .label {
            font-weight: bold;
            display: inline-block;
            width: 140px;
        }

        .footer {
            background: #f3f4f6;
            text-align: center;
            font-size: 13px;
            padding: 14px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="email-container">

        
        <div class="header">
            ✓ Lịch hẹn đã được xác nhận
        </div>

        
        <div class="content">

            <p>Xin chào <strong><?php echo e($lichHen->user->name ?? 'Bệnh nhân'); ?></strong>,</p>

            <p>Lịch hẹn của bạn đã được xác nhận thành công.</p>

            
            <div class="info-box">
                <div class="info-row">
                    <span class="label">Mã lịch hẹn:</span> #<?php echo e($lichHen->id); ?>

                </div>

                <div class="info-row">
                    <span class="label">Bác sĩ:</span> <?php echo e($lichHen->bacSi->ho_ten ?? '-'); ?>

                </div>

                <div class="info-row">
                    <span class="label">Dịch vụ:</span> <?php echo e($lichHen->dichVu->ten_dich_vu ?? '-'); ?>

                </div>

                <div class="info-row">
                    <span class="label">Ngày hẹn:</span>
                    <?php echo e(\Carbon\Carbon::parse($lichHen->ngay_hen)->format('d/m/Y')); ?>

                </div>

                <div class="info-row">
                    <span class="label">Giờ hẹn:</span> <?php echo e($lichHen->thoi_gian_hen); ?>

                </div>

                <div class="info-row">
                    <span class="label">Trạng thái:</span>
                    <strong style="color: #16A34A;"><?php echo e($lichHen->trang_thai); ?></strong>
                </div>
            </div>

            <p style="margin-top: 20px;">
                Vui lòng đến đúng giờ và mang theo giấy tờ cần thiết khi đến khám.
            </p>

            <p>Trân trọng,<br>
                <strong><?php echo e(config('app.name')); ?></strong>
            </p>

        </div>

        
        <div class="footer">
            Đây là email tự động. Vui lòng không trả lời email này.
        </div>

    </div>
</body>

</html>
<?php /**PATH F:\WORKING\DACN\DACN\resources\views/emails/lich_hen_xac_nhan.blade.php ENDPATH**/ ?>