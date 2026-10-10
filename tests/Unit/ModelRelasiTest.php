<?php

use App\Models\DetailPesanan;
use App\Models\Kategori;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Support\Carbon;

test('produk belongsTo kategori dan kategori hasMany produk', function () {
    $kategori = Kategori::factory()->create();
    $produk = Produk::factory()->create(['kategori_id' => $kategori->id]);

    expect($produk->kategori->is($kategori))->toBeTrue();
    expect($kategori->produk()->pluck('id'))->toContain($produk->id);
});

test('pesanan hasMany detailPesanan', function () {
    $pesanan = Pesanan::factory()->create();
    $detail = DetailPesanan::factory()->create(['pesanan_id' => $pesanan->id]);

    expect($pesanan->detailPesanan()->pluck('id'))->toContain($detail->id);
});

test('pesanan hasOne pembayaran', function () {
    $pesanan = Pesanan::factory()->create();
    $pembayaran = Pembayaran::factory()->create(['pesanan_id' => $pesanan->id]);

    expect($pesanan->pembayaran->is($pembayaran))->toBeTrue();
});

test('detailPesanan belongsTo produk', function () {
    $produk = Produk::factory()->create();
    $detail = DetailPesanan::factory()->create(['produk_id' => $produk->id]);

    expect($detail->produk->is($produk))->toBeTrue();
});

test('cast harga dan tanggal berfungsi', function () {
    $produk = Produk::factory()->create(['harga' => 125000]);
    $pesanan = Pesanan::factory()->create();

    expect((string) $produk->harga)->toBe('125000.00');
    expect($pesanan->tanggal)->toBeInstanceOf(Carbon::class);
});
