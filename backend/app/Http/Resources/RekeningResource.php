<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RekeningResource extends JsonResource
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
            'nomorRekening' => $this->nomor_rekening,
            'jenisRekening' => $this->jenis_rekening,
            'saldo' => (float) $this->saldo, // Pastikan formatnya sebagai float/number
            'statusRekening' => $this->status_rekening,
            'tanggalPembukaan' => $this->tanggal_pembukaan?->format('Y-m-d H:i:s'),
            'createdAt' => $this->created_at->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
