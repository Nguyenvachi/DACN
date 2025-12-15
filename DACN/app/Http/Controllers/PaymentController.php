<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\HoaDonDaThanhToan;
use App\Models\HoaDon;
use App\Models\PaymentLog;
use App\Models\ThanhToan;
use App\Services\PaymentSignatureService;

class PaymentController extends Controller
{
    /**
     * Tạo URL thanh toán và chuyển hướng người dùng.
     */
    public function createVnpayPayment(Request $request)
    {
        $hoaDonId = $request->input('hoa_don_id');
        $type = $request->input('type', 'invoice'); // 'invoice' hoặc 'shop'

        // Xử lý cho shop
        if ($type === 'shop' && str_starts_with($hoaDonId, 'shop_')) {
            $donHangId = str_replace('shop_', '', $hoaDonId);
            $donHang = \App\Models\DonHang::find($donHangId);
            if (!$donHang) {
                return redirect()->back()->with('error', 'Đơn hàng không tồn tại');
            }
            $amount = $donHang->thanh_toan;
            $orderInfo = "Thanh toan don hang #" . $donHangId;
        } else {
            // Xử lý cho hóa đơn như cũ
            $amount = $request->input('amount');
            $orderInfo = "Thanh toan hoa don #" . $hoaDonId;
        }

        $vnp_Amount = $amount * 100; // Số tiền thanh toán, nhân 100 theo quy định của VNPay
        $vnp_TxnRef = $hoaDonId; // Lấy mã hóa đơn từ form
        $vnp_OrderType = 'billpayment';
        $vnp_Locale = 'vn';
        $vnp_IpAddr = $request->ip();

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => config('vnpay.tmn_code'),
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $orderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => config('vnpay.return_url'),
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        // THÊM: Log payment request
        if (str_starts_with($hoaDonId, 'shop_')) {
            $donHangId = str_replace('shop_', '', $hoaDonId);
            PaymentLog::logRequest('vnpay', $donHangId, $inputData, \App\Models\DonHang::class);
        } else {
            PaymentLog::logRequest('vnpay', (int)$hoaDonId, $inputData);
        }

        // SỬA: Dùng PaymentSignatureService để tạo URL
        $vnp_Url = PaymentSignatureService::buildVnpayUrl($inputData);

        return redirect($vnp_Url);
    }

