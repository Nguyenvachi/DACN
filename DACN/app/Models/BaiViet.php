<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaiViet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'danh_muc_id', 'title', 'slug', 'excerpt', 'content',
        'status', 'published_at', 'meta_title', 'meta_description', 'thumbnail',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function danhMuc()
    {
        return $this->belongsTo(DanhMuc::class, 'danh_muc_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'bai_viet_tag');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where(function ($q) { $q->whereNull('published_at')->orWhere('published_at', '<=', now()); });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
