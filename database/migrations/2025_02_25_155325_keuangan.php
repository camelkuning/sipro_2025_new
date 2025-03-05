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
            $table->string('kode_keuangan', 10)->primary(); // Ubah jadi string biar bisa pakai format "AKUN01"
            $table->date('tanggal_awal');
            $table->date('tanggal_akhir');
            $table->decimal('konveksional', 15, 2);
            $table->decimal('inkonveksional', 15, 2);
            $table->decimal('total_penerimaan', 15, 2);
            $table->timestamp('waktu_input')->nullable();
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

