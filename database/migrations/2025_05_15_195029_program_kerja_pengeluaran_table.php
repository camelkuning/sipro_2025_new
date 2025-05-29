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
        Schema::create('program_kerja_pengeluaran', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('program_kerja_id');
            $table->date('tanggal');
            $table->string('keterangan');
            $table->bigInteger('jumlah');
            $table->timestamps();

            $table->foreign('program_kerja_id')->references('id')->on('program_kerja')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_kerja_pengeluaran');
    }
};
