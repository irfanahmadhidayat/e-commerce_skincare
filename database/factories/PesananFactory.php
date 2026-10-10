<?php

namespace Database\Factories;

use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Pesanan>
 */
class PesananFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => User::factory(),
            'nomor' => 'ARL-'.strtoupper(Str::random(10)),
            'tanggal' => now(),
            'status' => 'menunggu pembayaran',
            'subtotal' => 100000,
            'ongkir' => 0,
            'total' => 100000,
            'nama_penerima' => fake()->name(),
            'no_hp' => fake()->phoneNumber(),
            'alamat' => fake()->address(),
            'catatan' => null,
        ];
    }
}
