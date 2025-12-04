<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DanhMuc extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'meta_title', 'meta_description', 'description',
    ];

    public function baiViets()
    {
        return $this->hasMany(BaiViet::class, 'danh_muc_id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
