<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FinancingCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FinancingCardController extends Controller
{
    public function index()
    {
        return response()->json(FinancingCard::with(['nasabah', 'pembiayaan'])->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nasabah_id' => 'required|exists:nasabahs,id',
            'pembiayaan_id' => 'required|exists:pembiayaans,id',
            'nomor_kartu' => 'required|string|max:20|unique:financing_cards,nomor_kartu',
            'nama_pada_kartu' => 'required|string|max:255',
            'jenis_kartu' => 'required|string|max:50',
            'tanggal_kadaluarsa' => 'required|date',
            'cvv' => 'nullable|string|max:5',
            'total_limit' => 'required|numeric|min:0',
            'limit_tersedia' => 'required|numeric|min:0',
            'tanggal_cetak_tagihan' => 'nullable|date',
            'status_kartu' => 'required|in:Aktif,Blokir,Nonaktif,Kadaluarsa,Hilang,Rusak',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $card = FinancingCard::create($validator->validated());
        return response()->json($card, 201);
    }

    public function show($id)
    {
        $card = FinancingCard::with(['nasabah', 'pembiayaan'])->findOrFail($id);
        return response()->json($card);
    }

    public function update(Request $request, $id)
    {
        $card = FinancingCard::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nasabah_id' => 'sometimes|required|exists:nasabahs,id',
            'pembiayaan_id' => 'sometimes|required|exists:pembiayaans,id',
            'nomor_kartu' => 'sometimes|required|string|max:20|unique:financing_cards,nomor_kartu,' . $id,
            'nama_pada_kartu' => 'sometimes|required|string|max:255',
            'jenis_kartu' => 'sometimes|required|string|max:50',
            'tanggal_kadaluarsa' => 'sometimes|required|date',
            'cvv' => 'nullable|string|max:5',
            'total_limit' => 'sometimes|required|numeric|min:0',
            'limit_tersedia' => 'sometimes|required|numeric|min:0',
            'tanggal_cetak_tagihan' => 'nullable|date',
            'status_kartu' => 'sometimes|required|in:Aktif,Blokir,Nonaktif,Kadaluarsa,Hilang,Rusak',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $card->update($validator->validated());
        return response()->json($card);
    }

    public function destroy($id)
    {
        $card = FinancingCard::findOrFail($id);
        $card->delete();

        return response()->json(['message' => 'Financing Card berhasil dihapus.']);
    }
}
