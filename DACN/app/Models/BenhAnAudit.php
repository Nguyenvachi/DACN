<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BenhAnAudit extends Model
{
    protected $fillable = [
        'benh_an_id',
        'user_id',
        'action',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    // Quan hệ: audit log thuộc về 1 bệnh án
    public function benhAn()
    {
        return $this->belongsTo(BenhAn::class, 'benh_an_id');
    }

    // Quan hệ: audit log do 1 user thực hiện
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Helper: lấy label action
    public function getActionLabelAttribute(): string
    {
        return match($this->action) {
            'created' => 'Tạo mới',
            'updated' => 'Cập nhật',
            'deleted' => 'Xóa',
            // BỔ SUNG: Các action đặc biệt
            'prescription_created' => 'Kê đơn thuốc',
            'test_uploaded' => 'Upload xét nghiệm',
            'files_uploaded' => 'Upload tệp',
            'file_deleted' => 'Xóa tệp',
            'pregnancy_tracking_created' => 'Tạo theo dõi thai kỳ',
            'pregnancy_tracking_updated' => 'Cập nhật theo dõi thai kỳ',
            'pregnancy_tracking_status_changed' => 'Đổi trạng thái theo dõi thai kỳ',
            'pregnancy_tracking_downloaded' => 'Tải tệp theo dõi thai kỳ',
            default => ucfirst(str_replace('_', ' ', $this->action)),
        };
    }
}