    /**
     * Xử lý kết quả VNPay trả về (Return URL).
     */
    public function vnpayReturn(Request $request)
    {
        $inputData = $request->all();
        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';

        // THÊM: Log return callback
        $txnRef = $inputData['vnp_TxnRef'] ?? null;
        if ($txnRef && str_starts_with($txnRef, 'shop_')) {
            $donHangId = str_replace('shop_', '', $txnRef);
            PaymentLog::logReturn('vnpay', $inputData, $donHangId, \App\Models\DonHang::class);
        } else {
            PaymentLog::logReturn('vnpay', $inputData, (int)$txnRef);
        }

        // SỬA: Dùng PaymentSignatureService để verify
        if (!PaymentSignatureService::verifyVnpaySignature($inputData, $vnp_SecureHash)) {
            return redirect()
                ->route('admin.hoadon.index')
                ->with('error', 'Chữ ký không hợp lệ');
        }

        $hoaDonId = $request->input('vnp_TxnRef');
        $amount = $request->input('vnp_Amount') / 100;
        $responseCode = $request->input('vnp_ResponseCode');
        $transactionRef = $request->input('vnp_BankTranNo');

        // Xử lý thanh toán cho shop
        if (str_starts_with($hoaDonId, 'shop_')) {
            $donHangId = str_replace('shop_', '', $hoaDonId);
            $donHang = \App\Models\DonHang::find($donHangId);

            if ($donHang && $responseCode == '00') {
                // Cập nhật trạng thái đơn hàng
                $donHang->update([
                    'trang_thai_thanh_toan' => 'Đã thanh toán',
                    'phuong_thuc_thanh_toan' => 'VNPAY',
                    'thanh_toan_at' => now(),
                ]);

                // Ghi nhận thanh toán & tạo hóa đơn admin cho đơn hàng
                $this->createInvoiceForDonHang($donHang, 'VNPAY', $amount, $transactionRef, $hoaDonId . '_' . $transactionRef);

                return redirect()
                    ->route('patient.shop.order-success', $donHang->id)
                    ->with('success', 'Thanh toán thành công qua VNPay! Số tiền: ' . number_format($amount) . ' VNĐ');
            } else {
                return redirect()
                    ->route('patient.shop.orders')
                    ->with('error', 'Giao dịch không thành công. Mã lỗi: ' . $responseCode);
            }
        }

        // Xử lý thanh toán cho hóa đơn như cũ
        $hoaDon = HoaDon::find($hoaDonId);

        if ($hoaDon && $responseCode == '00') {
            // Kiểm tra xem đã thanh toán chưa
            if ($hoaDon->trang_thai != \App\Models\HoaDon::STATUS_PAID_VN) {
                // Cập nhật trạng thái
                $hoaDon->trang_thai = \App\Models\HoaDon::STATUS_PAID_VN;
                $hoaDon->phuong_thuc = 'VNPAY';
                $hoaDon->save();

                // Ghi nhận vào bảng thanh_toans
                $hoaDon->thanhToans()->create([
                    'provider' => 'VNPAY',
                    'so_tien' => $amount,
                    'trang_thai' => 'Thành công',
                    'transaction_ref' => $transactionRef,
                    'idempotency_key' => $hoaDonId . '_' . $transactionRef,
                    'paid_at' => now(),
                ]);

                // Bổ sung: cập nhật lại tổng số tiền đã thanh toán trên hóa đơn
                $hoaDon->recalculatePaidAmount();

                // THÊM: đồng bộ sang Lịch hẹn + gửi email biên lai
                if ($hoaDon->lichHen) {
                    $lh = $hoaDon->lichHen;
                    $lh->payment_status = \App\Models\HoaDon::STATUS_PAID_VN;
                    $lh->payment_method = 'VNPAY';
                    $lh->paid_at = now();
                    $lh->save();
                }
                if ($hoaDon->user && $hoaDon->user->email) {
                    Mail::to($hoaDon->user->email)->queue(new HoaDonDaThanhToan($hoaDon));
                }
            }

            // Hiển thị thông báo thành công
            $user = $hoaDon->user;
            if ($user && $user->isPatient()) {
                return redirect()
                    ->route('lichhen.my')
                    ->with('success', 'Thanh toán thành công qua VNPay! Số tiền: ' . number_format($amount) . ' VNĐ');
            }

            return redirect()
                ->route('admin.hoadon.show', $hoaDon)
                ->with('success', 'Thanh toán thành công qua VNPay! Số tiền: ' . number_format($amount) . ' VNĐ');
        } else {
            // Giao dịch thất bại
            return redirect()
                ->route('admin.hoadon.index')
                ->with('error', 'Giao dịch không thành công. Mã lỗi: ' . $responseCode);
        }
    }

