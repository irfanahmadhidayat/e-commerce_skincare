<?php

namespace Database\Seeders;

use App\Models\DataToko;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'customer']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            ['name' => 'Admin', 'password' => bcrypt('admin123')],
        );
        $admin->assignRole('admin');

        $customer = User::firstOrCreate(
            ['email' => 'cust@gmail.com'],
            ['name' => 'Customer', 'password' => bcrypt('cust123')],
        );
        $customer->assignRole('customer');

        $kategori = [
            ['nama' => 'Serum', 'slug' => 'serum', 'deskripsi' => 'Perawatan wajah fokus', 'aktif' => true],
            ['nama' => 'Moisturizer', 'slug' => 'moisturizer', 'deskripsi' => 'Pelembap wajah', 'aktif' => true],
            ['nama' => 'Cleanser', 'slug' => 'cleanser', 'deskripsi' => 'Pembersih wajah', 'aktif' => true],
        ];
        foreach ($kategori as $item) {
            Kategori::firstOrCreate(['slug' => $item['slug']], $item);
        }

        Produk::firstOrCreate(['slug' => 'vitamin-c-serum'], [
            'kategori_id' => Kategori::where('slug', 'serum')->value('id'),
            'nama' => 'Vitamin C Serum',
            'slug' => 'vitamin-c-serum',
            'deskripsi' => 'Serum pencerah dengan vitamin C',
            'harga' => 125000,
            'stok' => 40,
            'foto' => null,
            'aktif' => true,
        ]);

        Produk::firstOrCreate(['slug' => 'hydrating-moisturizer'], [
            'kategori_id' => Kategori::where('slug', 'moisturizer')->value('id'),
            'nama' => 'Hydrating Moisturizer',
            'slug' => 'hydrating-moisturizer',
            'deskripsi' => 'Pelembap harian untuk kulit kering',
            'harga' => 95000,
            'stok' => 35,
            'foto' => null,
            'aktif' => true,
        ]);

        Produk::firstOrCreate(['slug' => 'gentle-cleanser'], [
            'kategori_id' => Kategori::where('slug', 'cleanser')->value('id'),
            'nama' => 'Gentle Cleanser',
            'slug' => 'gentle-cleanser',
            'deskripsi' => 'Pembersih lembut untuk kulit sensitif',
            'harga' => 80000,
            'stok' => 30,
            'foto' => null,
            'aktif' => true,
        ]);

        Produk::firstOrCreate(['slug' => 'foaming-cleanser'], [
            'kategori_id' => Kategori::where('slug', 'cleanser')->value('id'),
            'nama' => 'Foaming Cleanser',
            'slug' => 'foaming-cleanser',
            'deskripsi' => 'Pembersih busa untuk kulit berminyak',
            'harga' => 70000,
            'stok' => 25,
            'foto' => null,
            'aktif' => true,
        ]);

        DataToko::firstOrCreate(['id' => 1], [
            'nama_toko' => 'Toko Skincare',
            'kontak' => '0812-3456-7890',
            'alamat' => 'Jl. Contoh No. 1, Bandung',
            'deskripsi' => 'Toko skincare terpercaya',
            'rekening' => [
                ['bank' => 'BCA', 'nomor' => '1234567890', 'atas_nama' => 'Toko Skincare'],
                ['bank' => 'Mandiri', 'nomor' => '9876543210', 'atas_nama' => 'Toko Skincare'],
            ],
        ]);
    }
}
