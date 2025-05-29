<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // public function up(): void
    // {
    //     Schema::create('program_kerja', function (Blueprint $table) {
    //         $table->id();
    //         $table->string('kode_program_kerja')->unique();
    //         $table->string('nama_program_kerja');
    //         $table->string('komisi_program_kerja');
    //         $table->string('nama_ketua_program_kerja');
    //         $table->string('nama_majelis_pendamping');
    //         $table->string('tanggal_mulai')->nullable();
    //         $table->string('tanggal_selesai')->nullable();
    //         $table->string('keterangan')->nullable();
    //         $table->decimal('anggaran_digunakan', 15, 2)->default(0);
    //         $table->decimal('tambahan_dana_kebijakan', 15, 2)->nullable();
    //         $table->integer('tahun'); // penting untuk fitur arsip
    //         $table->enum('status', ['aktif', 'diarsipkan'])->default('aktif'); // tracking status proker
    //         $table->timestamps();
    //     });
    // }

    // /**
    //  * Reverse the migrations.
    //  */
    // public function down(): void
    // {
    //     //
    // }
    public function up()
    {
        Schema::create('program_kerja', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('akun_id')->nullable();
            $table->foreign('akun_id')->references('id')->on('akun')->onDelete('set null');
            $table->string('kode_program_kerja')->unique();
            $table->string('nama_program_kerja'); // nama program kerja
            $table->string('komisi_program_kerja'); // nama komisi
            $table->string('nama_ketua_program_kerja'); // nama ketua program kerja
            $table->string('nama_majelis_pendamping'); // nama majelis pendamping
            $table->date('tanggal_mulai')->nullable(); // tanggal mulai program kerja
            $table->date('tanggal_selesai')->nullable(); // tanggal selesai program kerja
            $table->text('keterangan')->nullable(); // keterangan program kerja
            $table->decimal('anggaran_digunakan', 15, 2)->default(0); // anggaran yang digunakan
            $table->decimal('tambahan_dana_kebijakan', 15, 2)->nullable(); // tambahan dana kebijakan
            $table->string('tahun', 4); // contoh: '2025'
            $table->enum('status', ['aktif', 'selesai'])->default('aktif');
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('program_kerja');
    }
};
