<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    public $timestamps = false; // Karena hanya ada created_at, tidak ada updated_at

    protected $fillable = [
        'user_id',
        'tipe_aktivitas',
        'deskripsi',
        'ip_address',
        'user_agent',
        'data_lama_json',
        'data_baru_json',
    ];

    protected $casts = [
        'data_lama_json' => 'array', // Cast JSON kolom ke array PHP
        'data_baru_json' => 'array',
        'created_at' => 'datetime',
    ];

    // --- Relasi ---

    /**
     * Sebuah Activity Log dilakukan oleh satu User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
