<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NasabahResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'userId' => $this->user_id,
            'namaLengkap' => $this->nama_lengkap,
            'nik' => $this->nik,
            'tempatLahir' => $this->tempat_lahir,
            'tanggalLahir' => $this->tanggal_lahir,
            'jenisKelamin' => $this->jenis_kelamin,
            'statusPerkawinan' => $this->status_perkawinan,
            'pekerjaan' => $this->pekerjaan,
            'email' => $this->email,
            'nomorTelepon' => $this->nomor_telepon,
            'alamatKtp' => $this->alamat_ktp,
            'alamatDomisili' => $this->alamat_domisili,
            'sumberDana' => $this->sumber_dana,
            'tujuanHubunganBank' => $this->tujuan_hubungan_bank,
            'statusAkun' => $this->status_akun,
            'createdAt' => $this->created_at->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updated_at->format('Y-m-d H:i:s'),
            'user' => new UserResource($this->whenLoaded('user')),
            'rekenings' => RekeningResource::collection($this->whenLoaded('rekenings')),
            'pembiayaans' => PembiayaanResource::collection($this->whenLoaded('pembiayaans')),
        ];
    }
}
