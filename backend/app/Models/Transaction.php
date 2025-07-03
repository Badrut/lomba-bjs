<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'rekening_id',
        'financing_card_id',
        'tipe_transaksi',
        'jumlah',
        'deskripsi',
        'saldo_sebelum_transaksi',
        'saldo_setelah_transaksi',
        'nomor_referensi',
        'tanggal_transaksi',
        'waktu_transaksi',
        'status',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'saldo_sebelum_transaksi' => 'decimal:2',
        'saldo_setelah_transaksi' => 'decimal:2',
        'tanggal_transaksi' => 'date',
    ];

    // --- Relasi ---

    /**
     * Sebuah Transaksi dapat terkait dengan satu Rekening.
     */
    public function rekening()
    {
        return $this->belongsTo(Rekening::class);
    }

    /**
     * Sebuah Transaksi dapat terkait dengan satu Kartu Pembiayaan.
     */
    public function financingCard()
    {
        return $this->belongsTo(FinancingCard::class);
    }
}
