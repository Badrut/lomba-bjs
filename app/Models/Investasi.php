<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'nasabah_id',
        'jenis_investasi',
        'akad',
        'nilai_investasi_pokok',
        'nilai_saat_ini',
        'jangka_waktu_investasi',
        'tanggal_mulai',
        'tanggal_jatuh_tempo',
        'nisbah_imbal_hasil_nasabah',
        'status',
    ];

    protected $casts = [
        'nilai_investasi_pokok' => 'decimal:2',
        'nilai_saat_ini' => 'decimal:2',
        'tanggal_mulai' => 'date',
        'tanggal_jatuh_tempo' => 'date',
        'nisbah_imbal_hasil_nasabah' => 'decimal:2',
    ];

    // --- Relasi ---

    /**
     * Sebuah Investasi dimiliki oleh satu Nasabah.
     */
    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class);
    }
}
