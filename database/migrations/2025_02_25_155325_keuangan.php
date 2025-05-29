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
        Schema::create('keuangan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('akun_id')->nullable(); // tambahkan ini dulu jika belum ada
            $table->foreign('akun_id')->references('id')->on('akun')->onDelete('set null');
            $table->string('kode_keuangan')->unique(); // Contoh: AKUN2025-001, PROKER2025-KOMPELA-002
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->enum('tipe', ['kredit', 'debit']);
            $table->bigInteger('jumlah');
            $table->bigInteger('saldo_awal')->default(0);
            $table->bigInteger('saldo_akhir')->default(0);
            // $table->string('sumber_dana')->nullable(); // manual, alokasi_tahunan, kas_umum, dll
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan');
    }
};