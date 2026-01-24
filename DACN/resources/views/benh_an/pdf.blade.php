<!doctype html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Bệnh án #{{ $benh_an->id }}</title>
    <style>
        /* Match system PDF style used for invoices */
        body {
            font-family: 'DejaVu Serif', serif;
            font-size: 10pt;
            line-height: 1.3;
            color: #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .hospital-name {
            font-size: 13pt;
            font-weight: bold;
            color: #0056b3;
            text-transform: uppercase;
        }

        .hospital-sub {
            font-size: 9pt;
            color: #333;
        }

        .doc-title {
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            margin-top: 6px;
            text-transform: uppercase;
        }

        .info-box {
            border: 2px solid #000;
            margin-bottom: 12px;
        }

        .info-box td {
            border: 1px solid #777;
            padding: 6px;
            vertical-align: top;
        }

        .label {
            font-weight: bold;
            font-size: 9pt;
            color: #333;
            display: block;
            margin-bottom: 2px;
        }

        .value {
            font-size: 10pt;
            color: #111;
        }

        .section-title {
            font-weight: bold;
            margin: 8px 0 6px;
        }

        .data-table th {
            border: 1px solid #000;
            background-color: #f2f2f2;
            font-weight: bold;
            padding: 6px;
            text-align: left;
            font-size: 9pt;
        }

        .data-table td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 10pt;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }

        .small-muted { font-size: 9pt; color: #666; }

    </style>
</head>

<body>

    <table>
        <tr>
            <td width="70%">
                <div class="hospital-name">BỆNH VIỆN ĐA KHOA QUỐC TẾ</div>
                <div class="hospital-sub">PHÒNG KHÁM - MẪU HỆ THỐNG</div>
                <div class="hospital-sub">📍 Địa chỉ: (nhập từ config)</div>
                <div class="hospital-sub">📞 Hotline: (nhập từ config)</div>
            </td>
            <td width="30%">
                <div style="text-align:right;">
                    <div style="font-weight:bold; color:#cc0000; font-size:12pt;">#{{ $benh_an->id }}</div>
                    <div style="margin-top:6px; font-size:9pt;">Ngày in: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</div>
                </div>
            </td>
        </tr>
    </table>

    <div class="doc-title">Bệnh án khám bệnh</div>

    {{-- I. Thông tin hành chính --}}
    <div class="info-box">
        <table>
            <tr style="background-color:#eee;">
                <td colspan="4" class="text-center" style="font-weight:bold;">THÔNG TIN BỆNH NHÂN</td>
            </tr>
            <tr>
                <td width="40%">
                    <span class="label">Họ và tên:</span>
                    <div class="value">{{ optional($benh_an->benhNhan)->name ?? ('#' . $benh_an->user_id) }}</div>
                </td>
                <td width="20%">
                    <span class="label">SĐT:</span>
                    <div class="value">{{ optional($benh_an->benhNhan)->so_dien_thoai ?? '—' }}</div>
                </td>
                <td width="20%">
                    <span class="label">Ngày khám:</span>
                    <div class="value">{{ optional($benh_an->ngay_kham)->format('d/m/Y') }}</div>
                </td>
                <td width="20%">
                    <span class="label">Mã BN / Mã lịch:</span>
                    <div class="value">{{ $benh_an->lichHen->ma_lich_hen ?? '#' . $benh_an->id }}</div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="label">Bác sĩ khám:</span>
                    <div class="value">{{ optional($benh_an->bacSi)->ho_ten ?? optional(optional($benh_an->bacSi)->user)->name ?? '—' }}</div>
                </td>
                <td colspan="2">
                    <span class="label">Dịch vụ:</span>
                    <div class="value">{{ optional($benh_an->dichVu)->ten_dich_vu ?? 'Khám bệnh' }}</div>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <span class="label">Ghi chú:</span>
                    <div class="small-muted">{{ $benh_an->ghi_chu ?? '—' }}</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- II. Triệu chứng / Chẩn đoán / Điều trị --}}
    <div class="section-title">TRIỆU CHỨNG</div>
    <div style="margin-bottom:8px">{!! nl2br(e($benh_an->trieu_chung ?: '—')) !!}</div>

    <div class="section-title">CHẨN ĐOÁN</div>
    <div style="margin-bottom:8px; padding:6px; border-left:4px solid #f59e0b; background:#fff7ed">{!! nl2br(e($benh_an->chuan_doan ?? $benh_an->chan_doan ?? '—')) !!}</div>

    <div class="section-title">ĐIỀU TRỊ & CHỈ DẪN</div>
    <div style="margin-bottom:12px">{!! nl2br(e($benh_an->dieu_tri ?? '—')) !!}</div>

    {{-- III. Đơn thuốc (nếu có) --}}
    @if($benh_an->donThuocs && $benh_an->donThuocs->isNotEmpty())
        <div class="section-title">ĐƠN THUỐC</div>
        @foreach($benh_an->donThuocs as $don)
            <table class="data-table" style="margin-bottom:10px;">
                <thead>
                    <tr>
                        <th width="8%">STT</th>
                        <th width="45%">Tên thuốc</th>
                        <th width="12%">Liều</th>
                        <th width="10%">Số lượng</th>
                        <th width="25%">Hướng dẫn</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($don->items as $i => $item)
                        <tr>
                            <td class="text-center">{{ $i+1 }}</td>
                            <td>{{ optional($item->thuoc)->ten ?? ($item->ten ?? '—') }}</td>
                            <td>{{ $item->lieu_dung ?? ($item->lieu_luong ?? '—') }}</td>
                            <td class="text-center">{{ $item->so_luong ?? '—' }}</td>
                            <td>{{ $item->cach_dung ?? ($item->huong_dan_su_dung ?? '—') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($don->ghi_chu)
                <div style="font-style:italic; color:#666; margin-bottom:10px;"><strong>Ghi chú:</strong> {{ $don->ghi_chu }}</div>
            @endif
        @endforeach
    @endif

    {{-- IV. Kết quả xét nghiệm --}}
    @if($benh_an->xetNghiems && $benh_an->xetNghiems->isNotEmpty())
        <div class="section-title">KẾT QUẢ XÉT NGHIỆM</div>
        @foreach($benh_an->xetNghiems as $xn)
            <div style="border:1px solid #d1d5db; padding:8px; margin-bottom:8px; background:#f8fafc;">
                <div style="font-weight:bold;">{{ $xn->loai ?? 'Xét nghiệm' }}</div>
                <div style="margin-top:6px;">{{ $xn->ket_qua ?? ($xn->mo_ta ?? '—') }}</div>
                @if($xn->ghi_chu)
                    <div style="margin-top:6px; font-size:9pt; color:#666;"><em>{{ $xn->ghi_chu }}</em></div>
                @endif
            </div>
        @endforeach
    @endif

    {{-- FOOTER / Signature --}}
    <div style="margin-top:20px;">
        <table>
            <tr>
                <td width="60%"></td>
                <td width="40%" style="text-align:center;">
                    <div style="font-size:10pt;">Ngày {{ optional($benh_an->ngay_kham)->format('d') }} tháng {{ optional($benh_an->ngay_kham)->format('m') }} năm {{ optional($benh_an->ngay_kham)->format('Y') }}</div>
                    <div style="margin-top:10px; font-weight:bold;">Bác sĩ khám bệnh</div>
                    <div style="height:70px;"></div>
                    <div style="font-weight:bold;">{{ optional(optional($benh_an->bacSi)->user)->name ?? optional($benh_an->bacSi)->ho_ten ?? '' }}</div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