    /**
     * Xử lý thông báo từ VNPay (IPN URL).
     * Đây là nơi cập nhật trạng thái đơn hàng trong DB.
     */
    public function vnpayIpn(Request $request)
    {
        $inputData = $request->all();
        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';

        // THÊM: Log IPN callback
        $txnRef = $inputData['vnp_TxnRef'] ?? null;
        if ($txnRef && str_starts_with($txnRef, 'shop_')) {
            $donHangId = str_replace('shop_', '', $txnRef);
            PaymentLog::logIPN('vnpay', $inputData, $donHangId, \App\Models\DonHang::class);
        } else {
            PaymentLog::logIPN('vnpay', $inputData, (int)$txnRef);
        }

        try {
            // Kiểm tra chữ ký - SỬA: Dùng PaymentSignatureService
            if (!PaymentSignatureService::verifyVnpaySignature($inputData, $vnp_SecureHash)) {
                return response()->json(['RspCode' => '97', 'Message' => 'Invalid Signature']);
            }

            $hoaDonId = $inputData['vnp_TxnRef'];
            $amount = $inputData['vnp_Amount'] / 100;
            $responseCode = $inputData['vnp_ResponseCode'];
            $transactionRef = $inputData['vnp_BankTranNo']; // Mã giao dịch từ VNPay

            // Tìm hóa đơn trong DB
            $hoaDon = HoaDon::find($hoaDonId);

            if (!$hoaDon) {
                return response()->json(['RspCode' => '01', 'Message' => 'Order not found']);
            }

            // Kiểm tra số tiền
            if ($hoaDon->tong_tien != $amount) {
                return response()->json(['RspCode' => '04', 'Message' => 'Invalid amount']);
            }

            // THÊM: Idempotency check - tránh xử lý duplicate IPN
            $idempotencyKey = $hoaDonId . '_' . $transactionRef;
            $existingPayment = ThanhToan::where('idempotency_key', $idempotencyKey)->first();

            if ($existingPayment) {
                // Đã xử lý rồi, trả về success
                return response()->json(['RspCode' => '00', 'Message' => 'Order already confirmed']);
            }

            // Kiểm tra xem hóa đơn đã được thanh toán chưa
            if ($hoaDon->trang_thai != \App\Models\HoaDon::STATUS_PAID_VN) {
                if ($responseCode == '00') {
                    // Giao dịch thành công
                    // Cập nhật trạng thái hóa đơn
                    $hoaDon->trang_thai = \App\Models\HoaDon::STATUS_PAID_VN;
                    $hoaDon->phuong_thuc = 'VNPAY';
                    $hoaDon->save();

                    // Ghi nhận vào bảng thanh_toans
                    $hoaDon->thanhToans()->create([
                        'provider' => 'VNPAY',
                        'so_tien' => $amount,
                        'trang_thai' => 'Thành công',
                        'transaction_ref' => $transactionRef,
                        'idempotency_key' => $idempotencyKey,
                        'paid_at' => now(),
                        'payload' => $inputData,
                    ]);

                    // Bổ sung: cập nhật lại số tiền đã thanh toán -> đảm bảo trạng thái chính xác
                    $hoaDon->recalculatePaidAmount();

                    // THÊM: đồng bộ sang Lịch hẹn + gửi email biên lai
                    if ($hoaDon->lichHen) {
                        $lh = $hoaDon->lichHen;
                        $lh->payment_status = \App\Models\HoaDon::STATUS_PAID_VN;
                        $lh->payment_method = 'VNPAY';
                        $lh->paid_at = now();
                        $lh->save();
                    }
                    if ($hoaDon->user && $hoaDon->user->email) {
                        Mail::to($hoaDon->user->email)->queue(new HoaDonDaThanhToan($hoaDon));
                    }
                }
                // Phản hồi lại cho VNPay là đã xử lý thành công
                return response()->json(['RspCode' => '00', 'Message' => 'Confirm Success']);
            } else {
                // Hóa đơn đã được xác nhận thanh toán trước đó
                return response()->json(['RspCode' => '02', 'Message' => 'Order already confirmed']);
            }
        } catch (\Exception $e) {
            Log::error('VNPay IPN Error: ' . $e->getMessage());
            return response()->json(['RspCode' => '99', 'Message' => 'Unknown error']);
        }
    }

    /**
     * Tạo thanh toán MoMo
     */
    public function createMomoPayment(Request $request)
    {
        \Log::info('MoMo create called', $request->all());
        $partnerCode = config('momo.partner_code');
        $accessKey = config('momo.access_key');
        $secretKey = config('momo.secret_key');
        $endpoint = config('momo.endpoint');
        $returnUrl = config('momo.return_url');
        $notifyUrl = config('momo.ipn_url');

        // Lấy ID hóa đơn gốc
        $hoaDonId = $request->input('hoa_don_id');
        $type = $request->input('type', 'invoice'); // 'invoice' hoặc 'shop'

        \Log::info('MoMo create: hoaDonId=' . $hoaDonId . ', type=' . $type);

        // Xử lý cho shop
        if ($type === 'shop' && str_starts_with($hoaDonId, 'shop_')) {
            $donHangId = str_replace('shop_', '', $hoaDonId);
            \Log::info('MoMo create: donHangId=' . $donHangId);
            $donHang = \App\Models\DonHang::find($donHangId);
            \Log::info('MoMo create: donHang=' . ($donHang ? json_encode($donHang->only(['id', 'ma_don_hang', 'trang_thai_thanh_toan'])) : 'null'));
            if (!$donHang) {
                return redirect()->back()->with('error', 'Đơn hàng không tồn tại');
            }
            $amount = (string)(int)$donHang->thanh_toan;
            $orderInfo = "Thanh toán đơn hàng #" . $donHangId;
        } else {
            // Xử lý cho hóa đơn như cũ
            $amount = (string)(int)$request->input('amount');
            $orderInfo = "Thanh toán hóa đơn #" . $hoaDonId;
        }

        // SỬA LỖI 2: Đã sửa ở lần trước (thêm time())
        $orderId = $hoaDonId . '_' . time();
        $requestId = time() . "";

        // $requestType = "captureWallet";
        $requestType = "payWithMethod"; // Dòng này sẽ hiển thị form SĐT
        $extraData = "";

        $data = array(
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            'storeId' => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $returnUrl,
            'ipnUrl' => $notifyUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
        );

        // SỬA: Dùng PaymentSignatureService để tạo signature
        $rawHash = PaymentSignatureService::buildMomoRequestHash($data);
        $data['signature'] = PaymentSignatureService::createMomoSignature($rawHash);

        // THÊM: Log payment request
        if ($type === 'shop' && str_starts_with($hoaDonId, 'shop_')) {
            $donHangId = str_replace('shop_', '', $hoaDonId);
            PaymentLog::logRequest('momo', $donHangId, $data, \App\Models\DonHang::class);
        } else {
            PaymentLog::logRequest('momo', (int)$hoaDonId, $data);
        }

        // Gửi request đến MoMo
        $result = $this->execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);

