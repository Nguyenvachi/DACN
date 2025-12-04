<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LichNghi extends Model
{
    protected $fillable = [
        'bac_si_id',
        'ngay',
        'bat_dau',
        'ket_thuc',
        'ly_do',
    ];

    protected $casts = [
        'ngay' => 'date',
    ];

    public function bacSi()
    {
        return $this->belongsTo(BacSi::class, 'bac_si_id');
    }
}
