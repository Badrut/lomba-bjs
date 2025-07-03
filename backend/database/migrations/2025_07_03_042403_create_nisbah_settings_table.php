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
        Schema::create('nisbah_settings', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary Key
            $table->string('nama_produk', 100);
            $table->string('jenis_akad', 50);
            $table->year('tahun_berlaku');
            $table->string('bulan_berlaku', 2);
            $table->decimal('nisbah_nasabah_persen', 5, 2);
            $table->decimal('nisbah_bank_persen', 5, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->unique(['nama_produk', 'jenis_akad', 'tahun_berlaku', 'bulan_berlaku'], 'nisbah_unique_constraint');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nisbah_settings');
    }
};
