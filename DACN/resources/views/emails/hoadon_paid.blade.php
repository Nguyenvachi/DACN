<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f6f6f6;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: #1D4ED8;
            color: white;
            padding: 16px 24px;
            font-size: 18px;
            font-weight: bold;
        }

        .content {
            padding: 24px;
            color: #333;
            line-height: 1.5;
            font-size: 15px;
        }

        .details-box {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 16px;
            border-radius: 6px;
            margin-top: 12px;
        }

        .footer {
            padding: 16px 24px;
            font-size: 13px;
            color: #666;
            text-align: center;
            background: #f3f4f6;
        }

        .mt-2 {
            margin-top: 8px;
        }

        .bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="email-container">

        {{-- Header --}}
        <div class="header">
            Xác nhận thanh toán hóa đơn
        </div>

        {{-- Body --}}
        <div class="content">
            <p>Chào {{ optional($hoaDon->user)->name ?? 'bạn' }},</p>

            <p>Thanh toán cho hóa đơn <strong>#{{ $hoaDon->id }}</strong> đã được xác nhận thành công.</p>

            {{-- Details --}}
            <div class="details-box">
                <p class="bold">Thông tin hóa đơn:</p>
                <p><strong>Số tiền:</strong> {{ number_format($hoaDon->tong_tien, 0, ',', '.') }} đ</p>
                <p><strong>Phương thức:</strong> {{ $hoaDon->phuong_thuc }}</p>
                <p><strong>Thời gian:</strong> {{ now()->format('d/m/Y H:i') }}</p>
            </div>

            <p class="mt-2">Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi.</p>

            <p>Trân trọng,<br>
                <strong>{{ config('app.name') }}</strong>
            </p>
        </div>

        {{-- Footer --}}
        <div class="footer">
            Đây là email tự động. Vui lòng không trả lời trực tiếp email này.
        </div>

    </div>
</body>

</html>
