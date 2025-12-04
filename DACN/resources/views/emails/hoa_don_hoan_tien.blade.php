<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo hoàn tiền</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .info-box {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: bold;
            color: #666;
        }
        .value {
            color: #333;
        }
        .amount {
            font-size: 24px;
            color: #28a745;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .success-icon {
            font-size: 48px;
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎉 THÔNG BÁO HOÀN TIỀN</h1>
    </div>

    <div class="content">
        <div class="success-icon">✅</div>

        <p>Xin chào <strong>{{ $hoaDon->user->name ?? 'Quý khách' }}</strong>,</p>

        <p>Chúng tôi xin thông báo rằng yêu cầu hoàn tiền của quý khách đã được xử lý thành công.</p>

        <div class="info-box">
            <h3 style="margin-top: 0; color: #667eea;">Thông tin hoàn tiền</h3>

            <div class="info-row">
                <span class="label">Mã hóa đơn:</span>
                <span class="value">{{ $hoaDon->ma_hoa_don }}</span>
            </div>

            <div class="info-row">
                <span class="label">Số tiền hoàn:</span>
                <span class="value" style="color: #28a745; font-weight: bold;">{{ number_format($hoanTien->so_tien, 0, ',', '.') }} VNĐ</span>
            </div>

            <div class="info-row">
                <span class="label">Lý do hoàn tiền:</span>
                <span class="value">{{ $hoanTien->ly_do }}</span>
            </div>

            <div class="info-row">
                <span class="label">Phương thức:</span>
                <span class="value">
                    @switch($hoanTien->provider)
                        @case('tien_mat')
                            Tiền mặt
                            @break
                        @case('chuyen_khoan')
                            Chuyển khoản
                            @break
                        @case('hoan_cong')
                            Hoàn cổng thanh toán
                            @break
                        @default
                            {{ $hoanTien->provider }}
                    @endswitch
                </span>
            </div>

            <div class="info-row">
                <span class="label">Trạng thái:</span>
                <span class="value" style="color: #28a745;">✓ {{ $hoanTien->trang_thai }}</span>
            </div>

            <div class="info-row">
                <span class="label">Ngày hoàn:</span>
                <span class="value">{{ $hoanTien->created_at->format('d/m/Y H:i:s') }}</span>
            </div>
        </div>

        <div class="info-box" style="border-left-color: #ffc107;">
            <h4 style="margin-top: 0; color: #ffc107;">Thông tin hóa đơn</h4>

            <div class="info-row">
                <span class="label">Tổng tiền ban đầu:</span>
                <span class="value">{{ number_format($hoaDon->tong_tien, 0, ',', '.') }} VNĐ</span>
            </div>

            <div class="info-row">
                <span class="label">Đã thanh toán:</span>
                <span class="value">{{ number_format($hoaDon->so_tien_da_thanh_toan, 0, ',', '.') }} VNĐ</span>
            </div>

            <div class="info-row">
                <span class="label">Đã hoàn:</span>
                <span class="value">{{ number_format($hoaDon->so_tien_da_hoan, 0, ',', '.') }} VNĐ</span>
            </div>

            <div class="info-row">
                <span class="label">Trạng thái hóa đơn:</span>
                <span class="value" style="font-weight: bold;">{{ $hoaDon->trang_thai }}</span>
            </div>
        </div>

        <p style="margin-top: 30px;">
            @if($hoanTien->provider === 'chuyen_khoan')
                Số tiền sẽ được chuyển về tài khoản ngân hàng của quý khách trong vòng <strong>3-5 ngày làm việc</strong>.
            @elseif($hoanTien->provider === 'hoan_cong')
                Số tiền sẽ được hoàn về cổng thanh toán gốc trong vòng <strong>7-14 ngày làm việc</strong>.
            @else
                Vui lòng liên hệ phòng khám để nhận tiền mặt hoàn lại.
            @endif
        </p>

        <p>Nếu có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi qua:</p>
        <ul>
            <li>Email: {{ config('mail.from.address') }}</li>
            <li>Hotline: {{ config('app.phone', '1900xxxx') }}</li>
        </ul>

        <div style="text-align: center;">
            <a href="{{ route('login') }}" class="button">Xem chi tiết hóa đơn</a>
        </div>
    </div>

    <div class="footer">
        <p><strong>{{ config('app.name') }}</strong></p>
        <p>Email này được gửi tự động, vui lòng không trả lời trực tiếp.</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>
