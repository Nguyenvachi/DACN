<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 800px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 20px; border-radius: 8px 8px 0 0; }
        .content { background: #f9f9f9; padding: 20px; border: 1px solid #ddd; }
        .alert { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #f093fb; color: white; font-weight: bold; }
        .low-stock { color: #d32f2f; font-weight: bold; }
        .footer { text-align: center; padding: 15px; color: #888; font-size: 12px; }
        .priority-high { background: #ffebee; }
        .priority-medium { background: #fff8e1; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>📉 Cảnh báo Tồn Kho Thấp</h2>
            <p>Ngày kiểm tra: {{ $ngayKiemTra->format('d/m/Y H:i') }}</p>
        </div>

        <div class="content">
            <p>Kính gửi <strong>{{ $user->name }}</strong>,</p>

            <div class="alert">
                <h3>⚠️ Phát hiện {{ $thuocGiamTon->count() }} loại thuốc có tồn kho thấp hơn ngưỡng</h3>
                <p>Cần lập kế hoạch nhập kho ngay để tránh gián đoạn hoạt động khám chữa bệnh.</p>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tên thuốc</th>
                        <th>Hàm lượng</th>
                        <th>Đơn vị</th>
                        <th>Tồn hiện tại</th>
                        <th>Tồn tối thiểu</th>
                        <th>Chênh lệch</th>
                        <th>Ưu tiên</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($thuocGiamTon as $index => $thuoc)
                        @php
                            $phanTramConLai = $thuoc->ton_toi_thieu > 0
                                ? ($thuoc->ton_hien_tai / $thuoc->ton_toi_thieu) * 100
                                : 0;
                            $rowClass = $phanTramConLai < 25 ? 'priority-high' : 'priority-medium';
                            $uuTien = $phanTramConLai < 25 ? 'CAO' : 'Trung bình';
                        @endphp
                        <tr class="{{ $rowClass }}">
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $thuoc->ten }}</strong></td>
                            <td>{{ $thuoc->ham_luong ?? 'N/A' }}</td>
                            <td>{{ $thuoc->don_vi }}</td>
                            <td class="low-stock">{{ number_format($thuoc->ton_hien_tai) }}</td>
                            <td>{{ number_format($thuoc->ton_toi_thieu) }}</td>
                            <td class="low-stock">-{{ number_format($thuoc->chenh_lech) }}</td>
                            <td><strong>{{ $uuTien }}</strong><br><small>({{ number_format($phanTramConLai, 1) }}%)</small></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top: 20px; padding: 15px; background: #e8f5e9; border-left: 4px solid #4caf50;">
                <h4>📋 Khuyến nghị:</h4>
                <ul>
                    <li><strong>Ưu tiên CAO (màu đỏ):</strong> Tồn kho < 25% ngưỡng → Nhập ngay trong 1-2 ngày</li>
                    <li><strong>Ưu tiên Trung bình (màu vàng):</strong> Tồn kho 25-100% ngưỡng → Lên kế hoạch nhập trong tuần</li>
                    <li><strong>Liên hệ nhà cung cấp:</strong> Đàm phán giá tốt cho đơn hàng lớn, kiểm tra thời gian giao hàng</li>
                    <li><strong>Kiểm tra dự báo:</strong> Xem xu hướng tiêu thụ để điều chỉnh tồn tối thiểu hợp lý</li>
                </ul>
            </div>

            <div style="margin-top: 20px; padding: 15px; background: #fff9e6; border-left: 4px solid #ff9800;">
                <h4>📊 Thống kê:</h4>
                <table style="width: auto; margin: 0;">
                    <tr>
                        <td><strong>Tổng số thuốc cảnh báo:</strong></td>
                        <td>{{ $thuocGiamTon->count() }} loại</td>
                    </tr>
                    <tr>
                        <td><strong>Ưu tiên CAO:</strong></td>
                        <td>{{ $thuocGiamTon->filter(fn($t) => ($t->ton_hien_tai / $t->ton_toi_thieu) * 100 < 25)->count() }} loại</td>
                    </tr>
                    <tr>
                        <td><strong>Tổng số lượng cần nhập:</strong></td>
                        <td>{{ number_format($thuocGiamTon->sum('chenh_lech')) }} đơn vị</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="footer">
            <p>Email tự động từ Hệ thống Quản lý Phòng khám</p>
            <p>Vui lòng không trả lời email này. Liên hệ IT nếu có thắc mắc.</p>
        </div>
    </div>
</body>
</html>
