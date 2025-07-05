<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pembiayaan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nasabah_id',
        'jenis_pembiayaan',
        'akad',
        'jumlah_pokok',
        'margin_keuntungan',
        'tenor_bulan',
        'tanggal_pengajuan',
        'tanggal_pencairan',
        'tanggal_jatuh_tempo_pertama',
        'tanggal_jatuh_tempo_akhir',
        'tujuan_pembiayaan',
        'status',
    ];

    protected $casts = [
        'jumlah_pokok' => 'decimal:2',
        'margin_keuntungan' => 'decimal:2',
        'tanggal_pengajuan' => 'date',
        'tanggal_pencairan' => 'date',
        'tanggal_jatuh_tempo_pertama' => 'date',
        'tanggal_jatuh_tempo_akhir' => 'date',
    ];

    // --- Relasi ---

    /**
     * Sebuah Pembiayaan dimiliki oleh satu Nasabah.
     */
    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class);
    }

    /**
     * Sebuah Pembiayaan dapat memiliki banyak Kartu Pembiayaan.
     */
    public function financingCards()
    {
        return $this->hasMany(FinancingCard::class);
    }

    /**
     * Sebuah Pembiayaan dapat memiliki banyak Dokumen pendukung.
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
