<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class XetNghiem extends Model
{
    protected $fillable = ['benh_an_id','user_id','bac_si_id','loai','file_path','disk','mo_ta'];

    public function benhAn(){ return $this->belongsTo(BenhAn::class); }
    
    // Accessor để lấy tên disk (fallback về public nếu null)
    public function getDiskNameAttribute()
    {
        return $this->disk ?? 'public';
    }
}