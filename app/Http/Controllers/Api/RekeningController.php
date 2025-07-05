<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rekening;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RekeningController extends Controller
{
    public function index(Request $request)
    {
        $query = Rekening::with('nasabah'); 

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor_rekening', 'like', "%$search%")
                ->orWhereHas('nasabah', function ($q2) use ($search) {
                    $q2->where('nama_lengkap', 'like', "%$search%");
                });
            });
        }

        if ($jenis = $request->input('jenis_rekening')) {
            $query->where('jenis_rekening', $jenis);
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
            'nomor_rekening' => 'required|string|max:20|unique:rekenings,nomor_rekening',
            'jenis_rekening' => 'required|string|max:100',
            'akad' => 'required|string|max:50',
            'saldo' => 'nullable|numeric|min:0',
            'status' => 'in:Aktif,Beku,Tutup',
            'tanggal_buka' => 'required|date',
            'waktu_buka' => 'required|date_format:H:i:s',
            'jangka_waktu_bulan' => 'nullable|integer|min:1',
            'tanggal_jatuh_tempo_berjangka' => 'nullable|date|after_or_equal:tanggal_buka',
            'nisbah_nasabah_persen' => 'nullable|numeric|between:0,100',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $rekening = Rekening::create($validator->validated());

        return response()->json($rekening, 201);
    }

    public function show($id)
    {
        $rekening = Rekening::findOrFail($id);
        return response()->json($rekening);
    }

    public function update(Request $request, $id)
    {
        $rekening = Rekening::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nasabah_id' => 'sometimes|required|exists:nasabahs,id',
            'nomor_rekening' => 'sometimes|required|string|max:20|unique:rekenings,nomor_rekening,' . $id,
            'jenis_rekening' => 'sometimes|required|string|max:100',
            'akad' => 'sometimes|required|string|max:50',
            'saldo' => 'nullable|numeric|min:0',
            'status' => 'in:Aktif,Beku,Tutup',
            'tanggal_buka' => 'sometimes|required|date',
            'waktu_buka' => 'sometimes|required|date_format:H:i:s',
            'jangka_waktu_bulan' => 'nullable|integer|min:1',
            'tanggal_jatuh_tempo_berjangka' => 'nullable|date|after_or_equal:tanggal_buka',
            'nisbah_nasabah_persen' => 'nullable|numeric|between:0,100',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $rekening->update($validator->validated());

        return response()->json($rekening);
    }

    public function destroy($id)
    {
        $rekening = Rekening::findOrFail($id);
        $rekening->delete();

        return response()->json(['message' => 'Rekening berhasil dihapus.']);
    }
}
