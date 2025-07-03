<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tipe',
        'judul',
        'pesan',
        'link',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    // --- Relasi ---

    /**
     * Sebuah Notifikasi ditujukan kepada satu User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
