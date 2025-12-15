<!doctype html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Hóa đơn Dịch vụ HD-{{ str_pad($hoaDon->id, 4, '0', STR_PAD_LEFT) }}</title>
    <style>
        /* 1. CẤU HÌNH STYLE ĐỒNG BỘ (Serif Font - Chuẩn Y Dược) */
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

        /* HEADER */
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

        /* TITLE */
        .receipt-title {
            text-align: center;
            font-size: 18pt;
            font-weight: bold;
            margin-top: 15px;
            text-transform: uppercase;
        }

        .receipt-sub {
            text-align: center;
            font-size: 10pt;
            font-style: italic;
            margin-bottom: 15px;
        }

        /* INFO GRID */
        .info-box {
            border: 2px solid #000;
            margin-bottom: 15px;
        }

        .info-box td {
            border: 1px solid #999;
            padding: 5px;
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
            font-weight: bold;
            font-size: 10pt;
            text-transform: uppercase;
        }

        /* DATA TABLES */
        .data-table th {
            border: 1px solid #000;
            background-color: #f2f2f2;
            font-weight: bold;
            padding: 6px;
            text-align: center;
            font-size: 9pt;
        }

        .data-table td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 10pt;
        }

        /* UTILS */
        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }

        .text-success {
            color: #198754;
        }

        .text-danger {
            color: #dc3545;
        }

        /* QR Code Box */
        .qr-box {
            border: 1px solid #000;
            width: 65px;
            height: 65px;
            text-align: center;
            line-height: 65px;
            float: right;
            background: #eee;
            font-size: 9px;
        }
    </style>
</head>

