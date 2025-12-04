<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug',
    ];

    public function baiViets()
    {
        return $this->belongsToMany(BaiViet::class, 'bai_viet_tag');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
