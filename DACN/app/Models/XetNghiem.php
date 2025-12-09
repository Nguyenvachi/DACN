<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class XetNghiem extends Model
{
    protected $fillable = ['benh_an_id','user_id','bac_si_id','loai','file_path','disk','mo_ta','trang_thai'];

    // Trạng thái xét nghiệm
    const STATUS_PENDING = 'pending';       // Chờ thực hiện
    const STATUS_PROCESSING = 'processing'; // Đang xử lý
    const STATUS_COMPLETED = 'completed';   // Đã có kết quả

    /**
     * Lấy tên trạng thái tiếng Việt
     */
    public function getTrangThaiTextAttribute()
    {
        return match($this->trang_thai) {
            'pending' => 'Chờ thực hiện',
            'processing' => 'Đang xử lý',
            'completed' => 'Đã có kết quả',
            default => $this->trang_thai,
        };
    }

    public function benhAn(){ return $this->belongsTo(BenhAn::class); }

    public function bacSi(){ return $this->belongsTo(BacSi::class, 'bac_si_id'); }

    public function user(){ return $this->belongsTo(User::class); }

    // Accessor để lấy tên disk (fallback về public nếu null)
    public function getDiskNameAttribute()
    {
        return $this->disk ?? 'public';
    }
}
