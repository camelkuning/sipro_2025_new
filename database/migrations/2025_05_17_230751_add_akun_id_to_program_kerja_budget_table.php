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
        // Schema::table('program_kerja_budget', function (Blueprint $table) {
        //     $table->unsignedBigInteger('akun_id')->nullable()->after('tahun_alokasi');

        //     // Kalau pakai foreign key
        //     $table->foreign('akun_id')->references('id')->on('akun')->onDelete('set null');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('program_kerja_budget', function (Blueprint $table) {
        //     $table->dropForeign(['akun_id']);
        //     $table->dropColumn('akun_id');
        // });
    }
};
