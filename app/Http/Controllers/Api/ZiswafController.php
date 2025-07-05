<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ziswaf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ZiswafController extends Controller
{
    public function index(Request $request)
    {
        $query = Ziswaf::query();

        if ($request->has('nama_donatur')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_donatur', 'like', '%' . $request->input('nama_donatur') . '%');

                $q->orWhereHas('nasabah', function ($sub) use ($request) {
                    $sub->where('nama_lengkap', 'like', '%' . $request->input('nama_donatur') . '%');
                });
            });
        }

        if ($request->has('tanggal_penerimaan')) {
            $query->whereDate('tanggal_penerimaan', $request->input('tanggal_penerimaan'));
        }

        if ($request->has('jenis_ziswaf')) {
            $query->where('jenis_ziswaf', $request->input('jenis_ziswaf'));
        }

        if ($request->has('status_penyaluran')) {
            $query->where('status_penyaluran', $request->input('status_penyaluran'));
        }

        return response()->json($query->latest()->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nasabah_id' => 'nullable|exists:nasabahs,id',
            'nama_donatur' => 'nullable|required_if:nasabah_id,null|string|max:255',
            'jenis_ziswaf' => 'required|in:Zakat,Infaq,Sedekah,Wakaf',
            'nominal' => 'required|numeric|min:1000',
            'tanggal_penerimaan' => 'required|date',
            'metode_penerimaan' => 'required|string|max:100',
            'tujuan_penyaluran' => 'nullable|string',
            'status_penyaluran' => 'in:Tercatat,Tersalurkan,Dibatalkan',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $ziswaf = Ziswaf::create($validator->validated());

        return response()->json($ziswaf, 201);
    }

    public function show($id)
    {
        $ziswaf = Ziswaf::findOrFail($id);
        return response()->json($ziswaf);
    }

    public function update(Request $request, $id)
    {
        $ziswaf = Ziswaf::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nasabah_id' => 'nullable|exists:nasabahs,id',
            'nama_donatur' => 'nullable|required_if:nasabah_id,null|string|max:255',
            'jenis_ziswaf' => 'sometimes|required|in:Zakat,Infaq,Sedekah,Wakaf',
            'nominal' => 'sometimes|required|numeric|min:1000',
            'tanggal_penerimaan' => 'sometimes|required|date',
            'metode_penerimaan' => 'sometimes|required|string|max:100',
            'tujuan_penyaluran' => 'nullable|string',
            'status_penyaluran' => 'in:Tercatat,Tersalurkan,Dibatalkan',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $ziswaf->update($validator->validated());

        return response()->json($ziswaf);
    }

    public function destroy($id)
    {
        $ziswaf = Ziswaf::findOrFail($id);
        $ziswaf->delete();

        return response()->json(['message' => 'Data ziswaf berhasil dihapus.']);
    }
}
