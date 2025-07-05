<?php

namespace Database\Factories;

use App\Models\Rekening;
use App\Models\FinancingCard;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tipeTransaksi = $this->faker->randomElement([
            'Debit',
            'Kredit',
            'Transfer_Keluar',
            'Transfer_Masuk',
            'Pembayaran',
            'Setoran',
            'Penarikan',
            'Bagi_Hasil',
            'Biaya Admin',
            'Zakat',
            'Infaq',
            'Pembayaran_Kartu_Pembiayaan'
        ]);

        $amount = $this->faker->randomFloat(2, 1000, 10000000); // 1rb - 10 juta
        $currentDate = $this->faker->dateTimeBetween('-1 year', 'now');

        $rekening = Rekening::inRandomOrder()->first(); // Ambil rekening acak
        $financingCard = FinancingCard::inRandomOrder()->first(); // Ambil kartu acak

        // Logika sederhana untuk saldo sebelum/sesudah
        $saldoSebelum = $this->faker->randomFloat(2, 50000, 50000000);
        $saldoSesudah = $saldoSebelum;

        if ($tipeTransaksi === 'Kredit' || $tipeTransaksi === 'Setoran' || $tipeTransaksi === 'Transfer_Masuk' || $tipeTransaksi === 'Bagi_Hasil') {
            $saldoSesudah += $amount;
        } elseif ($tipeTransaksi === 'Debit' || $tipeTransaksi === 'Penarikan' || $tipeTransaksi === 'Transfer_Keluar' || $tipeTransaksi === 'Pembayaran' || $tipeTransaksi === 'Biaya Admin' || $tipeTransaksi === 'Zakat' || $tipeTransaksi === 'Infaq' || $tipeTransaksi === 'Pembayaran_Kartu_Pembiayaan') {
            $saldoSesudah -= $amount;
            if ($saldoSesudah < 0) $saldoSesudah = 0; // Hindari saldo negatif ekstrem untuk dummy
        }


        return [
            'rekening_id' => $rekening ? $rekening->id : null,
            'financing_card_id' => ($tipeTransaksi === 'Pembayaran_Kartu_Pembiayaan' && $financingCard) ? $financingCard->id : null,
            'tipe_transaksi' => $tipeTransaksi,
            'jumlah' => $amount,
            'deskripsi' => $this->faker->sentence(),
            'saldo_sebelum_transaksi' => $saldoSebelum,
            'saldo_setelah_transaksi' => $saldoSesudah,
            'nomor_referensi' => $this->faker->unique()->uuid(),
            'tanggal_transaksi' => $currentDate->format('Y-m-d'),
            'waktu_transaksi' => $currentDate->format('H:i:s'),
            'status' => $this->faker->randomElement(['Berhasil', 'Gagal']),
        ];
    }
}
