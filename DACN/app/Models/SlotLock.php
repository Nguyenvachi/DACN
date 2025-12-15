<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SlotLock extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'ngay' => 'date',
        'expires_at' => 'datetime',
    ];

    public function isExpired(): bool
    {
        return $this->expires_at ? $this->expires_at->isPast() : false;
    }

    public function scopeActive(
        $query
    ) {
        return $query->where('expires_at', '>', Carbon::now());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bacSi()
    {
        return $this->belongsTo(BacSi::class);
    }
}
