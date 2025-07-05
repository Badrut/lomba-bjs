<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nasabah extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'status_perkawinan',
        'pekerjaan',
        'email',
        'nomor_telepon',
        'alamat_ktp',
        'alamat_domisili',
        'sumber_dana',
        'tujuan_hubungan_bank',
        'status_akun',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    // --- Relasi ---

    /**
     * Seorang Nasabah dapat memiliki satu User (akun login).
     */
    public function user()
    {
        // Parameter kedua (user_id) adalah foreign key di tabel nasabahs ini.
        // Parameter ketiga (id) adalah local key di tabel users.
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Seorang Nasabah memiliki banyak Rekening.
     */
    public function rekenings()
    {
        return $this->hasMany(Rekening::class);
    }

    /**
     * Seorang Nasabah memiliki banyak Pembiayaan.
     */
    public function pembiayaans()
    {
        return $this->hasMany(Pembiayaan::class);
    }

    /**
     * Seorang Nasabah memiliki banyak Investasi.
     */
    public function investasis()
    {
        return $this->hasMany(Investasi::class);
    }

    /**
     * Seorang Nasabah dapat melakukan banyak ZISWAF (sebagai donatur).
     */
    public function ziswafs()
    {
        return $this->hasMany(Ziswaf::class);
    }

    /**
     * Seorang Nasabah memiliki banyak Kartu Pembiayaan.
     */
    public function financingCards()
    {
        return $this->hasMany(FinancingCard::class);
    }

    /**
     * Seorang Nasabah memiliki banyak Dokumen pendukung.
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }


}
