<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bệnh án #{{ $benh_an->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 13px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #2563eb;
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            font-size: 12px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            background-color: #f3f4f6;
            padding: 8px 12px;
            font-weight: bold;
            font-size: 14px;
            color: #1f2937;
            border-left: 4px solid #2563eb;
            margin-bottom: 10px;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            font-weight: bold;
            padding: 5px 10px 5px 0;
            width: 30%;
            color: #4b5563;
        }
        .info-value {
            display: table-cell;
            padding: 5px 0;
            color: #111827;
        }
        .diagnosis-box {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 12px;
            margin: 10px 0;
        }
        .prescription-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .prescription-table th {
            background-color: #e5e7eb;
            padding: 8px;
            text-align: left;
            font-size: 12px;
            border: 1px solid #d1d5db;
        }
        .prescription-table td {
            padding: 6px 8px;
            border: 1px solid #d1d5db;
            font-size: 12px;
        }
        .prescription-table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .footer {
            margin-top: 40px;
            text-align: right;
        }
        .signature {
            margin-top: 50px;
        }
        .signature p {
            margin-bottom: 5px;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
        }
        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }
        .test-results {
            background-color: #f0f9ff;
            border: 1px solid #bae6fd;
            padding: 10px;
            margin-top: 5px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <h1>BỆNH ÁN KHÁM BỆNH</h1>
        <p>Mã bệnh án: #{{ $benh_an->id }}</p>
        <p>Ngày in: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>

    <!-- Thông tin bệnh nhân -->
    <div class="section">
        <div class="section-title">THÔNG TIN BỆNH NHÂN</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Họ và tên:</div>
                <div class="info-value">{{ $benh_an->benhNhan->name ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value">{{ $benh_an->benhNhan->email ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Ngày khám:</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($benh_an->ngay_kham)->format('d/m/Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Bác sĩ khám:</div>
                <div class="info-value">{{ $benh_an->bacSi->user->name ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Dịch vụ:</div>
                <div class="info-value">{{ $benh_an->dichVu->ten_dich_vu ?? 'Khám tổng quát' }}</div>
            </div>
        </div>
    </div>

    <!-- Thông tin y tế -->
    @if($benh_an->benhNhan->patientProfile)
        @php $profile = $benh_an->benhNhan->patientProfile; @endphp
        <div class="section">
            <div class="section-title">THÔNG TIN Y TẾ</div>
            <div class="info-grid">
                @if($profile->nhom_mau)
                    <div class="info-row">
                        <div class="info-label">Nhóm máu:</div>
                        <div class="info-value">{{ $profile->nhom_mau }}</div>
                    </div>
                @endif
                @if($profile->chieu_cao && $profile->can_nang)
                    <div class="info-row">
                        <div class="info-label">Chiều cao / Cân nặng:</div>
                        <div class="info-value">{{ $profile->chieu_cao }} cm / {{ $profile->can_nang }} kg (BMI: {{ $profile->bmi }})</div>
                    </div>
                @endif
                @if($profile->allergies && count($profile->allergies) > 0)
                    <div class="info-row">
                        <div class="info-label">Dị ứng:</div>
                        <div class="info-value">
                            @foreach($profile->allergies as $allergy)
                                <span class="badge badge-warning">{{ $allergy }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
                @if($profile->tien_su_benh)
                    <div class="info-row">
                        <div class="info-label">Tiền sử bệnh:</div>
                        <div class="info-value">{{ $profile->tien_su_benh }}</div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Triệu chứng -->
    <div class="section">
        <div class="section-title">TRIỆU CHỨNG</div>
        <p style="padding: 8px 0;">{{ $benh_an->trieu_chung }}</p>
    </div>

    <!-- Chẩn đoán -->
    <div class="section">
        <div class="section-title">CHẨN ĐOÁN</div>
        <div class="diagnosis-box">
            <strong>{{ $benh_an->chan_doan }}</strong>
        </div>
    </div>

    <!-- Điều trị -->
    <div class="section">
        <div class="section-title">ĐIỀU TRỊ & CHỈ DẪN</div>
        <p style="padding: 8px 0;">{{ $benh_an->dieu_tri }}</p>
    </div>

    <!-- Đơn thuốc -->
    @if($benh_an->donThuocs && $benh_an->donThuocs->isNotEmpty())
        <div class="section">
            <div class="section-title">ĐỚN THUỐC</div>
            @foreach($benh_an->donThuocs as $donThuoc)
                <table class="prescription-table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">STT</th>
                            <th style="width: 30%;">Tên thuốc</th>
                            <th style="width: 15%;">Liều lượng</th>
                            <th style="width: 10%;">Số lượng</th>
                            <th style="width: 40%;">Hướng dẫn sử dụng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($donThuoc->items as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->thuoc->ten_thuoc ?? $item->ten_thuoc }}</td>
                                <td>{{ $item->lieu_luong }}</td>
                                <td>{{ $item->so_luong }}</td>
                                <td>{{ $item->huong_dan_su_dung }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($donThuoc->ghi_chu)
                    <p style="margin-top: 8px; font-style: italic; color: #666;">
                        <strong>Ghi chú:</strong> {{ $donThuoc->ghi_chu }}
                    </p>
                @endif
            @endforeach
        </div>
    @endif

    <!-- Xét nghiệm -->
    @if($benh_an->xetNghiems && $benh_an->xetNghiems->isNotEmpty())
        <div class="section">
            <div class="section-title">KẾT QUẢ XÉT NGHIỆM</div>
            @foreach($benh_an->xetNghiems as $xetNghiem)
                <div class="test-results">
                    <p><strong>{{ $xetNghiem->loai ?? 'Xét nghiệm' }}</strong></p>
                    <p style="margin-top: 5px;">{{ $xetNghiem->ket_qua }}</p>
                    @if($xetNghiem->ghi_chu)
                        <p style="margin-top: 3px; font-size: 11px; color: #666;">
                            <em>{{ $xetNghiem->ghi_chu }}</em>
                        </p>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <!-- Lời dặn -->
    @if($benh_an->ghi_chu)
        <div class="section">
            <div class="section-title">GHI CHÚ & LỜI DẶN</div>
            <p style="padding: 8px 0;">{{ $benh_an->ghi_chu }}</p>
        </div>
    @endif

    <!-- Chữ ký -->
    <div class="footer">
        <div class="signature">
            <p><em>Ngày {{ \Carbon\Carbon::parse($benh_an->ngay_kham)->format('d') }} tháng {{ \Carbon\Carbon::parse($benh_an->ngay_kham)->format('m') }} năm {{ \Carbon\Carbon::parse($benh_an->ngay_kham)->format('Y') }}</em></p>
            <p style="margin-top: 5px;"><strong>Bác sĩ khám bệnh</strong></p>
            <p style="margin-top: 60px; font-style: italic;">(Ký và ghi rõ họ tên)</p>
            <p style="margin-top: 10px;"><strong>{{ $benh_an->bacSi->user->name ?? '' }}</strong></p>
        </div>
    </div>

</body>
</html>
