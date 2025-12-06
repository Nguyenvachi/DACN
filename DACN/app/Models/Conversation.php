<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'benh_nhan_id',
        'bac_si_id',
        'lich_hen_id',
        'tieu_de',
        'trang_thai',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    // Relationships
    public function benhNhan()
    {
        return $this->belongsTo(User::class, 'benh_nhan_id');
    }

    public function bacSi()
    {
        return $this->belongsTo(BacSi::class, 'bac_si_id');
    }

    public function lichHen()
    {
        return $this->belongsTo(LichHen::class, 'lich_hen_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('trang_thai', 'Đang hoạt động');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('benh_nhan_id', $userId)
                    ->orWhere(function ($q) use ($userId) {
                        $q->whereHas('bacSi', function ($bacSiQuery) use ($userId) {
                            $bacSiQuery->where('user_id', $userId);
                        });
                    });
    }

    // Helper methods
    public function getUnreadCountForUser($userId)
    {
        return $this->messages()
                    ->where('user_id', '!=', $userId)
                    ->where('is_read', false)
                    ->count();
    }

    public function markAsReadForUser($userId)
    {
        $this->messages()
             ->where('user_id', '!=', $userId)
             ->where('is_read', false)
             ->update(['is_read' => true, 'read_at' => now()]);
    }

    public function getOtherParticipant($userId)
    {
        if ($this->benh_nhan_id == $userId) {
            return $this->bacSi->user ?? null;
        }
        return $this->benhNhan;
    }

    public function updateLastMessageTime()
    {
        $this->update(['last_message_at' => now()]);
    }
}
