<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'nasabah_id',
        'pembiayaan_id',
        'jenis_dokumen',
        'nama_file_asli',
        'path_file',
        'mime_type',
        'ukuran_file_bytes',
        'diupload_oleh_user_id',
    ];

    // --- Relasi ---

    /**
     * Sebuah Dokumen dapat terkait dengan satu Nasabah.
     */
    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class);
    }

    /**
     * Sebuah Dokumen dapat terkait dengan satu Pembiayaan.
     */
    public function pembiayaan()
    {
        return $this->belongsTo(Pembiayaan::class);
    }

    /**
     * Sebuah Dokumen diunggah oleh satu User.
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'diupload_oleh_user_id', 'id');
    }
}
