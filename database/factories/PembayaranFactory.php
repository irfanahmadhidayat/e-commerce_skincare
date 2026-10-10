<?php

namespace Database\Factories;

use App\Models\Pembayaran;
use App\Models\Pesanan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pembayaran>
 */
class PembayaranFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pesanan_id' => Pesanan::factory(),
            'metode' => 'transfer',
            'nomor_rekening' => null,
            'nama_pengirim' => fake()->name(),
            'rekening_pengirim' => fake()->numerify('##########'),
            'jumlah' => 100000,
            'tanggal_bayar' => now(),
            'status' => 'menunggu',
            'referensi' => null,
            'bukti' => null,
        ];
    }
}
