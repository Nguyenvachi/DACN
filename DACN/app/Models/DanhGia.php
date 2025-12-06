<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DanhGia extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bac_si_id',
        'lich_hen_id',
        'rating',
        'noi_dung',
        'trang_thai',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bacSi()
    {
        return $this->belongsTo(BacSi::class);
    }

    public function lichHen()
    {
        return $this->belongsTo(LichHen::class);
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('trang_thai', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('trang_thai', 'pending');
    }

    // Helper methods
    public static function getAverageRating($bacSiId)
    {
        return self::where('bac_si_id', $bacSiId)
            ->approved()
            ->avg('rating');
    }

    public static function getTotalReviews($bacSiId)
    {
        return self::where('bac_si_id', $bacSiId)
            ->approved()
            ->count();
    }

    public static function getRatingDistribution($bacSiId)
    {
        return self::where('bac_si_id', $bacSiId)
            ->approved()
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->orderBy('rating', 'desc')
            ->pluck('count', 'rating')
            ->toArray();
    }
}
