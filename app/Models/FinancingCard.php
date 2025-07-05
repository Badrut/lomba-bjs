<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancingCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'nasabah_id',
        'pembiayaan_id',
        'nomor_kartu',
        'nama_pada_kartu',
        'jenis_kartu',
        'tanggal_kadaluarsa',
        'cvv',
        'total_limit',
        'limit_tersedia',
        'tanggal_cetak_tagihan',
        'status_kartu',
    ];

    protected $casts = [
        'tanggal_kadaluarsa' => 'date',
        'total_limit' => 'decimal:2',
        'limit_tersedia' => 'decimal:2',
        'tanggal_cetak_tagihan' => 'date',
    ];

    // --- Relasi ---

    /**
     * Sebuah Kartu Pembiayaan dimiliki oleh satu Nasabah.
     */
    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class);
    }

    /**
     * Sebuah Kartu Pembiayaan terkait dengan satu Pembiayaan induk.
     */
    public function pembiayaan()
    {
        return $this->belongsTo(Pembiayaan::class);
    }

    /**
     * Sebuah Kartu Pembiayaan memiliki banyak Transaksi.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
