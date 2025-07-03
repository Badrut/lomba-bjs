<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rekening extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nasabah_id',
        'nomor_rekening',
        'jenis_rekening',
        'akad',
        'saldo',
        'status',
        'tanggal_buka',
        'waktu_buka',
        'jangka_waktu_bulan',
        'tanggal_jatuh_tempo_berjangka',
        'nisbah_nasabah_persen',
    ];

    protected $casts = [
        'saldo' => 'decimal:2',
        'tanggal_buka' => 'date',
        'tanggal_jatuh_tempo_berjangka' => 'date',
        'nisbah_nasabah_persen' => 'decimal:2',
    ];

    // --- Relasi ---

    /**
     * Sebuah Rekening dimiliki oleh satu Nasabah.
     */
    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class);
    }

    /**
     * Sebuah Rekening memiliki banyak Transaksi.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
