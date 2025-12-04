<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaDieuChinhBacSi extends Model
{
    protected $table = 'ca_dieu_chinh_bac_sis';

    protected $fillable = [
        'bac_si_id',
        'ngay',
        'gio_bat_dau',
        'gio_ket_thuc',
        'hanh_dong',
        'ly_do',
        'meta',
    ];

    protected $casts = [
        'ngay' => 'date',
        'meta' => 'array',
    ];

    public function bacSi(): BelongsTo
    {
        return $this->belongsTo(BacSi::class, 'bac_si_id');
    }
}