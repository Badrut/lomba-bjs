<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ziswaf extends Model
{
    use HasFactory;

    protected $fillable = [
        'nasabah_id',
        'nama_donatur',
        'jenis_ziswaf',
        'nominal',
        'tanggal_penerimaan',
        'metode_penerimaan',
        'tujuan_penyaluran',
        'status_penyaluran',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'tanggal_penerimaan' => 'date',
    ];

    // --- Relasi ---

    /**
     * Sebuah ZISWAF dapat dikaitkan dengan satu Nasabah (sebagai donatur).
     */
    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class);
    }
}
