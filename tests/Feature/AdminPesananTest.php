<?php

use App\Models\DetailPesanan;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;
use Spatie\Permission\Models\Role;

test('admin memverifikasi pembayaran manual', function () {
    Role::create(['name' => 'admin']);

    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $customer = User::factory()->create();

    $pesanan = Pesanan::factory()->create(['customer_id' => $customer->id]);
    $pembayaran = Pembayaran::factory()->create(['pesanan_id' => $pesanan->id]);

    $this->actingAs($admin)
        ->post("/admin/pesanan/{$pesanan->id}/verifikasi")
        ->assertRedirect();

    expect($pesanan->fresh()->status)->toBe('dibayar');
    expect($pembayaran->fresh()->status)->toBe('terverifikasi');
});

test('admin menolak pembayaran', function () {
    Role::create(['name' => 'admin']);

    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $customer = User::factory()->create();
    $pesanan = Pesanan::factory()->create(['customer_id' => $customer->id]);
    $pembayaran = Pembayaran::factory()->create(['pesanan_id' => $pesanan->id]);

    $this->actingAs($admin)
        ->post("/admin/pesanan/{$pesanan->id}/tolak")
        ->assertRedirect();

    expect($pesanan->fresh()->status)->toBe('dibatalkan');
    expect($pembayaran->fresh()->status)->toBe('gagal');
});

test('stok produk berkurang saat pembayaran diverifikasi', function () {
    Role::create(['name' => 'admin']);

    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $customer = User::factory()->create();
    $produk = Produk::factory()->create(['stok' => 10]);

    $pesanan = Pesanan::factory()->create(['customer_id' => $customer->id]);
    DetailPesanan::factory()->create([
        'pesanan_id' => $pesanan->id,
        'produk_id' => $produk->id,
        'harga_satuan' => 100000,
        'jumlah' => 3,
    ]);

    $this->actingAs($admin)
        ->post("/admin/pesanan/{$pesanan->id}/verifikasi")
        ->assertRedirect();

    expect($produk->fresh()->stok)->toBe(7);
});
