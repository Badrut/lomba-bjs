<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pembiayaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PembiayaanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembiayaan::with('nasabah');

        if ($search = $request->input('search')) {
            $query->whereHas('nasabah', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%$search%");
            })->orWhere('id', 'like', "%$search%");
        }

        if ($jenis = $request->input('jenis_pembiayaan')) {
            $query->where('jenis_pembiayaan', $jenis);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        return response()->json($query->latest()->get());
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nasabah_id' => 'required|exists:nasabahs,id',
            'jenis_pembiayaan' => 'required|string|max:100',
            'akad' => 'required|string|max:50',
            'jumlah_pokok' => 'required|numeric|min:0',
            'margin_keuntungan' => 'nullable|numeric|min:0',
            'tenor_bulan' => 'required|integer|min:1',
            'tanggal_pengajuan' => 'required|date',
            'tanggal_pencairan' => 'nullable|date',
            'tanggal_jatuh_tempo_pertama' => 'nullable|date',
            'tanggal_jatuh_tempo_akhir' => 'nullable|date',
            'tujuan_pembiayaan' => 'nullable|string',
            'status' => 'required|in:Diajukan,Disetujui,Ditolak,Aktif,Lunas,Bermasalah',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $pembiayaan = Pembiayaan::create($validator->validated());

        return response()->json($pembiayaan, 201);
    }

    public function show($id)
    {
        $pembiayaan = Pembiayaan::with('nasabah')->findOrFail($id);
        return response()->json($pembiayaan);
    }

    public function update(Request $request, $id)
    {
        $pembiayaan = Pembiayaan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nasabah_id' => 'sometimes|required|exists:nasabahs,id',
            'jenis_pembiayaan' => 'sometimes|required|string|max:100',
            'akad' => 'sometimes|required|string|max:50',
            'jumlah_pokok' => 'sometimes|required|numeric|min:0',
            'margin_keuntungan' => 'nullable|numeric|min:0',
            'tenor_bulan' => 'sometimes|required|integer|min:1',
            'tanggal_pengajuan' => 'sometimes|required|date',
            'tanggal_pencairan' => 'nullable|date',
            'tanggal_jatuh_tempo_pertama' => 'nullable|date',
            'tanggal_jatuh_tempo_akhir' => 'nullable|date',
            'tujuan_pembiayaan' => 'nullable|string',
            'status' => 'sometimes|required|in:Diajukan,Disetujui,Ditolak,Aktif,Lunas,Bermasalah',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $pembiayaan->update($validator->validated());

        return response()->json($pembiayaan);
    }

    public function destroy($id)
    {
        $pembiayaan = Pembiayaan::findOrFail($id);
        $pembiayaan->delete();

        return response()->json(['message' => 'Pembiayaan berhasil dihapus.']);
    }
}
