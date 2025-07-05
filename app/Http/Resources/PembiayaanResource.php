<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PembiayaanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nasabahId' => $this->nasabah_id,
            'nomorPembiayaan' => $this->nomor_pembiayaan,
            'jenisPembiayaan' => $this->jenis_pembiayaan,
            'jumlahPinjaman' => (float) $this->jumlah_pinjaman,
            'jumlahAngsuran' => (float) $this->jumlah_angsuran,
            'tenor' => $this->tenor,
            'tanggalMulai' => $this->tanggal_mulai?->format('Y-m-d H:i:s'),
            'tanggalJatuhTempo' => $this->tanggal_jatuh_tempo?->format('Y-m-d H:i:s'),
            'statusPembiayaan' => $this->status_pembiayaan,
            'createdAt' => $this->created_at->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
