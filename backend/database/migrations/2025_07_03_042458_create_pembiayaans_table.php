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
        Schema::create('pembiayaans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('nasabah_id')->constrained('nasabahs')->onDelete('cascade'); // Foreign Key ke nasabahs
            $table->string('jenis_pembiayaan', 100);
            $table->string('akad', 50);
            $table->decimal('jumlah_pokok', 18, 2);
            $table->decimal('margin_keuntungan', 18, 2)->nullable();
            $table->integer('tenor_bulan');
            $table->date('tanggal_pengajuan');
            $table->date('tanggal_pencairan')->nullable();
            $table->date('tanggal_jatuh_tempo_pertama')->nullable();
            $table->date('tanggal_jatuh_tempo_akhir')->nullable();
            $table->text('tujuan_pembiayaan')->nullable();
            $table->enum('status', ['Diajukan', 'Disetujui', 'Ditolak', 'Aktif', 'Lunas', 'Bermasalah']);
            $table->timestamps();
            $table->softDeletes(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembiayaans');
    }
};
