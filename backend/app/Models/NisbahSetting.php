<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NisbahSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_produk',
        'jenis_akad',
        'tahun_berlaku',
        'bulan_berlaku',
        'nisbah_nasabah_persen',
        'nisbah_bank_persen',
        'keterangan',
    ];

    protected $casts = [
        'nisbah_nasabah_persen' => 'decimal:2',
        'nisbah_bank_persen' => 'decimal:2',
    ];

}
