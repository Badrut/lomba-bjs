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
        Schema::create('investasis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('nasabah_id')->constrained('nasabahs')->onDelete('cascade'); 
            $table->string('jenis_investasi', 100);
            $table->string('akad', 50);
            $table->decimal('nilai_investasi_pokok', 18, 2);
            $table->decimal('nilai_saat_ini', 18, 2)->nullable();
            $table->string('jangka_waktu_investasi', 50);
            $table->date('tanggal_mulai');
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->decimal('nisbah_imbal_hasil_nasabah', 5, 2)->nullable();
            $table->enum('status', ['Aktif', 'Selesai', 'Dibatalkan']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investasis');
    }
};
