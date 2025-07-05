<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Investasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvestasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Investasi::with('nasabah');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('jenis_investasi', 'like', "%$search%")
                ->orWhereHas('nasabah', function ($q2) use ($search) {
                    $q2->where('nama_lengkap', 'like', "%$search%");
                });
            });
        }

        if ($jenis = $request->input('jenis_investasi')) {
            $query->where('jenis_investasi', $jenis);
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
            'jenis_investasi' => 'required|string|max:100',
            'akad' => 'required|string|max:50',
            'nilai_investasi_pokok' => 'required|numeric|min:0',
            'nilai_saat_ini' => 'nullable|numeric|min:0',
            'jangka_waktu_investasi' => 'required|string|max:50',
            'tanggal_mulai' => 'required|date',
            'tanggal_jatuh_tempo' => 'nullable|date|after_or_equal:tanggal_mulai',
            'nisbah_imbal_hasil_nasabah' => 'nullable|numeric|between:0,100',
            'status' => 'required|in:Aktif,Selesai,Dibatalkan',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $investasi = Investasi::create($validator->validated());

        return response()->json($investasi, 201);
    }

    public function show($id)
    {
        $investasi = Investasi::with('nasabah')->findOrFail($id);
        return response()->json($investasi);
    }

    public function update(Request $request, $id)
    {
        $investasi = Investasi::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nasabah_id' => 'sometimes|required|exists:nasabahs,id',
            'jenis_investasi' => 'sometimes|required|string|max:100',
            'akad' => 'sometimes|required|string|max:50',
            'nilai_investasi_pokok' => 'sometimes|required|numeric|min:0',
            'nilai_saat_ini' => 'nullable|numeric|min:0',
            'jangka_waktu_investasi' => 'sometimes|required|string|max:50',
            'tanggal_mulai' => 'sometimes|required|date',
            'tanggal_jatuh_tempo' => 'nullable|date|after_or_equal:tanggal_mulai',
            'nisbah_imbal_hasil_nasabah' => 'nullable|numeric|between:0,100',
            'status' => 'sometimes|required|in:Aktif,Selesai,Dibatalkan',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $investasi->update($validator->validated());

        return response()->json($investasi);
    }

    public function destroy($id)
    {
        $investasi = Investasi::findOrFail($id);
        $investasi->delete();

        return response()->json(['message' => 'Investasi berhasil dihapus.']);
    }
}
