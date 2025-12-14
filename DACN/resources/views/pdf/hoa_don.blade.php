<!doctype html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Hóa đơn #{{ $hoaDon->id }}</title>
    <style>
        /* 1. CẤU HÌNH GIAO DIỆN CHUẨN Y DƯỢC (Font có chân Serif) */
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

        /* TIÊU ĐỀ */
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
            margin-bottom: 10px;
        }

        /* KHUNG THÔNG TIN (Style kẻ ô chi tiết) */
        .info-box {
            border: 2px solid #000;
            margin-bottom: 15px;
        }

        .info-box td {
            border: 1px solid #777;
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

        /* BẢNG DỮ LIỆU (Dùng cho Dịch vụ & Lịch sử thanh toán) */
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

        /* Màu xanh success của bootstrap */
        .text-danger {
            color: #dc3545;
        }

        /* QR Code mô phỏng */
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

    {{-- HEADER --}}
    <table>
        <tr>
            <td width="75%">
                <div class="hospital-name">PHÒNG KHÁM SẢN PHỤ KHOA</div>
                <div class="hospital-sub">CHĂM SÓC SỨC KHỎE PHỤ NỮ & TRẺ EM</div>
                <div class="hospital-sub">📍 Địa chỉ: 215 Hồng Bàng, P.11, Q.5, TP.HCM</div>
                <div class="hospital-sub">📞 Hotline: 1900 1234</div>
            </td>
            <td width="25%">
                {{-- Khu vực mã QR --}}
                <div class="qr-box">
                    <img src="{{ qr_code_data_uri('HD#' . $hoaDon->id . '|' . ($hoaDon->tong_tien ?? 0) . '|' . optional($hoaDon->updated_at ?? $hoaDon->created_at)->format('Y-m-d')) }}"
                        alt="QR" style="width:65px;height:65px;object-fit:cover;" />
                </div>
                <div style="clear:both; text-align: right; margin-top: 5px; font-weight: bold; color: #cc0000;">
                    #{{ $hoaDon->id }}
                </div>
            </td>
        </tr>
    </table>

    <div class="receipt-title">BIÊN LAI THU TIỀN / RECEIPT</div>
    <div class="receipt-sub">(Bản chính / Original)</div>

    {{-- I. THÔNG TIN HÀNH CHÍNH (Map đúng theo show.blade.php) --}}
    <div class="info-box">
        <table>
            <tr style="background-color: #eee;">
                <td colspan="4" class="text-center font-bold">THÔNG TIN HÀNH CHÍNH / PATIENT INFORMATION</td>
            </tr>
            <tr>
                <td width="50%">
                    <span class="label">Họ tên/Name:</span>
                    {{-- Logic lấy tên: nếu user null thì lấy id --}}
                    <span class="value">{{ optional($hoaDon->user)->name ?? '#' . $hoaDon->user_id }}</span>
                </td>
                <td width="25%">
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
                    <span class="label">Dịch vụ/Khoa:</span>
                    {{-- Lấy tên dịch vụ từ quan hệ LichHen -> DichVu --}}
                    {{ optional(optional($hoaDon->lichHen)->dichVu)->ten ?? 'Khám bệnh' }}
                </td>
                <td colspan="2">
                    <span class="label">Mã Lịch Hẹn:</span> #{{ $hoaDon->lich_hen_id }}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <span class="label">Ghi chú/Note:</span>
                    {{ $hoaDon->ghi_chu ?? '...' }}
                </td>
            </tr>
        </table>
    </div>

    {{-- II. CHI TIẾT DỊCH VỤ --}}
    <div style="margin-bottom: 5px; font-weight: bold;">1. CHI TIẾT DỊCH VỤ / SERVICE DETAILS:</div>
    <table class="data-table" style="margin-bottom: 15px;">
        <thead>
            <tr>
                <th width="10%">STT</th>
                <th width="60%">Nội dung / Description</th>
                <th width="30%">Thành tiền / Amount (VNĐ)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1</td>
                <td>
                    <strong>Phí khám chữa bệnh</strong><br>
                    <small><i>(Dịch vụ theo lịch hẹn #{{ $hoaDon->lich_hen_id }})</i></small>
                </td>
                <td class="text-right font-bold">
                    {{ number_format($hoaDon->tong_tien, 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>

    {{-- 1.a CHI TIẾT ĐƠN THUỐC (nếu có) --}}
    @if (optional($hoaDon->donThuoc)->items && optional($hoaDon->donThuoc)->items->count())
        <div style="margin-bottom: 5px; font-weight: bold;">1.a CHI TIẾT ĐƠN THUỐC / PRESCRIPTION ITEMS</div>
        <table class="data-table" style="margin-bottom: 15px;">
            <thead>
                <tr>
                    <th width="10%">STT</th>
                    <th width="60%">Tên thuốc / Description</th>
                    <th width="30%">Thành tiền / Amount (VNĐ)</th>
                </tr>
            </thead>
            <tbody>
                @foreach (optional($hoaDon->donThuoc)->items as $idx => $item)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td>
                            <strong>{{ optional($item->thuoc)->ten ?? ($item->ten ?? '---') }}</strong><br>
                            <small><i>Liều: {{ $item->lieu_dung ?? '-' }} - Cách dùng: {{ $item->cach_dung ?? '-' }} -
                                    Số lượng: {{ $item->so_luong ?? '-' }}</i></small>
                        </td>
                        <td class="text-right font-bold">
                            @php
                                $linePrice = null;
                                if (isset($item->gia) && $item->gia) {
                                    $linePrice = $item->gia * ($item->so_luong ?? 1);
                                } elseif (optional($item->thuoc)->gia_tham_khao) {
                                    $linePrice = optional($item->thuoc)->gia_tham_khao * ($item->so_luong ?? 1);
                                }
                            @endphp
                            @if ($linePrice)
                                {{ number_format($linePrice, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- III. LỊCH SỬ THANH TOÁN (Logic chuẩn từ show.blade.php) --}}
    <div style="margin-bottom: 5px; font-weight: bold;">2. LỊCH SỬ THANH TOÁN / PAYMENT LOGS:</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="25%">Thời gian/Time</th>
                <th width="20%">Provider</th>
                <th width="30%">Mã GD/Ref</th>
                <th width="25%">Số tiền/Amount</th>
            </tr>
        </thead>
        <tbody>
            {{-- Vòng lặp lấy lịch sử thanh toán y hệt trên Web --}}
            @forelse($hoaDon->thanhToans as $tt)
                <tr>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($tt->paid_at ?? $tt->created_at)->format('H:i d/m/Y') }}
                    </td>
                    <td class="text-center">
                        <span style="text-transform: uppercase;">{{ $tt->provider }}</span>
                    </td>
                    <td class="text-center">
                        {{ $tt->transaction_ref ?? '-' }}
                    </td>
                    <td class="text-right font-bold text-success">
                        {{ number_format($tt->so_tien, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center" style="font-style: italic; color: #777;">
                        Chưa có dữ liệu thanh toán
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            {{-- Tính toán tổng tiền đã trả và còn lại --}}
            @php
                $daThanhToan = $hoaDon->thanhToans->sum('so_tien');
                $conLai = $hoaDon->tong_tien - $daThanhToan;
            @endphp
            <tr>
                <td colspan="3" class="text-right font-bold" style="border:none;">TỔNG ĐÃ THANH TOÁN:</td>
                <td class="text-right font-bold" style="font-size: 11pt; border: 1px solid #000;">
                    {{ number_format($daThanhToan, 0, ',', '.') }} đ
                </td>
            </tr>
            @if ($conLai > 0)
                <tr>
                    <td colspan="3" class="text-right font-bold" style="border:none;">CÒN LẠI / BALANCE:</td>
                    <td class="text-right font-bold text-danger" style="border: 1px solid #000;">
                        {{ number_format($conLai, 0, ',', '.') }} đ
                    </td>
                </tr>
            @endif
        </tfoot>
    </table>

    <div style="margin-top: 10px; font-style: italic; font-size: 9pt;">
        Số tiền (bằng số): <strong>{{ number_format($hoaDon->tong_tien, 0, ',', '.') }} đ</strong><br>
        Số tiền (bằng chữ):
        <strong>{{ function_exists('format_money_in_words') ? format_money_in_words($hoaDon->tong_tien) : '' }}</strong>
    </div>

    {{-- FOOTER CHỮ KÝ --}}
    <div style="margin-top: 30px;">
        <table style="text-align: center;">
            <tr>
                <td width="33%">
                    <div class="font-bold">NGƯỜI NỘP TIỀN</div>
                    <div style="font-size: 8pt;">(Payer)</div>
                    <div style="height: 60px;"></div>
                    <strong>{{ optional($hoaDon->user)->name }}</strong>
                </td>
                <td width="33%">
                    <div class="font-bold">KẾ TOÁN / THU NGÂN</div>
                    <div style="font-size: 8pt;">(Cashier)</div>
                    <div style="height: 60px;"></div>
                    <div style="font-size: 9pt;">(Ký, đóng dấu)</div>
                </td>
                <td width="33%">
                    <div style="font-size: 9pt; font-style: italic;">
                        Ngày: {{ optional($hoaDon->updated_at ?? $hoaDon->created_at)->format('d/m/Y') }}
                    </div>
                    <div class="font-bold">GIÁM ĐỐC BỆNH VIỆN</div>
                    <div style="font-size: 8pt;">(Director)</div>
                </td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 30px; border-top: 1px dashed #777; padding-top: 5px; font-size: 8pt; text-align: center;">
        <i>Hóa đơn điện tử được khởi tạo từ hệ thống. Giá trị pháp lý tương đương hóa đơn giấy.</i>
    </div>

</body>

</html>