<body>

    {{-- 1. HEADER --}}
    <table>
        <tr>
            <td width="75%">
                <div class="hospital-name">PHÒNG KHÁM SẢN PHỤ KHOA</div>
                <div class="hospital-sub">CHĂM SÓC SỨC KHỎE PHỤ NỮ & TRẺ EM</div>
                <div class="hospital-sub">📍 Địa chỉ: 215 Hồng Bàng, P.11, Q.5, TP.HCM</div>
                <div class="hospital-sub">📞 Hotline: 1900 1234</div>
            </td>
            <td width="25%">
                <div class="qr-box">
                    <img src="{{ qr_code_data_uri('HD#' . $hoaDon->id . '|' . ($hoaDon->tong_tien ?? 0) . '|' . optional($hoaDon->updated_at ?? $hoaDon->created_at)->format('Y-m-d')) }}" alt="QR" style="width:65px;height:65px;object-fit:cover;" />
                </div>
                <div style="clear:both; text-align: right; margin-top: 5px; font-weight: bold; color: #cc0000;">
                    HD-{{ str_pad($hoaDon->id, 4, '0', STR_PAD_LEFT) }}
                </div>
            </td>
        </tr>
    </table>

    <div class="receipt-title">HÓA ĐƠN DỊCH VỤ / SERVICE INVOICE</div>
    <div class="receipt-sub">(Cận lâm sàng & Thủ thuật / CLS & Procedures)</div>

    {{-- 2. THÔNG TIN HÀNH CHÍNH --}}
    <div class="info-box">
        <table>
            <tr style="background-color: #eee;">
                <td colspan="4" class="text-center font-bold">THÔNG TIN BỆNH NHÂN / PATIENT INFO</td>
            </tr>
            <tr>
                <td width="55%">
                    <span class="label">Họ tên/Name:</span>
                    <span class="value">{{ optional($hoaDon->user)->name ?? 'BN-' . str_pad($hoaDon->user_id, 4, '0', STR_PAD_LEFT) }}</span>
                </td>
                <td width="20%">
                    <span class="label">Mã BN:</span>
                    {{ $hoaDon->user_id }}
                </td>
                <td width="25%">
                    <span class="label">Trạng thái:</span>
                    {{ $hoaDon->trang_thai }}
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="label">Chỉ định/Service:</span>
                    {{ optional(optional($hoaDon->lichHen)->dichVu)->ten ?? 'Dịch vụ CLS' }}
                </td>
                <td colspan="2">
                    <span class="label">Mã Lịch Hẹn:</span> LH-{{ str_pad($hoaDon->lich_hen_id, 4, '0', STR_PAD_LEFT) }}
                </td>
            </tr>
        </table>
    </div>

    {{-- 3. CHI TIẾT DỊCH VỤ (SERVICE DETAILS) --}}
    <div style="margin-bottom: 5px; font-weight: bold;">1. CHI TIẾT DỊCH VỤ / SERVICE LIST:</div>
    <table class="data-table" style="margin-bottom: 15px;">
        <thead>
            <tr>
                <th width="10%">STT</th>
                <th width="60%">Tên Dịch vụ / Service Name</th>
                <th width="30%">Thành tiền / Amount (VNĐ)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1</td>
                <td>
                    <strong>{{ optional(optional($hoaDon->lichHen)->dichVu)->ten ?? 'Khám chữa bệnh' }}</strong>
                    <br>
                    <small><i>(Theo chỉ định của bác sĩ)</i></small>
                </td>
                <td class="text-right font-bold">
                    {{ number_format($hoaDon->tong_tien, 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 8px; font-style: italic; font-size: 9pt;">
        Số tiền (bằng số): <strong>{{ number_format($hoaDon->tong_tien, 0, ',', '.') }} đ</strong><br>
        Số tiền (bằng chữ): <strong>{{ function_exists('format_money_in_words') ? format_money_in_words($hoaDon->tong_tien) : '' }}</strong>
    </div>

    {{-- 4. LỊCH SỬ THANH TOÁN (PAYMENT LOGS - Logic chuẩn từ show.blade.php) --}}
    <div style="margin-bottom: 5px; font-weight: bold;">2. LỊCH SỬ GIAO DỊCH / TRANSACTION LOGS:</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="25%">Thời gian</th>
                <th width="20%">Kênh (Provider)</th>
                <th width="30%">Mã GD (Ref)</th>
                <th width="25%">Số tiền</th>
            </tr>
        </thead>
        <tbody>
            @forelse($hoaDon->thanhToans as $tt)
                <tr>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($tt->paid_at ?? $tt->created_at)->format('H:i d/m/Y') }}
                    </td>
                    <td class="text-center">
                        <span style="text-transform: uppercase;">{{ $tt->provider }}</span>
                    </td>
                    <td class="text-center">{{ $tt->transaction_ref ?? '-' }}</td>
                    <td class="text-right font-bold text-success">
                        {{ number_format($tt->so_tien, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center" style="font-style: italic; color: #777;">
                        Chưa có giao dịch nào
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            @php
                $daThanhToan = $hoaDon->thanhToans->sum('so_tien');
                $conLai = $hoaDon->tong_tien - $daThanhToan;
            @endphp
            <tr>
                <td colspan="3" class="text-right font-bold" style="border:none;">TỔNG CỘNG / TOTAL:</td>
                <td class="text-right font-bold" style="font-size: 11pt; border: 1px solid #000;">
                    {{ number_format($hoaDon->tong_tien, 0, ',', '.') }} đ
                </td>
            </tr>
            @if ($conLai > 0)
                <tr>
                    <td colspan="3" class="text-right font-bold" style="border:none;">CÒN LẠI / DUE:</td>
                    <td class="text-right font-bold text-danger" style="border: 1px solid #000;">
                        {{ number_format($conLai, 0, ',', '.') }} đ
                    </td>
                </tr>
            @endif
        </tfoot>
    </table>

    {{-- 5. FOOTER --}}
    <div style="margin-top: 30px;">
        <table style="text-align: center;">
            <tr>
                <td width="33%">
                    <div class="font-bold">KỸ THUẬT VIÊN</div>
                    <div style="font-size: 8pt;">(Technician)</div>
                    <div style="height: 60px;"></div>
                    <div style="font-size: 9pt;">(Ký, ghi rõ họ tên)</div>
                </td>
                <td width="33%">
                    <div class="font-bold">THU NGÂN</div>
                    <div style="font-size: 8pt;">(Cashier)</div>
                    <div style="height: 60px;"></div>
                    <div style="font-size: 9pt;">(Ký, đóng dấu)</div>
                </td>
                <td width="33%">
                    <div style="font-size: 9pt; font-style: italic;">
                        Ngày {{ now()->day }} tháng {{ now()->month }} năm {{ now()->year }}
                    </div>
                    <div class="font-bold">NGƯỜI NỘP TIỀN</div>
                    <div style="font-size: 8pt;">(Payer)</div>
                </td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 20px; font-size: 9pt; text-align: center; font-style: italic; color: #555;">
        Lưu ý: Bệnh nhân vui lòng mang phiếu này đến phòng chức năng để thực hiện dịch vụ.
    </div>

</body>

</html>
