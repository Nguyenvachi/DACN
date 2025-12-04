<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 800px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 8px 8px 0 0; }
        .content { background: #f9f9f9; padding: 20px; border: 1px solid #ddd; }
        .alert-danger { background: #fee; border-left: 4px solid #c00; padding: 15px; margin: 10px 0; }
        .alert-warning { background: #ffc; border-left: 4px solid #f90; padding: 15px; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #667eea; color: white; font-weight: bold; }
        .expired { color: #c00; font-weight: bold; }
        .warning { color: #f90; font-weight: bold; }
        .footer { text-align: center; padding: 15px; color: #888; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>⚠️ Cảnh báo Thuốc Hết Hạn</h2>
            <p>Ngày kiểm tra: {{ $ngayKiemTra->format('d/m/Y H:i') }}</p>
        </div>

        <div class="content">
            <p>Kính gửi <strong>{{ $user->name }}</strong>,</p>

            @if($thuocDaHetHan->isNotEmpty())
                <div class="alert-danger">
                    <h3>🚨 Thuốc ĐÃ HẾT HẠN ({{ $thuocDaHetHan->count() }} lô)</h3>
                    <p><strong>Cần xử lý ngay!</strong> Các lô thuốc sau đã hết hạn sử dụng và phải được thu hồi khỏi kho:</p>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tên thuốc</th>
                            <th>Mã lô</th>
                            <th>Hạn sử dụng</th>
                            <th>Số lượng</th>
                            <th>Giá trị (VNĐ)</th>
                            <th>Nhà cung cấp</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($thuocDaHetHan as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $item->thuoc->ten }}</strong><br><small>{{ $item->thuoc->ham_luong }}</small></td>
                                <td class="expired">{{ $item->ma_lo ?? 'N/A' }}</td>
                                <td class="expired">{{ \Carbon\Carbon::parse($item->han_su_dung)->format('d/m/Y') }}</td>
                                <td>{{ number_format($item->so_luong) }} {{ $item->thuoc->don_vi }}</td>
                                <td>{{ number_format($item->so_luong * $item->gia_nhap, 0, ',', '.') }}</td>
                                <td>{{ $item->nhaCungCap->ten ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5">Tổng giá trị đã hết hạn:</th>
                            <th colspan="2">{{ number_format($thuocDaHetHan->sum(fn($i) => $i->so_luong * $i->gia_nhap), 0, ',', '.') }} VNĐ</th>
                        </tr>
                    </tfoot>
                </table>
            @endif

            @if($thuocSapHetHan->isNotEmpty())
                <div class="alert-warning">
                    <h3>⏰ Thuốc SẮP HẾT HẠN ({{ $thuocSapHetHan->count() }} lô)</h3>
                    <p>Các lô thuốc sau sẽ hết hạn trong vòng 30 ngày tới. Cần ưu tiên xuất kho theo FIFO:</p>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tên thuốc</th>
                            <th>Mã lô</th>
                            <th>Hạn sử dụng</th>
                            <th>Còn lại (ngày)</th>
                            <th>Số lượng</th>
                            <th>Giá trị (VNĐ)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($thuocSapHetHan as $index => $item)
                            @php
                                $conLai = \Carbon\Carbon::parse($item->han_su_dung)->diffInDays($ngayKiemTra);
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $item->thuoc->ten }}</strong><br><small>{{ $item->thuoc->ham_luong }}</small></td>
                                <td>{{ $item->ma_lo ?? 'N/A' }}</td>
                                <td class="warning">{{ \Carbon\Carbon::parse($item->han_su_dung)->format('d/m/Y') }}</td>
                                <td class="warning">{{ $conLai }} ngày</td>
                                <td>{{ number_format($item->so_luong) }} {{ $item->thuoc->don_vi }}</td>
                                <td>{{ number_format($item->so_luong * $item->gia_nhap, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="6">Tổng giá trị sắp hết hạn:</th>
                            <th>{{ number_format($thuocSapHetHan->sum(fn($i) => $i->so_luong * $i->gia_nhap), 0, ',', '.') }} VNĐ</th>
                        </tr>
                    </tfoot>
                </table>
            @endif

            <div style="margin-top: 20px; padding: 15px; background: #e3f2fd; border-left: 4px solid #2196f3;">
                <h4>📋 Hành động cần thực hiện:</h4>
                <ul>
                    <li><strong>Thuốc đã hết hạn:</strong> Thu hồi ngay, lập phiếu tiêu hủy, báo cáo nhà cung cấp</li>
                    <li><strong>Thuốc sắp hết hạn:</strong> Ưu tiên xuất kho FIFO, giảm giá khuyến mãi nếu cần</li>
                    <li><strong>Kiểm tra định kỳ:</strong> Rà soát kho mỗi tuần, cập nhật hạn sử dụng khi nhập mới</li>
                </ul>
            </div>
        </div>

        <div class="footer">
            <p>Email tự động từ Hệ thống Quản lý Phòng khám</p>
            <p>Vui lòng không trả lời email này. Liên hệ IT nếu có thắc mắc.</p>
        </div>
    </div>
</body>
</html>
