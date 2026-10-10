<?php

namespace Database\Factories;

use App\Models\DetailPesanan;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DetailPesanan>
 */
class DetailPesananFactory extends Factory
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
            'produk_id' => Produk::factory(),
            'harga_satuan' => 100000,
            'jumlah' => 1,
        ];
    }
}
