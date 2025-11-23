<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QRCode extends Model
{
    use HasFactory;

    protected $table = 'qr_codes';

    protected $fillable = [
        'code',
        'seat_number',
        'is_active',
        'last_used_at',
        'reset_at',
        'generated_for_date',
        'qr_image_path',
        'qr_generated',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_used_at' => 'datetime',
        'reset_at' => 'datetime',
        'generated_for_date' => 'datetime',
        'qr_generated' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeNeedingReset($query)
    {
        return $query->where('reset_at', '<=', now())
                    ->orWhereNull('reset_at');
    }

    public function markAsUsed()
    {
        $this->update([
            'last_used_at' => now(),
            'reset_at' => now()->addHours(config('app.qr_reset_hours', 24)),
        ]);
    }

    public function reset()
    {
        $this->update([
            'is_active' => true,
            'reset_at' => null,
        ]);
    }
}