        // Log để debug
        Log::info('MoMo Request:', $data);
        Log::info('MoMo Response:', $jsonResult ? $jsonResult : ['error' => $result]);

        // Chuyển hướng đến trang thanh toán MoMo
        if (isset($jsonResult['payUrl'])) {
            return redirect($jsonResult['payUrl']);
        } else {
            $errorMessage = isset($jsonResult['message']) ? $jsonResult['message'] : 'Không thể kết nối đến MoMo';

            // SỬA LỖI 3: Chuyển hướng thất bại phải về ID gốc
            if ($type === 'shop') {
                return redirect()->route('patient.shop.checkout')->with('error', 'Lỗi MoMo: ' . $errorMessage);
            } else {
                return redirect()->route('admin.hoadon.show', $hoaDonId)->with('error', 'Lỗi MoMo: ' . $errorMessage);
            }
        }
    }

    /**
     * Xử lý kết quả MoMo trả về
     */
    public function momoReturn(Request $request)
    {
        $data = $request->all();
        \Log::info('MoMo return called', $data);
        $signature = $data['signature'] ?? '';
        $amount = (string)(int)($data['amount'] ?? 0);
        $data['amount'] = $amount; // Đảm bảo amount là string

        // THÊM: Log return callback
        $orderId = $data['orderId'] ?? '';
        // Sửa: Extract hoaDonId từ orderId dạng 'shop_123_1234567890'
        $parts = explode('_', $orderId);
        $time = array_pop($parts); // remove timestamp
        $hoaDonId = implode('_', $parts);

        if (str_starts_with($hoaDonId, 'shop_')) {
            $donHangId = str_replace('shop_', '', $hoaDonId);
            PaymentLog::logReturn('momo', $data, $donHangId, \App\Models\DonHang::class);
        } else {
            PaymentLog::logReturn('momo', $data, (int)$hoaDonId);
        }

        // SỬA: Dùng PaymentSignatureService để verify
        if (!PaymentSignatureService::verifyMomoSignature($data, $signature)) {
            return redirect()
                ->route('admin.hoadon.index')
                ->with('error', 'Chữ ký không hợp lệ');
        }

        $resultCode = $data['resultCode'] ?? '';
        $transId = $data['transId'] ?? null;

        // Xử lý thanh toán cho shop
        if (str_starts_with($hoaDonId, 'shop_')) {
            $donHangId = str_replace('shop_', '', $hoaDonId);
            $donHang = \App\Models\DonHang::find($donHangId);

            if (!$donHang) {
                return redirect()
                    ->route('patient.shop.orders')
                    ->with('error', 'Đơn hàng không tồn tại');
            }

            if ($donHang && $resultCode == '0') {
                // Cập nhật trạng thái đơn hàng
                $donHang->update([
                    'trang_thai_thanh_toan' => 'Đã thanh toán',
                    'phuong_thuc_thanh_toan' => 'MOMO',
                    'thanh_toan_at' => now(),
                ]);

                // Ghi nhận thanh toán & tạo hóa đơn admin cho đơn hàng
                list($hoaDonCreated, $thanhToan) = $this->createInvoiceForDonHang($donHang, 'MOMO', $amount, $transId, $orderId . '_' . $transId);

                return redirect()
                    ->route('patient.shop.order-success', $donHang->id)
                    ->with('success', 'Thanh toán thành công qua MoMo! Số tiền: ' . number_format($amount) . ' VNĐ');
            } else {
                return redirect()
                    ->route('patient.shop.orders')
                    ->with('error', 'Giao dịch không thành công. Mã lỗi: ' . $resultCode);
            }
        }

        // Xử lý thanh toán cho hóa đơn như cũ
        $hoaDon = HoaDon::find($orderId);
        // THÊM: fallback tách orderId dạng "{id}_{timestamp}"
        if (! $hoaDon && strpos((string)$orderId, '_') !== false) {
            $hoaDonIdGoc = (int) explode('_', (string)$orderId)[0];
            $hoaDon = HoaDon::find($hoaDonIdGoc);
        }

        if ($hoaDon && $resultCode == '0') {
            // Kiểm tra xem đã thanh toán chưa
            if ($hoaDon->trang_thai != \App\Models\HoaDon::STATUS_PAID_VN) {
                // Cập nhật trạng thái
                $hoaDon->trang_thai = \App\Models\HoaDon::STATUS_PAID_VN;
                $hoaDon->phuong_thuc = 'MOMO';
                $hoaDon->save();

                // Ghi nhận vào bảng thanh_toans
                $hoaDon->thanhToans()->create([
                    'provider' => 'MOMO',
                    'so_tien' => $amount,
                    'trang_thai' => 'Thành công',
                    'transaction_ref' => $transId,
                    'idempotency_key' => $hoaDon->id . '_' . $transId,
                    'paid_at' => now(),
                ]);

                // Bổ sung: cập nhật lại tổng số tiền đã thanh toán
                $hoaDon->recalculatePaidAmount();

                // THÊM: đồng bộ sang Lịch hẹn + gửi email biên lai
                if ($hoaDon->lichHen) {
                    $lh = $hoaDon->lichHen;
                    $lh->payment_status = \App\Models\HoaDon::STATUS_PAID_VN;
                    $lh->payment_method = 'MOMO';
                    $lh->paid_at = now();
                    $lh->save();
                }
                if ($hoaDon->user && $hoaDon->user->email) {
                    Mail::to($hoaDon->user->email)->queue(new HoaDonDaThanhToan($hoaDon));
                }
            }

            // THÊM: Check role để redirect đúng route
            $user = $hoaDon->user;
            if ($user && $user->isPatient()) {
                return redirect()
                    ->route('lichhen.my')
                    ->with('success', 'Thanh toán thành công qua MoMo! Số tiền: ' . number_format($amount) . ' VNĐ');
            }

            return redirect()
                ->route('admin.hoadon.show', $hoaDon)
                ->with('success', 'Thanh toán thành công qua MoMo! Số tiền: ' . number_format($amount) . ' VNĐ');
        } else {
            return redirect()
                ->route('admin.hoadon.index')
                ->with('error', 'Giao dịch không thành công. Mã lỗi: ' . $resultCode);
        }
    }

    /**
     * Xử lý IPN từ MoMo
     */
    public function momoIpn(Request $request)
    {
        try {
            $data = $request->all();
            $signature = $data['signature'] ?? '';

            // Đảm bảo amount là string
            $data['amount'] = (string)(int)($data['amount'] ?? 0);

            // THÊM: Log IPN callback
            $orderId = $data['orderId'] ?? '';
            // Sửa: Extract hoaDonId từ orderId dạng 'shop_123_1234567890'
            $parts = explode('_', $orderId);
            $time = array_pop($parts); // remove timestamp
            $hoaDonId = implode('_', $parts);

            if (str_starts_with($hoaDonId, 'shop_')) {
                $donHangId = str_replace('shop_', '', $hoaDonId);
                PaymentLog::logIPN('momo', $data, $donHangId, \App\Models\DonHang::class);
            } else {
                PaymentLog::logIPN('momo', $data, (int)$hoaDonId);
            }

            // SỬA: Dùng PaymentSignatureService để verify
            if (!PaymentSignatureService::verifyMomoSignature($data, $signature)) {
                return response()->json(['resultCode' => 9403, 'message' => 'Invalid signature'], 200);
            }

            $amount = (int)($data['amount'] ?? 0);
            $resultCode = (string)($data['resultCode'] ?? '');
            $transId = $data['transId'] ?? null;

            // Xử lý cho shop
            if (str_starts_with($hoaDonId, 'shop_')) {
                $donHangId = str_replace('shop_', '', $hoaDonId);
                $donHang = \App\Models\DonHang::find($donHangId);

                if (!$donHang) {
                    return response()->json(['resultCode' => 1, 'message' => 'Order not found'], 200);
                }

                if (!$donHang) {
                    return response()->json(['resultCode' => 1, 'message' => 'Order not found'], 200);
                }

                if ((int)$donHang->thanh_toan !== $amount) {
                    return response()->json(['resultCode' => 2, 'message' => 'Invalid amount'], 200);
                }

                // THÊM: Idempotency check
                $idempotencyKey = $orderId . '_' . $transId;
                $existingPayment = \App\Models\ThanhToan::where('idempotency_key', $idempotencyKey)->first();

                if ($existingPayment) {
                    return response()->json(['resultCode' => 0, 'message' => 'Order already confirmed'], 200);
                }

                if ($donHang->trang_thai_thanh_toan === 'Đã thanh toán') {
                    return response()->json(['resultCode' => 0, 'message' => 'Order already confirmed'], 200);
                }

                if ($resultCode === '0') {
                    $donHang->update([
                        'trang_thai_thanh_toan' => 'Đã thanh toán',
                        'phuong_thuc_thanh_toan' => 'MOMO',
                        'thanh_toan_at' => now(),
                    ]);

                    list($hoaDonCreated, $thanhToan) = $this->createInvoiceForDonHang($donHang, 'MOMO', $amount, $transId, $idempotencyKey, $data);

                    return response()->json(['resultCode' => 0, 'message' => 'Confirm Success'], 200);
                }

                return response()->json(['resultCode' => 3, 'message' => 'Payment failed'], 200);
            }

            // Xử lý cho hóa đơn
            $hoaDon = HoaDon::find($hoaDonId);
            if (! $hoaDon && strpos((string)$orderId, '_') !== false) {
                $hoaDonIdGoc = (int) explode('_', (string)$orderId)[0];
                $hoaDon = HoaDon::find($hoaDonIdGoc);
            }
        } catch (\Throwable $e) {
            Log::error('MoMo IPN Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['resultCode' => 99, 'message' => 'Unknown error'], 200);
        }
    }

    /**
     * Helper: create a corresponding HoaDon for a DonHang and record ThanhToan
     */
    private function createInvoiceForDonHang(\App\Models\DonHang $donHang, string $provider, $amount, $transactionRef = null, $idempotencyKey = null, $payload = null)
    {
        // Try to find existing invoice created for this order
        $hoaDon = \App\Models\HoaDon::where('ghi_chu', 'like', '%DonHang#' . $donHang->id . "%")->first();

        if (! $hoaDon) {
            $hoaDon = \App\Models\HoaDon::create([
                'user_id' => $donHang->user_id,
                'tong_tien' => $donHang->thanh_toan,
                'trang_thai' => \App\Models\HoaDon::STATUS_UNPAID_VN,
                'phuong_thuc' => $provider,
                'ghi_chu' => 'DonHang#' . $donHang->id,
                'lich_hen_id' => null,
            ]);
        }

        $thanhToan = \App\Models\ThanhToan::create([
            'hoa_don_id' => $hoaDon->id,
            'payable_type' => \App\Models\DonHang::class,
            'payable_id' => $donHang->id,
            'provider' => $provider,
            'so_tien' => $amount,
            'trang_thai' => 'Thành công',
            'transaction_ref' => $transactionRef,
            'idempotency_key' => $idempotencyKey,
            'paid_at' => now(),
            'payload' => $payload,
        ]);

        // Recalculate invoice payment totals/status
        $hoaDon->recalculatePaidAmount();

        return [$hoaDon, $thanhToan];
    }

    /**
     * Hàm helper để gửi POST request
     */
    private function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
