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
        Schema::create('financing_cards', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary Key
            $table->foreignId('nasabah_id')->constrained('nasabahs')->onDelete('cascade'); // Foreign Key ke nasabahs
            $table->foreignId('pembiayaan_id')->constrained('pembiayaans')->onDelete('cascade'); // Foreign Key ke pembiayaans
            $table->string('nomor_kartu', 20)->unique();
            $table->string('nama_pada_kartu', 255);
            $table->string('jenis_kartu', 50);
            $table->date('tanggal_kadaluarsa');
            $table->string('cvv', 5)->nullable();
            $table->decimal('total_limit', 18, 2);
            $table->decimal('limit_tersedia', 18, 2);
            $table->date('tanggal_cetak_tagihan')->nullable();
            $table->enum('status_kartu', ['Aktif', 'Blokir', 'Nonaktif', 'Kadaluarsa', 'Hilang', 'Rusak'])->default('Aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financing_cards');
    }
};
