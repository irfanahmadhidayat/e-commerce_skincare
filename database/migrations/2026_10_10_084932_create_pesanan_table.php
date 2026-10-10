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
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->dateTime('tanggal')->default(now());
            $table->string('status', 30)->default('menunggu pembayaran');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('ongkir', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->string('nama_penerima');
            $table->text('alamat');
            $table->string('no_hp', 25);
            $table->string('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
