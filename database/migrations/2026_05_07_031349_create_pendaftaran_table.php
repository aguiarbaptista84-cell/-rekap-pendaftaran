<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->string('no_registrasi', 30)->unique();
            $table->string('nama_lengkap', 150);
            $table->string('no_bi', 30)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('jenis_kelamin', 10)->default('L');
            $table->string('alamat', 255)->nullable();
            $table->string('no_telpon', 20)->nullable();
            $table->enum('jenis_dokumen', [
                'passaporte',
                'bi',
                'rejistu_kriminal',
                'rdtl',
                'eleitoral',
                'sim',
            ]);
            $table->enum('kategori_sim', ['A', 'B', 'C', 'D'])->nullable();
            $table->enum('status', ['halo_foun', 'renova', 'lakon'])->default('halo_foun');
            $table->date('tanggal_daftar');
            $table->date('tanggal_selesai')->nullable();
            $table->string('nomor_dokumen', 50)->nullable();
            $table->text('keterangan')->nullable();
            $table->string('petugas', 100)->nullable();
            $table->string('munisipiu', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};
