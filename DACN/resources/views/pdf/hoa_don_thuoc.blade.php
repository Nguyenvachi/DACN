<!doctype html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Hóa đơn thuốc #{{ $hoaDon->id }}</title>
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
                <div class="hospital-name">BỆNH VIỆN ĐA KHOA QUỐC TẾ</div>
                <div class="hospital-sub">PHÒNG KHÁM BỆNH VIỆN ĐẠI HỌC (MÔ PHỎNG)</div>
                <div class="hospital-sub">📍 Địa chỉ: 215 Hồng Bàng, P.11, Q.5, TP.HCM</div>
                <div class="hospital-sub">📞 Hotline: 1900 1234</div>
            </td>
            <td width="25%">
                <div class="qr-box">
                    <img src="{{ qr_code_data_uri('HD#' . $hoaDon->id . '|' . ($hoaDon->tong_tien ?? 0) . '|' . optional($hoaDon->updated_at ?? $hoaDon->created_at)->format('Y-m-d')) }}" alt="QR" style="width:65px;height:65px;object-fit:cover;" />
                </div>
                <div style="clear:both; text-align: right; margin-top: 5px; font-weight: bold; color: #cc0000;">
                    #{{ $hoaDon->id }}
                </div>
            </td>
        </tr>
    </table>

    <div class="receipt-title">HÓA ĐƠN THUỐC / PHARMACY INVOICE</div>
    <div class="receipt-sub">(Thuốc BHYT & Dịch vụ / Insurance & Service Drugs)</div>

    {{-- 2. THÔNG TIN HÀNH CHÍNH --}}
    <div class="info-box">
        <table>
            <tr style="background-color: #eee;">
                <td colspan="4" class="text-center font-bold">THÔNG TIN BỆNH NHÂN / PATIENT INFO</td>
            </tr>
            <tr>
                <td width="55%">
                    <span class="label">Họ tên/Name:</span>
                    <span class="value">{{ optional($hoaDon->user)->name ?? '#' . $hoaDon->user_id }}</span>
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
                    <span class="label">Bác sĩ kê đơn/Doctor:</span>
                    {{-- Logic giả định: Lấy tên bác sĩ từ quan hệ Lịch hẹn --}}
                    {{ optional(optional($hoaDon->lichHen)->doctor)->name ?? 'Bác sĩ trực' }}
                </td>
                <td colspan="2">
                    <span class="label">Mã Lịch Hẹn:</span> #{{ $hoaDon->lich_hen_id }}
                </td>
            </tr>
        </table>
    </div>

    {{-- 3. CHI TIẾT ĐƠN THUỐC (PRESCRIPTION DETAILS) --}}
    <div style="margin-bottom: 5px; font-weight: bold;">1. CHI TIẾT ĐƠN THUỐC / PRESCRIPTION:</div>
    <table class="data-table" style="margin-bottom: 15px;">
        <thead>
            <tr>
                <th width="10%">STT</th>
                <th width="45%">Tên thuốc / Medicine Name</th>
                <th width="15%">ĐVT/Unit</th>
                <th width="10%">SL/Qty</th>
                <th width="20%">Thành tiền/Amount</th>
            </tr>
        </thead>
        <tbody>
            {{-- Hiện tại code cũ của bạn chỉ có 1 dòng tổng, mình trình bày nó vào bảng này --}}
            {{-- Nếu có chi tiết thuốc (donThuoc->items), hiển thị itemized lines with gia_tham_khao when available --}}
            <tr>
                <td class="text-center">1</td>
                <td>
                    <strong>Thuốc theo đơn (Prescription Drugs)</strong><br>
                    <small><i>(Theo toa bác sĩ)</i></small>
                </td>
                <td class="text-center">Toa</td>
                <td class="text-center">1</td>
                <td class="text-right font-bold">
                    @php
                        // If donThuoc items exist, try to compute total from items, otherwise fallback to hoaDon->tong_tien
                        $computed = null;
                        if(optional($hoaDon->donThuoc)->items && optional($hoaDon->donThuoc)->items->count()){
                            $computed = optional($hoaDon->donThuoc)->items->sum(function($it){
                                $qty = $it->so_luong ?? 1;
                                if(isset($it->gia) && $it->gia) return $it->gia * $qty;
                                if(optional($it->thuoc)->gia_tham_khao) return optional($it->thuoc)->gia_tham_khao * $qty;
                                return 0;
                            });
                        }
                        $displayTotal = $computed ? $computed : $hoaDon->tong_tien;
                    @endphp
                    {{ number_format($displayTotal, 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 8px; font-style: italic; font-size: 9pt;">
        Số tiền (bằng số): <strong>{{ number_format($displayTotal, 0, ',', '.') }} đ</strong><br>
        Số tiền (bằng chữ): <strong>{{ function_exists('format_money_in_words') ? format_money_in_words($displayTotal) : '' }}</strong>
    </div>

    {{-- 4. LỊCH SỬ THANH TOÁN (PAYMENT LOGS - Đảm bảo tính minh bạch) --}}
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

    {{-- 5. FOOTER (Chữ ký Dược sĩ) --}}
    <div style="margin-top: 30px;">
        <table style="text-align: center;">
            <tr>
                <td width="33%">
                    <div class="font-bold">DƯỢC SĨ CẤP PHÁT</div>
                    <div style="font-size: 8pt;">(Pharmacist)</div>
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
                    <div class="font-bold">NGƯỜI NHẬN THUỐC</div>
                    <div style="font-size: 8pt;">(Receiver)</div>
                </td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 20px; font-size: 9pt; text-align: center; font-style: italic; color: #555;">
        Lưu ý: Kiểm tra kỹ thuốc trước khi rời quầy. Thuốc đã mua không được trả lại.
    </div>

</body>

</html>
