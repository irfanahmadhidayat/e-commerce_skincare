<?php

namespace Database\Factories;

use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Produk>
 */
class ProdukFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kategori_id' => Kategori::factory(),
            'nama' => fake()->unique()->words(2, true),
            'slug' => Str::slug(fake()->unique()->words(4, true)),
            'deskripsi' => fake()->sentence(),
            'harga' => fake()->numberBetween(10000, 200000),
            'stok' => fake()->numberBetween(0, 100),
            'foto' => null,
            'aktif' => true,
        ];
    }
}
