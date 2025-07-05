<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Nasabah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NasabahController extends Controller
{
    public function index()
    {
        return response()->json(Nasabah::all());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|exists:users,id|unique:nasabahs,user_id',
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|size:16|unique:nasabahs,nik',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'status_perkawinan' => 'nullable|in:Belum Menikah,Menikah,Cerai Hidup,Cerai Mati',
            'pekerjaan' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:nasabahs,email',
            'nomor_telepon' => 'required|string|max:20|unique:nasabahs,nomor_telepon',
            'alamat_ktp' => 'required|string',
            'alamat_domisili' => 'nullable|string',
            'sumber_dana' => 'nullable|string|max:255',
            'tujuan_hubungan_bank' => 'nullable|string|max:255',
            'status_akun' => 'nullable|in:Aktif,Nonaktif,Blokir',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $nasabah = Nasabah::create($validator->validated());
        return response()->json($nasabah, 201);
    }

    public function show($id)
    {
        $nasabah = Nasabah::findOrFail($id);
        return response()->json($nasabah);
    }

    public function update(Request $request, $id)
    {
        $nasabah = Nasabah::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|exists:users,id|unique:nasabahs,user_id,' . $id,
            'nama_lengkap' => 'sometimes|required|string|max:255',
            'nik' => 'sometimes|required|string|size:16|unique:nasabahs,nik,' . $id,
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'sometimes|required|in:Laki-laki,Perempuan',
            'status_perkawinan' => 'nullable|in:Belum Menikah,Menikah,Cerai Hidup,Cerai Mati',
            'pekerjaan' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:nasabahs,email,' . $id,
            'nomor_telepon' => 'sometimes|required|string|max:20|unique:nasabahs,nomor_telepon,' . $id,
            'alamat_ktp' => 'sometimes|required|string',
            'alamat_domisili' => 'nullable|string',
            'sumber_dana' => 'nullable|string|max:255',
            'tujuan_hubungan_bank' => 'nullable|string|max:255',
            'status_akun' => 'nullable|in:Aktif,Nonaktif,Blokir',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $nasabah->update($validator->validated());
        return response()->json($nasabah);
    }

    public function destroy($id)
    {
        $nasabah = Nasabah::findOrFail($id);
        $nasabah->delete();
        return response()->json(['message' => 'Nasabah berhasil dihapus.']);
    }
}
