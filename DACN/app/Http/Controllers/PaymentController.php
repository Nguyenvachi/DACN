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
        $vnp_Amount = $request->input('amount') * 100; // Số tiền thanh toán, nhân 100 theo quy định của VNPay
        $vnp_TxnRef = $request->input('hoa_don_id'); // Lấy mã hóa đơn từ form
        $vnp_OrderInfo = "Thanh toan hoa don #" . $vnp_TxnRef;
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
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => config('vnpay.return_url'),
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        // THÊM: Log payment request
        PaymentLog::logRequest('vnpay', $vnp_TxnRef, $inputData);

        // SỬA: Dùng PaymentSignatureService để tạo URL
        $vnp_Url = PaymentSignatureService::buildVnpayUrl($inputData);

        return redirect($vnp_Url);
    }

    /**
     * Xử lý kết quả VNPay trả về (Return URL).
     * CẬP NHẬT: Bây giờ sẽ cập nhật database luôn tại đây
     */
    public function vnpayReturn(Request $request)
    {
        $inputData = $request->all();
        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';

        // THÊM: Log return callback
        PaymentLog::logReturn('vnpay', $inputData, $inputData['vnp_TxnRef'] ?? null);

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

        // Tìm hóa đơn
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
                // và để model tự động cập nhật `trang_thai` thông qua `updatePaymentStatus`
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
            // THÊM: Check role để redirect đúng route
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
        PaymentLog::logIPN('vnpay', $inputData, $inputData['vnp_TxnRef'] ?? null);

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
        $partnerCode = config('momo.partner_code');
        $accessKey = config('momo.access_key');
        $secretKey = config('momo.secret_key');
        $endpoint = config('momo.endpoint');
        $returnUrl = config('momo.return_url');
        $notifyUrl = config('momo.ipn_url');

        // Lấy ID hóa đơn gốc
        $hoaDonIdGoc = $request->input('hoa_don_id');

        // SỬA LỖI 1: Ép kiểu amount về (string) của (int)
        $amount = (string)(int)$request->input('amount');

        // SỬA LỖI 2: Đã sửa ở lần trước (thêm time())
        $orderId = $hoaDonIdGoc . '_' . time();
        $requestId = time() . "";

        $orderInfo = "Thanh toán hóa đơn #" . $hoaDonIdGoc;
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
        PaymentLog::logRequest('momo', $hoaDonIdGoc, $data);

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
            return redirect()->route('admin.hoadon.show', $hoaDonIdGoc) // <-- Sửa ở đây
                ->with('error', 'Lỗi MoMo: ' . $errorMessage);
        }
    }

    /**
     * Xử lý kết quả MoMo trả về
     */
    public function momoReturn(Request $request)
    {
        $data = $request->all();
        $signature = $data['signature'] ?? '';
        $amount = (string)(int)($data['amount'] ?? 0);
        $data['amount'] = $amount; // Đảm bảo amount là string

        // THÊM: Log return callback
        $orderId = $data['orderId'] ?? '';
        $hoaDonId = strpos((string)$orderId, '_') !== false
            ? (int)explode('_', (string)$orderId)[0]
            : $orderId;
        PaymentLog::logReturn('momo', $data, $hoaDonId);

        // SỬA: Dùng PaymentSignatureService để verify
        if (!PaymentSignatureService::verifyMomoSignature($data, $signature)) {
            return redirect()
                ->route('admin.hoadon.index')
                ->with('error', 'Chữ ký không hợp lệ');
        }

        $resultCode = $data['resultCode'] ?? '';
        $transId = $data['transId'] ?? null;

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
            $hoaDonId = strpos((string)$orderId, '_') !== false
                ? (int)explode('_', (string)$orderId)[0]
                : $orderId;
            PaymentLog::logIPN('momo', $data, $hoaDonId);

            // SỬA: Dùng PaymentSignatureService để verify
            if (!PaymentSignatureService::verifyMomoSignature($data, $signature)) {
                return response()->json(['resultCode' => 9403, 'message' => 'Invalid signature'], 200);
            }

            $amount = (int)($data['amount'] ?? 0);
            $resultCode = (string)($data['resultCode'] ?? '');
            $transId = $data['transId'] ?? null;

            $hoaDon = HoaDon::find($orderId);
            if (! $hoaDon && strpos((string)$orderId, '_') !== false) {
                $hoaDonIdGoc = (int) explode('_', (string)$orderId)[0];
                $hoaDon = HoaDon::find($hoaDonIdGoc);
            }

            if (! $hoaDon) {
                return response()->json(['resultCode' => 1, 'message' => 'Order not found'], 200);
            }

            if ((int)$hoaDon->tong_tien !== $amount) {
                return response()->json(['resultCode' => 2, 'message' => 'Invalid amount'], 200);
            }

            // THÊM: Idempotency check - tránh xử lý duplicate IPN
            $idempotencyKey = $hoaDon->id . '_' . $transId;
            $existingPayment = ThanhToan::where('idempotency_key', $idempotencyKey)->first();

            if ($existingPayment) {
                // Đã xử lý rồi, trả về success
                return response()->json(['resultCode' => 0, 'message' => 'Order already confirmed'], 200);
            }

            if ($hoaDon->trang_thai === \App\Models\HoaDon::STATUS_PAID_VN) {
                return response()->json(['resultCode' => 0, 'message' => 'Order already confirmed'], 200);
            }

            if ($resultCode === '0') {
                $hoaDon->trang_thai = \App\Models\HoaDon::STATUS_PAID_VN;
                $hoaDon->phuong_thuc = 'MOMO';
                $hoaDon->save();

                $hoaDon->thanhToans()->create([
                    'provider' => 'MOMO',
                    'so_tien' => $amount,
                    'trang_thai' => 'Thành công',
                    'transaction_ref' => $transId,
                    'idempotency_key' => $idempotencyKey,
                    'paid_at' => now(),
                    'payload' => $data,
                ]);

                // Bổ sung: cập nhật lại số tiền đã thanh toán và trạng thái hóa đơn
                $hoaDon->recalculatePaidAmount();

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

                return response()->json(['resultCode' => 0, 'message' => 'Confirm Success'], 200);
            }

            return response()->json(['resultCode' => 3, 'message' => 'Payment failed'], 200);
        } catch (\Throwable $e) {
            Log::error('MoMo IPN Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['resultCode' => 99, 'message' => 'Unknown error'], 200);
        }
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
