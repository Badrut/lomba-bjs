<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NisbahSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NisbahSettingController extends Controller
{
    public function index()
    {
        return response()->json(NisbahSetting::all());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_produk' => 'required|string|max:100',
            'jenis_akad' => 'required|string|max:50',
            'tahun_berlaku' => 'required|digits:4|integer|min:2000',
            'bulan_berlaku' => 'required|string|size:2',
            'nisbah_nasabah_persen' => 'required|numeric|between:0,100',
            'nisbah_bank_persen' => 'required|numeric|between:0,100',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Cek apakah kombinasi unik sudah ada
        $exists = NisbahSetting::where([
            'nama_produk' => $request->nama_produk,
            'jenis_akad' => $request->jenis_akad,
            'tahun_berlaku' => $request->tahun_berlaku,
            'bulan_berlaku' => $request->bulan_berlaku,
        ])->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Data dengan kombinasi tersebut sudah ada.'
            ], 409);
        }

        $nisbah = NisbahSetting::create($validator->validated());
        return response()->json($nisbah, 201);
    }

    public function show($id)
    {
        $nisbah = NisbahSetting::findOrFail($id);
        return response()->json($nisbah);
    }

    public function update(Request $request, $id)
    {
        $nisbah = NisbahSetting::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_produk' => 'sometimes|required|string|max:100',
            'jenis_akad' => 'sometimes|required|string|max:50',
            'tahun_berlaku' => 'sometimes|required|digits:4|integer|min:2000',
            'bulan_berlaku' => 'sometimes|required|string|size:2',
            'nisbah_nasabah_persen' => 'sometimes|required|numeric|between:0,100',
            'nisbah_bank_persen' => 'sometimes|required|numeric|between:0,100',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $nisbah->update($validator->validated());
        return response()->json($nisbah);
    }

    public function destroy($id)
    {
        $nisbah = NisbahSetting::findOrFail($id);
        $nisbah->delete();

        return response()->json(['message' => 'Nisbah setting berhasil dihapus.']);
    }
}
