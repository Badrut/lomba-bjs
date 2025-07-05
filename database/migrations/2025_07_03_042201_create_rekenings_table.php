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
        Schema::create('rekenings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('nasabah_id')->constrained('nasabahs')->onDelete('cascade');
            $table->string('nomor_rekening', 20)->unique();
            $table->string('jenis_rekening', 100);
            $table->string('akad', 50);
            $table->decimal('saldo', 18, 2)->default(0.00);
            $table->enum('status', ['Aktif', 'Beku', 'Tutup'])->default('Aktif');
            $table->date('tanggal_buka');
            $table->time('waktu_buka');
            $table->integer('jangka_waktu_bulan')->nullable();
            $table->date('tanggal_jatuh_tempo_berjangka')->nullable();
            $table->decimal('nisbah_nasabah_persen', 5, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekenings');
    }
};
