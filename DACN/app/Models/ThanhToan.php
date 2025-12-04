<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThanhToan extends Model
{
    protected $fillable = [
        'hoa_don_id',
        'provider',
        'so_tien',
        'tien_te',
        'trang_thai',
        'transaction_ref',
        'idempotency_key',
        'paid_at',
        'payload',
    ];

    protected $casts = [
        'so_tien' => 'decimal:2',
        'paid_at' => 'datetime',
        'payload' => 'array',
    ];

    public function hoaDon()
    {
        return $this->belongsTo(HoaDon::class);
    }
}
