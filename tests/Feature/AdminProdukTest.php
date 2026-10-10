<?php

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::create(['name' => 'admin']);

    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');

    $this->kategori = Kategori::create([
        'nama' => 'Serum',
        'slug' => 'serum',
        'deskripsi' => null,
        'aktif' => true,
    ]);
});

test('admin dapat menambahkan produk baru', function () {
    $this->actingAs($this->admin)
        ->post('/admin/produk', [
            'nama' => 'Niacinamide Serum',
            'slug' => 'niacinamide-serum',
            'kategori_id' => $this->kategori->id,
            'deskripsi' => 'Test',
            'harga' => 110000,
            'stok' => 20,
            'aktif' => '1',
        ])->assertRedirect(route('admin.produk.index'));

    $this->assertDatabaseHas('produk', [
        'slug' => 'niacinamide-serum',
        'nama' => 'Niacinamide Serum',
        'aktif' => true,
    ]);
});

test('admin dapat mengedit produk tanpa melanggar unique slug miliknya sendiri', function () {
    $produk = Produk::create([
        'kategori_id' => $this->kategori->id,
        'nama' => 'Vitamin C Serum',
        'slug' => 'vitamin-c-serum',
        'deskripsi' => 'Lama',
        'harga' => 125000,
        'stok' => 10,
        'foto' => null,
        'aktif' => true,
    ]);

    $this->actingAs($this->admin)
        ->put('/admin/produk/'.$produk->id, [
            'nama' => 'Vitamin C Serum Updated',
            'slug' => 'vitamin-c-serum',
            'kategori_id' => $this->kategori->id,
            'deskripsi' => 'Baru',
            'harga' => 130000,
            'stok' => 5,
            'aktif' => '1',
        ])->assertRedirect(route('admin.produk.index'));

    $this->assertDatabaseHas('produk', [
        'id' => $produk->id,
        'nama' => 'Vitamin C Serum Updated',
        'harga' => '130000.00',
    ]);
});

test('slug produk duplikat saat menambah ditolak', function () {
    Produk::create([
        'kategori_id' => $this->kategori->id,
        'nama' => 'A',
        'slug' => 'duplikat',
        'deskripsi' => null,
        'harga' => 1000,
        'stok' => 1,
        'foto' => null,
        'aktif' => true,
    ]);

    $this->actingAs($this->admin)
        ->post('/admin/produk', [
            'nama' => 'B',
            'slug' => 'duplikat',
            'kategori_id' => $this->kategori->id,
            'deskripsi' => null,
            'harga' => 2000,
            'stok' => 2,
            'aktif' => '1',
        ])->assertSessionHasErrors('slug');
});
