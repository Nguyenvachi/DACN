<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoaDon extends Model
{
    // Trạng thái hiển thị (tiếng Việt)
    public const STATUS_PAID_VN = 'Đã thanh toán';
    public const STATUS_UNPAID_VN = 'Chưa thanh toán';
    public const STATUS_REFUNDED_VN = 'Hoàn tiền';
    public const STATUS_PARTIAL_REFUND_VN = 'Hoàn một phần';
    public const STATUS_PARTIAL_VN = 'Thanh toán một phần';
    public const STATUS_CANCELLED_VN = 'Đã hủy';
    protected $fillable = [
        'lich_hen_id',
        'user_id',
        'ma_hoa_don',
        'tong_tien',
        'so_tien_da_thanh_toan',
        'so_tien_con_lai',
        'so_tien_da_hoan',
        'trang_thai',
        'status',
        'phuong_thuc',
        'ghi_chu',
    ];

    protected $casts = [
        'tong_tien' => 'decimal:2',
        'so_tien_da_thanh_toan' => 'decimal:2',
        'so_tien_con_lai' => 'decimal:2',
        'so_tien_da_hoan' => 'decimal:2',
    ];

    /**
     * Boot model events
     */
    protected static function booted()
    {
        // Tự động tạo mã hóa đơn khi tạo mới
        static::creating(function ($hoaDon) {
            if (empty($hoaDon->ma_hoa_don)) {
                $hoaDon->ma_hoa_don = self::generateInvoiceNumber();
            }
            // Khởi tạo số tiền còn lại = tổng tiền
            if (!isset($hoaDon->so_tien_con_lai)) {
                $hoaDon->so_tien_con_lai = $hoaDon->tong_tien;
            }
        });

        // Tự động cập nhật status và số tiền khi lưu
        static::saving(function ($hoaDon) {
            $hoaDon->updatePaymentStatus();
        });
    }

    /**
     * Generate mã hóa đơn chuẩn quốc tế: INV-YYYYMMDD-XXXXX
     */
    public static function generateInvoiceNumber(): string
    {
        $date = now()->format('Ymd');
        $lastInvoice = self::where('ma_hoa_don', 'like', "INV-{$date}-%")
            ->orderByDesc('id')
            ->first();

        if ($lastInvoice && preg_match('/INV-\d{8}-(\d{5})/', $lastInvoice->ma_hoa_don, $matches)) {
            $sequence = intval($matches[1]) + 1;
        } else {
            $sequence = 1;
        }

        return sprintf('INV-%s-%05d', $date, $sequence);
    }

    /**
     * Cập nhật trạng thái thanh toán dựa trên số tiền
     * Logic: Số tiền thực = Đã thanh toán - Đã hoàn
     */
    public function updatePaymentStatus(): void
    {
        $tongTien = floatval($this->tong_tien);
        $daThanh = floatval($this->so_tien_da_thanh_toan);
        $daHoan = floatval($this->so_tien_da_hoan);

        // Số tiền thực tế còn lại (đã thanh toán - đã hoàn)
        $tienThuc = $daThanh - $daHoan;

        // Tính số tiền còn phải thanh toán (không âm)
        $this->so_tien_con_lai = max(0, $tongTien - $tienThuc);

        // Xác định status dựa trên số tiền thực
        if ($daHoan >= $daThanh && $daHoan > 0) {
            $this->status = 'refunded'; // Đã hoàn toàn bộ
            $this->trang_thai = self::STATUS_REFUNDED_VN;
        } elseif ($daHoan > 0 && $tienThuc > 0) {
            $this->status = 'partial_refund'; // Hoàn một phần, còn tiền thực
            $this->trang_thai = self::STATUS_PARTIAL_REFUND_VN;
        } elseif ($tienThuc >= $tongTien && $tongTien > 0) {
            $this->status = 'paid'; // Đã thanh toán đủ
            $this->trang_thai = self::STATUS_PAID_VN;
        } elseif ($tienThuc > 0 && $tienThuc < $tongTien) {
            $this->status = 'partial'; // Thanh toán một phần
            $this->trang_thai = self::STATUS_PARTIAL_VN;
        } else {
            $this->status = 'unpaid'; // Chưa thanh toán
            $this->trang_thai = self::STATUS_UNPAID_VN;
        }
    }

    /**
     * Tính lại số tiền đã thanh toán từ các bản ghi thanh toán
     */
    public function recalculatePaidAmount(): void
    {
        $this->so_tien_da_thanh_toan = $this->thanhToans()
            ->whereIn('trang_thai', ['succeeded', 'Thành công'])
            ->sum('so_tien');

        $this->so_tien_da_hoan = $this->hoanTiens()
            ->whereIn('trang_thai', ['Đã hoàn', 'Hoàn thành'])
            ->sum('so_tien');

        $this->save();
    }

    public function lichHen()
    {
        return $this->belongsTo(LichHen::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function thanhToans()
    {
        return $this->hasMany(ThanhToan::class);
    }

    public function paymentLogs()
    {
        return $this->hasMany(\App\Models\PaymentLog::class);
    }

    public function hoanTiens()
    {
        return $this->hasMany(\App\Models\HoanTien::class);
    }

    public function chiTiets()
    {
        return $this->hasMany(HoaDonChiTiet::class);
    }
}
