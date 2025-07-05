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
        Schema::create('ziswafs', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary Key
            // Foreign Key ke nasabahs, nullable karena donatur bisa non-nasabah
            $table->foreignId('nasabah_id')->nullable()->constrained('nasabahs')->onDelete('set null');
            $table->string('nama_donatur', 255)->nullable(); // Jika nasabah_id null
            $table->enum('jenis_ziswaf', ['Zakat', 'Infaq', 'Sedekah', 'Wakaf']);
            $table->decimal('nominal', 18, 2);
            $table->date('tanggal_penerimaan');
            $table->string('metode_penerimaan', 100);
            $table->text('tujuan_penyaluran')->nullable();
            $table->enum('status_penyaluran', ['Tercatat', 'Tersalurkan', 'Dibatalkan'])->default('Tercatat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ziswafs');
    }
};
