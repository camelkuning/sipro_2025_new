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
        Schema::create('akun', function (Blueprint $table) {
            $table->id();
            $table->string('kode_akun')->unique(); // Contoh: AKUN2025, PROKER2025-001
            $table->string('nama_akun');            // Nama akun
            $table->enum('tipe_akun', ['keuangan', 'proker']); // Tipe akun
            $table->text('keterangan')->nullable(); // Penjelasan opsional
            $table->timestamps();                   // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akun');
    }
};
