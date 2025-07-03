<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key_setting',
        'value_setting',
        'tipe_value',
        'deskripsi',
        'updated_by_user_id',
    ];

    // --- Relasi ---

    /**
     * Sebuah System Setting terakhir diperbarui oleh satu User.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_user_id', 'id');
    }
}
