<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoanTien extends Model
{
    protected $fillable = [
        'hoa_don_id',
        'so_tien',
        'ly_do',
        'trang_thai',
        'provider',
        'provider_ref',
        'payload',
    ];

    protected $casts = [
        'so_tien' => 'decimal:2',
        'payload' => 'array',
    ];

    public function hoaDon()
    {
        return $this->belongsTo(HoaDon::class);
    }
}
