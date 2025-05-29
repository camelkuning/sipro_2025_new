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
        Schema::create('program_kerja_budget', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('akun_id')->nullable();
            $table->foreign('akun_id')->references('id')->on('akun')->onDelete('set null');
            $table->integer('tahun_alokasi'); // Misalnya: 2025
            $table->bigInteger('alokasi_tahun_depan')->default(0); // 30% dari pemasukan tahun ini
            $table->integer('tahun_budget')->default(0); // Tahun ini
            $table->bigInteger('budget_berjalan')->default(0); // Dana program kerja untuk tahun ini
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_kerja_budget');
    }
};
