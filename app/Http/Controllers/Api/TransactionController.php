<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['rekening.nasabah', 'financingCard']);

        if ($request->has('id')) {
            $query->where('id', $request->input('id'));
        }

        if ($request->has('deskripsi')) {
            $query->where('deskripsi', 'like', '%' . $request->input('deskripsi') . '%');
        }

        if ($request->has('nama_nasabah')) {
            $query->whereHas('rekening.nasabah', function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->input('nama_nasabah') . '%');
            });
        }

        if ($request->has('tanggal_transaksi')) {
            $query->whereDate('tanggal_transaksi', $request->input('tanggal_transaksi'));
        }

        if ($request->has('tipe_transaksi')) {
            $query->where('tipe_transaksi', $request->input('tipe_transaksi'));
        }

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        return response()->json($query->latest()->get());
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rekening_id' => 'nullable|exists:rekenings,id',
            'financing_card_id' => 'nullable|exists:financing_cards,id',
            'tipe_transaksi' => 'required|in:Debit,Kredit,Transfer_Keluar,Transfer_Masuk,Pembayaran,Setoran,Penarikan,Bagi_Hasil,Biaya Admin,Zakat,Infaq,Pembayaran_Kartu_Pembiayaan',
            'jumlah' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'saldo_sebelum_transaksi' => 'required|numeric',
            'saldo_setelah_transaksi' => 'required|numeric',
            'nomor_referensi' => 'nullable|string|unique:transactions,nomor_referensi',
            'tanggal_transaksi' => 'required|date',
            'waktu_transaksi' => 'required|date_format:H:i:s',
            'status' => 'in:Berhasil,Gagal,Pending',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $transaksi = Transaction::create($validator->validated());
        return response()->json($transaksi, 201);
    }

    public function show($id)
    {
        $transaksi = Transaction::with(['rekening', 'financingCard'])->findOrFail($id);
        return response()->json($transaksi);
    }

    public function update(Request $request, $id)
    {
        $transaksi = Transaction::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'rekening_id' => 'nullable|exists:rekenings,id',
            'financing_card_id' => 'nullable|exists:financing_cards,id',
            'tipe_transaksi' => 'sometimes|required|in:Debit,Kredit,Transfer_Keluar,Transfer_Masuk,Pembayaran,Setoran,Penarikan,Bagi_Hasil,Biaya Admin,Zakat,Infaq,Pembayaran_Kartu_Pembiayaan',
            'jumlah' => 'sometimes|required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'saldo_sebelum_transaksi' => 'sometimes|required|numeric',
            'saldo_setelah_transaksi' => 'sometimes|required|numeric',
            'nomor_referensi' => 'nullable|string|unique:transactions,nomor_referensi,' . $id,
            'tanggal_transaksi' => 'sometimes|required|date',
            'waktu_transaksi' => 'sometimes|required|date_format:H:i:s',
            'status' => 'in:Berhasil,Gagal,Pending',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $transaksi->update($validator->validated());
        return response()->json($transaksi);
    }

    public function destroy($id)
    {
        $transaksi = Transaction::findOrFail($id);
        $transaksi->delete();

        return response()->json(['message' => 'Transaksi berhasil dihapus.']);
    }
}
