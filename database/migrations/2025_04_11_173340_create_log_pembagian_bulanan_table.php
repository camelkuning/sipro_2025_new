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
        Schema::create('log_pembagian_bulanan', function (Blueprint $table) {
            $table->id();
            $table->integer('bulan');         // Contoh: 4 untuk April
            $table->integer('tahun');         // Contoh: 2025
            $table->date('tanggal_pembagian'); // Kapan tombol diklik
            $table->enum('status', ['belum', 'selesai']); // Status pembagian
            $table->timestamps();
    
            $table->unique(['bulan', 'tahun']); // Supaya tidak double log untuk bulan yang sama
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_pembagian_bulanan');
    }
};
