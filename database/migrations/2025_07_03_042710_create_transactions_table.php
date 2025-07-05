<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('rekening_id')->nullable()->constrained('rekenings')->onDelete('set null');
            $table->foreignId('financing_card_id')->nullable()->constrained('financing_cards')->onDelete('set null');
            $table->enum('tipe_transaksi', ['Debit', 'Kredit', 'Transfer_Keluar', 'Transfer_Masuk', 'Pembayaran', 'Setoran', 'Penarikan', 'Bagi_Hasil', 'Biaya Admin', 'Zakat', 'Infaq', 'Pembayaran_Kartu_Pembiayaan']);
            $table->decimal('jumlah', 18, 2);
            $table->text('deskripsi')->nullable();
            $table->decimal('saldo_sebelum_transaksi', 18, 2);
            $table->decimal('saldo_setelah_transaksi', 18, 2);
            $table->string('nomor_referensi', 255)->unique()->nullable();
            $table->date('tanggal_transaksi');
            $table->time('waktu_transaksi');
            $table->enum('status', ['Berhasil', 'Gagal', 'Pending'])->default('Berhasil');
            $table->timestamps();
            $table->index(['rekening_id', 'tanggal_transaksi']);
            $table->index(['financing_card_id', 'tanggal_transaksi']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
