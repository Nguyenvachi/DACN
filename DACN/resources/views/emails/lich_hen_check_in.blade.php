<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Lịch hẹn - Check-in thành công</title>

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
            background: #0891B2;
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
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .info-label {
            font-weight: bold;
            color: #374151;
        }

        .info-value {
            color: #6b7280;
        }

        .footer {
            background: #f9fafb;
            padding: 16px 24px;
            text-align: center;
            font-size: 13px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }

        .btn {
            display: inline-block;
            background: #0891B2;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <i class="fas fa-check-circle"></i> Check-in thành công!
        </div>

        <div class="content">
            <p>Kính chào <strong>{{ $lichHen->user->ho_ten ?? 'Bệnh nhân' }}</strong>,</p>

            <p>Chúng tôi xin thông báo rằng bạn đã check-in thành công tại phòng khám. Vui lòng chờ đợi để được khám.</p>

            <div class="info-box">
                <h4 style="margin-top: 0; color: #0891B2;">Chi tiết lịch hẹn</h4>
                <div class="info-row">
                    <span class="info-label">Mã lịch hẹn:</span>
                    <span class="info-value">#{{ $lichHen->id }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Ngày hẹn:</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($lichHen->ngay_hen)->format('d/m/Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Thời gian:</span>
                    <span class="info-value">{{ $lichHen->thoi_gian_hen }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Dịch vụ:</span>
                    <span class="info-value">{{ $lichHen->dichVu->ten_dich_vu ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Bác sĩ:</span>
                    <span class="info-value">{{ $lichHen->bacSi->ho_ten ?? 'N/A' }}</span>
                </div>
                @if($lichHen->bacSi && $lichHen->bacSi->chuyenKhoas->count() > 0)
                <div class="info-row">
                    <span class="info-label">Chuyên khoa:</span>
                    <span class="info-value">{{ $lichHen->bacSi->chuyenKhoas->pluck('ten_chuyen_khoa')->join(', ') }}</span>
                </div>
                @endif
            </div>

            <p>Vui lòng đến phòng khám đúng giờ để được phục vụ tốt nhất.</p>

            <p>Trân trọng,<br>
            Phòng khám Sản-Phụ Khoa</p>
        </div>

        <div class="footer">
            <p>Email này được gửi tự động từ hệ thống phòng khám. Vui lòng không trả lời email này.</p>
        </div>
    </div>
</body>

</html>
