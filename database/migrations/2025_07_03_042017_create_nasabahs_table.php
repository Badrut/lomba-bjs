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
        Schema::create('nasabahs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->nullable()->unique()->constrained('users')->onDelete('cascade');

            $table->string('nama_lengkap');
            $table->string('nik', 16)->unique();
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->enum('status_perkawinan', ['Belum Menikah', 'Menikah', 'Cerai Hidup', 'Cerai Mati'])->nullable();
            $table->string('pekerjaan', 255)->nullable();
            $table->string('email', 255)->unique()->nullable();
            $table->string('nomor_telepon', 20)->unique();
            $table->text('alamat_ktp');
            $table->text('alamat_domisili')->nullable();
            $table->string('sumber_dana', 255)->nullable();
            $table->string('tujuan_hubungan_bank', 255)->nullable();
            $table->enum('status_akun', ['Aktif', 'Nonaktif', 'Blokir'])->default('Aktif');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nasabahs');
    }
};
