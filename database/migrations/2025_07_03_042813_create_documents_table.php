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
        Schema::create('documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('nasabah_id')->nullable()->constrained('nasabahs')->onDelete('cascade');
            $table->foreignId('pembiayaan_id')->nullable()->constrained('pembiayaans')->onDelete('cascade');
            $table->string('jenis_dokumen', 100);
            $table->string('nama_file_asli', 255);
            $table->string('path_file', 255);
            $table->string('mime_type', 100);
            $table->bigInteger('ukuran_file_bytes')->unsigned();
            $table->foreignId('diupload_oleh_user_id')->nullable()->constrained('users')->onDelete('set null'); // Siapa yang mengunggah
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
