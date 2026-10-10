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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->cascadeOnDelete();
            $table->string('metode', 20)->default('transfer');
            $table->string('nomor_rekening', 30)->nullable();
            $table->string('nama_pengirim')->nullable();
            $table->string('rekening_pengirim', 50)->nullable();
            $table->decimal('jumlah', 12, 2)->nullable();
            $table->date('tanggal_bayar')->nullable();
            $table->string('status', 25)->default('menunggu');
            $table->string('referensi', 100)->nullable();
            $table->string('bukti')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
