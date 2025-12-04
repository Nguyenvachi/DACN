<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Báo Cáo Lịch Làm Việc</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #667eea;
        }
        .header h1 {
            color: #667eea;
            margin: 0;
            font-size: 24px;
        }
        .info-box {
            background: #f5f7ff;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            border-left: 4px solid #667eea;
        }
        .info-box p {
            margin: 5px 0;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            background: #667eea;
            color: white;
            padding: 10px;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th {
            background: #f0f4ff;
            color: #667eea;
            font-weight: bold;
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        table tr:nth-child(even) {
            background: #f9f9f9;
        }
        .no-data {
            text-align: center;
            color: #999;
            font-style: italic;
            padding: 20px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
        }
        .badge-add { background: #d4edda; color: #155724; }
        .badge-modify { background: #fff3cd; color: #856404; }
        .badge-cancel { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>BÁO CÁO LỊCH LÀM VIỆC</h1>
        <p style="margin: 5px 0;">Phòng Khám - Hệ Thống Quản Lý Bệnh Viện</p>
    </div>

    <!-- Info Box -->
    <div class="info-box">
        <p><strong>Bác sĩ:</strong> {{ $bacSi->ten }}</p>
        <p><strong>Chuyên khoa:</strong> {{ $bacSi->chuyenKhoa->ten ?? 'N/A' }}</p>
        <p><strong>Số điện thoại:</strong> {{ $bacSi->sdt ?? 'N/A' }}</p>
        <p><strong>Email:</strong> {{ $bacSi->email ?? 'N/A' }}</p>
        <p><strong>Thời gian báo cáo:</strong> {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</p>
        <p><strong>Ngày xuất:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <!-- Lịch làm việc định kỳ -->
    <div class="section">
        <div class="section-title">LỊCH LÀM VIỆC ĐỊNH KỲ</div>
        @if($lichLamViecs->isNotEmpty())
            <table>
                <thead>
                    <tr>
                        <th>Thứ</th>
                        <th>Giờ bắt đầu</th>
                        <th>Giờ kết thúc</th>
                        <th>Phòng</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $weekdays = ['Chủ nhật', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy'];
                    @endphp
                    @foreach($lichLamViecs as $lich)
                        <tr>
                            <td>{{ $weekdays[$lich->ngay_trong_tuan] }}</td>
                            <td>{{ $lich->thoi_gian_bat_dau }}</td>
                            <td>{{ $lich->thoi_gian_ket_thuc }}</td>
                            <td>{{ $lich->phong->ten ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">Không có lịch làm việc định kỳ</div>
        @endif
    </div>

    <!-- Lịch nghỉ -->
    <div class="section">
        <div class="section-title">LỊCH NGHỈ</div>
        @if($lichNghis->isNotEmpty())
            <table>
                <thead>
                    <tr>
                        <th>Ngày</th>
                        <th>Giờ bắt đầu</th>
                        <th>Giờ kết thúc</th>
                        <th>Lý do</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lichNghis as $lich)
                        <tr>
                            <td>{{ $lich->ngay->format('d/m/Y') }}</td>
                            <td>{{ $lich->bat_dau }}</td>
                            <td>{{ $lich->ket_thuc }}</td>
                            <td>{{ $lich->ly_do ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">Không có lịch nghỉ trong thời gian này</div>
        @endif
    </div>

    <!-- Ca điều chỉnh -->
    <div class="section">
        <div class="section-title">CA ĐIỀU CHỈNH</div>
        @if($caDieuChinhs->isNotEmpty())
            <table>
                <thead>
                    <tr>
                        <th>Ngày</th>
                        <th>Giờ bắt đầu</th>
                        <th>Giờ kết thúc</th>
                        <th>Hành động</th>
                        <th>Lý do</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($caDieuChinhs as $ca)
                        @php
                            $badgeClass = match($ca->hanh_dong) {
                                'add' => 'badge-add',
                                'modify' => 'badge-modify',
                                'cancel' => 'badge-cancel',
                                default => '',
                            };
                            $hanhDongLabel = match($ca->hanh_dong) {
                                'add' => 'Thêm',
                                'modify' => 'Sửa',
                                'cancel' => 'Hủy',
                                default => $ca->hanh_dong,
                            };
                        @endphp
                        <tr>
                            <td>{{ $ca->ngay->format('d/m/Y') }}</td>
                            <td>{{ $ca->gio_bat_dau }}</td>
                            <td>{{ $ca->gio_ket_thuc }}</td>
                            <td><span class="badge {{ $badgeClass }}">{{ $hanhDongLabel }}</span></td>
                            <td>{{ $ca->ly_do ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">Không có ca điều chỉnh trong thời gian này</div>
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Báo cáo được tạo tự động bởi Hệ Thống Quản Lý Phòng Khám</p>
        <p>{{ config('app.name') }} - {{ config('app.url') }}</p>
    </div>
</body>
</html>
