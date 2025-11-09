<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'seat_qr_id',
        'name',
        'phone',
        'email',
        'date_of_birth',
        'whatsapp_optin',
        'joke',
        'submitted_at',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'whatsapp_optin' => 'boolean',
        'submitted_at' => 'datetime',
    ];

    public function getAgeAttribute()
    {
        return Carbon::parse($this->date_of_birth)->age;
    }

    public function getAgeBracketAttribute()
    {
        $age = $this->age;
        
        if ($age < 18) return 'Under 18';
        if ($age < 25) return '18-24';
        if ($age < 35) return '25-34';
        if ($age < 45) return '35-44';
        if ($age < 55) return '45-54';
        if ($age < 65) return '55-64';
        return '65+';
    }

    public function scopeWhatsappOptedIn($query)
    {
        return $query->where('whatsapp_optin', true);
    }

    public function scopeRecentSubmissions($query, $days = 7)
    {
        return $query->where('submitted_at', '>=', now()->subDays($days));
    }
}
