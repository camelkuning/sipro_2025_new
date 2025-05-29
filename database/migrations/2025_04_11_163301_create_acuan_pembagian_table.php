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
        Schema::create('acuan_pembagian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('akun_id')->nullable();
            $table->foreign('akun_id')->references('id')->on('akun')->onDelete('set null');
            $table->string('kategori');
            $table->integer('persentase');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acuan_pembagian');
    }
};
