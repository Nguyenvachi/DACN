<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BenhAnFile extends Model
{
    // BỔ SUNG: thêm 'disk' vào fillable
    protected $fillable = ['benh_an_id', 'ten_file', 'path', 'loai_mime', 'size_bytes', 'uploaded_by', 'disk'];

    public function benhAn()
    {
        return $this->belongsTo(BenhAn::class);
    }
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // BỔ SUNG: helper để lấy disk name (fallback public nếu null)
    public function getDiskNameAttribute(): string
    {
        return $this->disk ?? 'public';
    }

    // GIỮ NGUYÊN: getUrlAttribute cho file cũ (public disk)
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }
}
